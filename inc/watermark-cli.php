<?php
/**
 * WP-CLI: массовое наложение вотермарки на изображения товаров.
 *
 * Использует публичный API плагина Image Watermark (dFactory):
 *   Image_Watermark()                        -> экземпляр плагина
 *   $plugin->get_upload_handler()            -> сервис обработки
 *   $handler->describe_operation_eligibility()
 *   $handler->apply_watermark( $data, $id, 'manual' )
 *
 * Запуск:
 *   wp belgranit watermark-doctor
 *   wp belgranit watermark-products --dry-run
 *   wp belgranit watermark-products --ids=123,456
 *   wp belgranit watermark-products --limit=100
 *   wp belgranit watermark-products --include-categories
 *
 * @package BelGranit
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

/**
 * Массовое наложение вотермарки на изображения товаров.
 */
class Belgranit_Watermark_CLI {

	/**
	 * Наложить вотермарку на изображения товаров.
	 *
	 * ## OPTIONS
	 *
	 * [--ids=<id,id,...>]
	 * : Обработать только указанные ID вложений (через запятую). Если не задано — берутся все изображения товаров.
	 *
	 * [--limit=<n>]
	 * : Обработать не более N изображений.
	 *
	 * [--include-categories]
	 * : Также обработать изображения категорий товаров (product_cat thumbnail).
	 *
	 * [--force]
	 * : Обрабатывать повторно даже уже помеченные изображения.
	 *
	 * [--dry-run]
	 * : Только показать, что будет обработано, без изменений файлов.
	 *
	 * ## EXAMPLES
	 *
	 *     wp belgranit watermark-products --dry-run
	 *     wp belgranit watermark-products --ids=123,456
	 *     wp belgranit watermark-products --limit=100
	 *     wp belgranit watermark-products --include-categories
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Позиционные аргументы.
	 * @param array $assoc_args Именованные аргументы.
	 * @return void
	 */
	public function products( $args, $assoc_args ) {
		$plugin  = $this->get_plugin();
		$handler = $this->get_handler( $plugin );

		if ( ! $plugin || ! $handler ) {
			WP_CLI::error( 'Плагин Image Watermark не активен или его API недоступен. Запустите: wp belgranit watermark-doctor' );
		}

		if ( ! method_exists( $plugin, 'get_extension' ) || ! $plugin->get_extension() ) {
			WP_CLI::error( 'Нет доступного движка обработки изображений (нужен GD или Imagick).' );
		}

		$backup_enabled = ! empty( $plugin->options['backup']['backup_image'] );
		if ( ! $backup_enabled ) {
			WP_CLI::warning( 'В настройках плагина выключены бэкапы. С 2.0.13 применение требует валидного бэкапа — включите «Backup» перед запуском.' );
		}

		$dry_run = ! empty( $assoc_args['dry-run'] );
		$force   = ! empty( $assoc_args['force'] );
		$limit   = isset( $assoc_args['limit'] ) ? absint( $assoc_args['limit'] ) : 0;
		$ids     = $this->get_attachment_ids( $assoc_args );

		if ( empty( $ids ) ) {
			WP_CLI::warning( 'Изображения товаров не найдены. Проверьте товары/галереи или передайте --ids=...' );
			return;
		}

		$allowed_mime    = $plugin->get_allowed_mime_types();
		$watermarked_key = $plugin->get_watermarked_meta_key();

		// Принудительно покрываем все размеры, которые реально выводит тема,
		// независимо от галочек «размеры изображений» в настройках плагина.
		$force_sizes = array(
			'full',
			'large',
			'medium',
			'medium_large',
			'thumbnail',
			'woocommerce_single',
			'woocommerce_thumbnail',
			'woocommerce_gallery_thumbnail',
		);
		$size_filter = function ( $options ) use ( $force_sizes ) {
			if ( empty( $options['watermark_on'] ) || ! is_array( $options['watermark_on'] ) ) {
				$options['watermark_on'] = array();
			}
			foreach ( $force_sizes as $size ) {
				$options['watermark_on'][ $size ] = 1;
			}
			return $options;
		};
		add_filter( 'iw_watermark_options', $size_filter, 99 );

		$processed = 0;
		$done      = 0;
		$skipped   = 0;
		$failed    = 0;
		$failures  = array();

		$progress = ( $dry_run || $limit ) ? null : \WP_CLI\Utils\make_progress_bar( 'Наложение вотермарки', count( $ids ) );

		foreach ( $ids as $id ) {
			if ( ( $dry_run || $limit ) && $processed >= ( $limit ? $limit : PHP_INT_MAX ) ) {
				break;
			}
			$processed++;

			$mime = get_post_mime_type( $id );
			if ( ! in_array( $mime, $allowed_mime, true ) ) {
				$skipped++;
				WP_CLI::log( sprintf( '• #%d — пропуск: неподдерживаемый формат (%s).', $id, $mime ? $mime : 'unknown' ) );
				continue;
			}

			if ( ! $force && get_post_meta( $id, $watermarked_key, true ) ) {
				$skipped++;
				WP_CLI::log( sprintf( '• #%d — уже помечено.', $id ) );
				continue;
			}

			$eligibility = $handler->describe_operation_eligibility( $id, 'manual-apply' );

			if ( empty( $eligibility['valid'] ) ) {
				$code = isset( $eligibility['code'] ) ? $eligibility['code'] : '';
				if ( 'manual_disabled' === $code ) {
					WP_CLI::error( 'В настройках плагина выключено «Manual watermarking». Включите его (Image Watermark → Settings) и повторите.' );
				}

				$skipped++;
				WP_CLI::log( sprintf( '• #%d — пропуск: %s', $id, $eligibility['error'] ) );
				continue;
			}

			if ( $dry_run ) {
				$done++;
				WP_CLI::log( sprintf( '• #%d — будет обработано.', $id ) );
				continue;
			}

			$data    = wp_get_attachment_metadata( $id, false );
			$result  = $handler->apply_watermark( $data, $id, 'manual' );
			$outcome = $handler->get_last_operation_outcome();

			$error = '';
			if ( is_array( $result ) && ! empty( $result['error'] ) ) {
				$error = $result['error'];
			} elseif ( ! empty( $outcome ) && isset( $outcome['outcome'] ) && 'complete' !== $outcome['outcome'] ) {
				$error = isset( $outcome['message'] ) ? $outcome['message'] : 'неполная операция';
			}

			if ( $error ) {
				$failed++;
				$failures[ $id ] = $error;
				WP_CLI::log( sprintf( '✗ #%d — ошибка: %s', $id, $error ) );
			} else {
				$sizes = ! empty( $outcome['sizes'] ) ? implode( ', ', (array) $outcome['sizes'] ) : '';
				$done++;
				WP_CLI::log( sprintf( '✓ #%d — готово%s.', $id, $sizes ? ' [' . $sizes . ']' : '' ) );
			}

			if ( $progress ) {
				$progress->tick();
			}
		}

		if ( $progress ) {
			$progress->finish();
		}

		remove_filter( 'iw_watermark_options', $size_filter, 99 );

		if ( ! empty( $failures ) ) {
			WP_CLI::log( '' );
			WP_CLI::log( 'Ошибки:' );
			foreach ( $failures as $id => $message ) {
				WP_CLI::log( sprintf( '  #%d — %s', $id, $message ) );
			}
		}

		$summary = sprintf(
			'%s: обработано %d, успешно %d, пропущено %d, ошибок %d.',
			$dry_run ? 'Проверка (dry-run)' : 'Готово',
			$processed,
			$done,
			$skipped,
			$failed
		);

		if ( $failed > 0 ) {
			WP_CLI::warning( $summary );
		} else {
			WP_CLI::success( $summary );
		}
	}

	/**
	 * Диагностика окружения и настроек плагина.
	 *
	 * ## EXAMPLES
	 *
	 *     wp belgranit watermark-doctor
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Позиционные аргументы.
	 * @param array $assoc_args Именованные аргументы.
	 * @return void
	 */
	public function doctor( $args, $assoc_args ) {
		$rows = array();

		$rows[] = array( 'WP-CLI', WP_CLI_VERSION );
		$rows[] = array( 'PHP', PHP_VERSION );
		$rows[] = array( 'GD', function_exists( 'imagecreatetruecolor' ) ? 'да' : 'нет' );
		$rows[] = array( 'Imagick', class_exists( 'Imagick' ) ? 'да' : 'нет' );

		$plugin  = $this->get_plugin();
		$handler = $this->get_handler( $plugin );

		$rows[] = array( 'Плагин Image Watermark', $plugin ? 'ПОДКЛЮЧЁН' : 'НЕ НАЙДЕН' );
		if ( $plugin ) {
			$version = isset( $plugin->defaults['version'] ) ? $plugin->defaults['version'] : '?';
			$rows[]  = array( 'Версия', $version );
			$rows[]  = array( 'get_upload_handler()', method_exists( $plugin, 'get_upload_handler' ) ? 'есть' : 'НЕТ' );
			$rows[]  = array( 'Движок', method_exists( $plugin, 'get_extension' ) ? ( $plugin->get_extension() ? $plugin->get_extension() : 'нет' ) : '?' );

			$opts = isset( $plugin->options ) && is_array( $plugin->options ) ? $plugin->options : array();
			$rows[] = array( 'Backup', ! empty( $opts['backup']['backup_image'] ) ? 'вкл' : 'ВЫКЛ' );
			$rows[] = array( 'Manual watermarking', ! empty( $opts['watermark_image']['manual_watermarking'] ) ? 'вкл' : 'ВЫКЛ' );
			$rows[] = array( 'Тип вотермарки', isset( $opts['watermark_image']['type'] ) ? $opts['watermark_image']['type'] : '?' );
			$rows[] = array( 'Позиция', isset( $opts['watermark_image']['position'] ) ? $opts['watermark_image']['position'] : '?' );
			$rows[] = array( 'Прозрачность', isset( $opts['watermark_image']['transparent'] ) ? $opts['watermark_image']['transparent'] : '?' );
			$rows[] = array( 'Apply on', isset( $opts['watermark_apply_on'] ) ? $opts['watermark_apply_on'] : '?' );
			$sizes_on = isset( $opts['watermark_on'] ) && is_array( $opts['watermark_on'] ) ? array_keys( array_filter( $opts['watermark_on'] ) ) : array();
			$rows[] = array( 'Размеры (watermark_on)', $sizes_on ? implode( ', ', $sizes_on ) : 'ПУСТО — плагин ничего не помечает' );
			$rows[] = array( 'Backup-папка доступна', property_exists( $plugin, 'is_backup_folder_writable' ) ? ( $plugin->is_backup_folder_writable ? 'да' : ( null === $plugin->is_backup_folder_writable ? 'не проверено' : 'НЕТ' ) ) : '?' );
		}

		$ids = $this->get_attachment_ids( array() );
		$rows[] = array( 'Найдено изображений товаров', count( $ids ) );

		if ( $plugin && $handler && ! empty( $ids ) ) {
			$sample = $ids[0];
			$elig   = $handler->describe_operation_eligibility( $sample, 'manual-apply' );
			$rows[] = array(
				'Тест #' . $sample,
				! empty( $elig['valid'] ) ? 'OK' : sprintf( 'НЕ ГОТОВО (%s): %s', isset( $elig['code'] ) ? $elig['code'] : '?', isset( $elig['error'] ) ? $elig['error'] : '' ),
			);
		}

		\WP_CLI\Utils\format_items( 'table', $rows, array( 'Параметр', 'Значение' ) );

		if ( ! $plugin ) {
			WP_CLI::warning( 'Плагин не активен — команда watermark-products работать не будет.' );
		} elseif ( empty( $ids ) ) {
			WP_CLI::warning( 'Не найдено изображений товаров — проверьте товары или передайте --ids.' );
		} else {
			WP_CLI::success( 'Диагностика завершена.' );
		}
	}

	/**
	 * Получить экземпляр плагина, устойчиво к версиям.
	 *
	 * @return object|null
	 */
	private function get_plugin() {
		if ( function_exists( 'Image_Watermark' ) ) {
			$instance = call_user_func( 'Image_Watermark' );
			if ( is_object( $instance ) ) {
				return $instance;
			}
		}

		if ( class_exists( 'Image_Watermark' ) && method_exists( 'Image_Watermark', 'instance' ) ) {
			$instance = call_user_func( array( 'Image_Watermark', 'instance' ) );
			if ( is_object( $instance ) ) {
				return $instance;
			}
		}

		if ( isset( $GLOBALS['image_watermark'] ) && is_object( $GLOBALS['image_watermark'] ) ) {
			return $GLOBALS['image_watermark'];
		}

		return null;
	}

	/**
	 * Получить сервис обработки изображений.
	 *
	 * @param object|null $plugin Экземпляр плагина.
	 * @return object|null
	 */
	private function get_handler( $plugin ) {
		if ( ! $plugin || ! method_exists( $plugin, 'get_upload_handler' ) ) {
			return null;
		}

		$handler = $plugin->get_upload_handler();

		return is_object( $handler ) ? $handler : null;
	}

	/**
	 * Собрать ID вложений для обработки.
	 *
	 * @param array $assoc_args Именованные аргументы.
	 * @return int[]
	 */
	private function get_attachment_ids( $assoc_args ) {
		if ( ! empty( $assoc_args['ids'] ) ) {
			$ids = array_filter( array_map( 'absint', explode( ',', (string) $assoc_args['ids'] ) ) );
			return array_values( array_unique( $ids ) );
		}

		global $wpdb;

		$include_categories = ! empty( $assoc_args['include-categories'] );

		$product_subquery = "SELECT ID FROM {$wpdb->posts}
			WHERE post_type = 'product' AND post_status NOT IN ('trash','auto-draft')";

		$ids = array();

		// Featured images товаров.
		$featured = $wpdb->get_col(
			"SELECT meta_value FROM {$wpdb->postmeta}
			WHERE meta_key = '_thumbnail_id' AND post_id IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL без пользовательских данных.

		// Галереи товаров (CSV).
		$galleries = $wpdb->get_col(
			"SELECT meta_value FROM {$wpdb->postmeta}
			WHERE meta_key = '_product_image_gallery' AND post_id IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL без пользовательских данных.

		$gallery_ids = array();
		foreach ( $galleries as $gallery ) {
			foreach ( explode( ',', (string) $gallery ) as $gallery_id ) {
				$gallery_ids[] = absint( $gallery_id );
			}
		}

		// Вложения, привязанные к товарам.
		$attached = $wpdb->get_col(
			"SELECT ID FROM {$wpdb->posts}
			WHERE post_type = 'attachment' AND post_parent IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL без пользовательских данных.

		$ids = array_merge( $ids, $featured, $gallery_ids, $attached );

		// Изображения категорий товаров.
		if ( $include_categories ) {
			$term_thumbs = $wpdb->get_col(
				"SELECT tm.meta_value FROM {$wpdb->termmeta} tm
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = tm.term_id
				WHERE tm.meta_key = 'thumbnail_id' AND tt.taxonomy = 'product_cat'"
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL без пользовательских данных.

			$ids = array_merge( $ids, $term_thumbs );
		}

		$ids = array_filter( array_map( 'absint', $ids ) );
		$ids = array_values( array_unique( $ids ) );

		return $ids;
	}
}

$belgranit_watermark_cli = new Belgranit_Watermark_CLI();
WP_CLI::add_command( 'belgranit watermark-products', array( $belgranit_watermark_cli, 'products' ) );
WP_CLI::add_command( 'belgranit watermark-doctor', array( $belgranit_watermark_cli, 'doctor' ) );
