<?php
/**
 * Админ-инструмент: массовое наложение вотермарки на изображения товаров.
 *
 * Для хостингов без WP-CLI (доступ только по FTP). Работает пакетами через AJAX,
 * используя публичный API плагина Image Watermark (dFactory).
 *
 * Инструменты → Вотермарки товаров.
 *
 * @package BelGranit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Страница и AJAX-обработчик пакетного наложения вотермарки.
 */
class Belgranit_Watermark_Admin {

	const CAP         = 'manage_options';
	const PAGE        = 'belgranit-watermarks';
	const AJAX_ACTION = 'belgranit_watermark_batch';
	const BATCH       = 5;

	/**
	 * Размеры изображений, которые реально выводит тема.
	 *
	 * @return string[]
	 */
	public static function force_sizes() {
		return array(
			'full',
			'large',
			'medium',
			'medium_large',
			'thumbnail',
			'woocommerce_single',
			'woocommerce_thumbnail',
			'woocommerce_gallery_thumbnail',
		);
	}

	/**
	 * Конструктор.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( $this, 'ajax' ) );
	}

	/**
	 * Пункт меню в «Инструменты».
	 *
	 * @return void
	 */
	public function menu() {
		add_management_page(
			'Вотермарки товаров',
			'Вотермарки товаров',
			self::CAP,
			self::PAGE,
			array( $this, 'render' )
		);
	}

	/**
	 * Экземпляр плагина Image Watermark (устойчиво к версиям).
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
	 * Сервис обработки изображений.
	 *
	 * @param object|null $plugin Плагин.
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
	 * Собрать ID изображений товаров.
	 *
	 * @param bool $include_categories Включать изображения категорий товаров.
	 * @return int[]
	 */
	private function get_ids( $include_categories = false ) {
		global $wpdb;

		$product_subquery = "SELECT ID FROM {$wpdb->posts}
			WHERE post_type = 'product' AND post_status NOT IN ('trash','auto-draft')";

		$ids = array();

		$featured = $wpdb->get_col(
			"SELECT meta_value FROM {$wpdb->postmeta}
			WHERE meta_key = '_thumbnail_id' AND post_id IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL.

		$galleries = $wpdb->get_col(
			"SELECT meta_value FROM {$wpdb->postmeta}
			WHERE meta_key = '_product_image_gallery' AND post_id IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL.

		$gallery_ids = array();
		foreach ( $galleries as $gallery ) {
			foreach ( explode( ',', (string) $gallery ) as $gallery_id ) {
				$gallery_ids[] = absint( $gallery_id );
			}
		}

		$attached = $wpdb->get_col(
			"SELECT ID FROM {$wpdb->posts}
			WHERE post_type = 'attachment' AND post_parent IN ( {$product_subquery} )"
		); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL.

		$ids = array_merge( $ids, $featured, $gallery_ids, $attached );

		if ( $include_categories ) {
			$term_thumbs = $wpdb->get_col(
				"SELECT tm.meta_value FROM {$wpdb->termmeta} tm
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = tm.term_id
				WHERE tm.meta_key = 'thumbnail_id' AND tt.taxonomy = 'product_cat'"
			); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- статичный SQL.

			$ids = array_merge( $ids, $term_thumbs );
		}

		$ids = array_filter( array_map( 'absint', $ids ) );
		$ids = array_values( array_unique( $ids ) );
		sort( $ids );

		return $ids;
	}

	/**
	 * Отрисовка страницы инструмента.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( self::CAP ) ) {
			wp_die( 'Недостаточно прав.' );
		}

		$plugin  = $this->get_plugin();
		$handler = $this->get_handler( $plugin );
		$ready   = ( $plugin && $handler && method_exists( $plugin, 'get_extension' ) && $plugin->get_extension() );
		$total   = $ready ? count( $this->get_ids( false ) ) : 0;

		$nonce = wp_create_nonce( self::AJAX_ACTION );
		$url   = admin_url( 'admin-ajax.php' );
		?>
		<div class="wrap">
			<h1>Вотермарки товаров</h1>

			<?php if ( ! $plugin ) : ?>
				<div class="notice notice-error"><p>Плагин <strong>Image Watermark</strong> не активен. Активируйте его и настройте вотермарку.</p></div>
			<?php elseif ( ! $handler ) : ?>
				<div class="notice notice-error"><p>Версия плагина Image Watermark несовместима (нет <code>get_upload_handler()</code>). Обновите плагин до 2.0.x.</p></div>
			<?php else : ?>
				<?php
				$opts        = isset( $plugin->options ) && is_array( $plugin->options ) ? $plugin->options : array();
				$manual      = ! empty( $opts['watermark_image']['manual_watermarking'] );
				$backup      = ! empty( $opts['backup']['backup_image'] );
				$sizes_on    = isset( $opts['watermark_on'] ) && is_array( $opts['watermark_on'] ) ? array_keys( array_filter( $opts['watermark_on'] ) ) : array();
				?>
				<table class="widefat striped" style="max-width:720px;margin-bottom:16px">
					<tbody>
						<tr><td>Движок</td><td><?php echo esc_html( method_exists( $plugin, 'get_extension' ) && $plugin->get_extension() ? $plugin->get_extension() : 'нет' ); ?></td></tr>
						<tr><td>Manual watermarking</td><td><?php echo $manual ? '<span style="color:green">вкл</span>' : '<span style="color:#b32d2e">ВЫКЛ — включите в настройках плагина</span>'; ?></td></tr>
						<tr><td>Backup</td><td><?php echo $backup ? '<span style="color:green">вкл</span>' : '<span style="color:#b32d2e">ВЫКЛ — с 2.0.13 применение требует бэкапа</span>'; ?></td></tr>
						<tr><td>Размеры в плагине</td><td><?php echo $sizes_on ? esc_html( implode( ', ', $sizes_on ) ) : '<span style="color:#b32d2e">не выбраны (инструмент переопределит принудительно)</span>'; ?></td></tr>
						<tr><td>Изображений товаров найдено</td><td><?php echo (int) $total; ?></td></tr>
					</tbody>
				</table>

				<p>
					<label><input type="checkbox" id="bw-force"> Повторно обрабатывать уже помеченные</label><br>
					<label><input type="checkbox" id="bw-cats"> Включить изображения категорий товаров</label>
				</p>

				<p>
					<button class="button button-primary" id="bw-run" <?php disabled( ! $ready ); ?>>Наложить вотермарку</button>
					<button class="button" id="bw-dry" <?php disabled( ! $ready ); ?>>Только проверить</button>
				</p>

				<div id="bw-progress" style="max-width:720px;height:18px;background:#f0f0f1;border-radius:9px;overflow:hidden;display:none;margin:10px 0">
					<div id="bw-bar" style="height:100%;width:0;background:#2271b1;transition:width .2s"></div>
				</div>
				<div id="bw-status" style="margin:8px 0;font-weight:600"></div>
				<pre id="bw-log" style="max-width:720px;max-height:420px;overflow:auto;background:#1d2327;color:#c3c4c7;padding:12px;border-radius:6px;font-size:12px;line-height:1.5"></pre>

				<script>
				(function () {
					var ajaxUrl = <?php echo wp_json_encode( $url ); ?>;
					var nonce   = <?php echo wp_json_encode( $nonce ); ?>;
					var total   = <?php echo (int) $total; ?>;

					var logEl   = document.getElementById('bw-log');
					var statusEl= document.getElementById('bw-status');
					var wrap    = document.getElementById('bw-progress');
					var bar     = document.getElementById('bw-bar');

					function log(msg, color) {
						var line = document.createElement('div');
						if (color) line.style.color = color;
						line.textContent = msg;
						logEl.appendChild(line);
						logEl.scrollTop = logEl.scrollHeight;
					}

					async function run(dry) {
						var lastId = 0, done = false, processed = 0, ok = 0, skip = 0, fail = 0;
						var force = document.getElementById('bw-force').checked ? 1 : 0;
						var cats  = document.getElementById('bw-cats').checked ? 1 : 0;

						document.getElementById('bw-run').disabled = true;
						document.getElementById('bw-dry').disabled = true;
						logEl.innerHTML = '';
						wrap.style.display = 'block';
						statusEl.textContent = (dry ? 'Проверка' : 'Наложение') + '… 0 / ' + total;

						try {
							while (!done) {
								var body = new URLSearchParams({
									action: '<?php echo esc_js( self::AJAX_ACTION ); ?>',
									nonce: nonce,
									force: force,
									cats: cats,
									dry: dry ? 1 : 0,
									last_id: lastId
								});

								var res  = await fetch(ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body });
								var data = await res.json();

								if (!data || !data.success) {
									log('Ошибка: ' + ((data && data.data && data.data.message) || 'неизвестная'), '#ff6b6b');
									break;
								}

								var d = data.data;
								total = d.total;
								d.items.forEach(function (it) {
									processed++;
									if (it.status === 'ok')      { ok++;   log('✓ #' + it.id + ' — готово' + (it.message ? ' [' + it.message + ']' : ''), '#8fd694'); }
									else if (it.status === 'skip') { skip++; log('• #' + it.id + ' — ' + (it.message || 'пропуск'), '#c3c4c7'); }
									else                          { fail++; log('✗ #' + it.id + ' — ошибка: ' + (it.message || ''), '#ff6b6b'); }
								});

								lastId = d.last_id;
								done   = d.done;

								var pct = total ? Math.min(100, Math.round(processed / total * 100)) : 100;
								bar.style.width = pct + '%';
								statusEl.textContent = (dry ? 'Проверка' : 'Наложение') + '… ' + processed + ' / ' + total;
							}
						} catch (e) {
							log('Сбой запроса: ' + e, '#ff6b6b');
						}

						statusEl.textContent = (dry ? 'Проверка завершена' : 'Готово') + ': успешно ' + ok + ', пропущено ' + skip + ', ошибок ' + fail + '.';
						document.getElementById('bw-run').disabled = false;
						document.getElementById('bw-dry').disabled = false;
					}

					document.getElementById('bw-run').addEventListener('click', function () { run(false); });
					document.getElementById('bw-dry').addEventListener('click', function () { run(true); });
				})();
				</script>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * AJAX: обработка одного пакета.
	 *
	 * @return void
	 */
	public function ajax() {
		check_ajax_referer( self::AJAX_ACTION, 'nonce' );

		if ( ! current_user_can( self::CAP ) ) {
			wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
		}

		$plugin  = $this->get_plugin();
		$handler = $this->get_handler( $plugin );

		if ( ! $plugin || ! $handler || ! method_exists( $plugin, 'get_extension' ) || ! $plugin->get_extension() ) {
			wp_send_json_error( array( 'message' => 'Плагин Image Watermark недоступен или нет движка обработки.' ) );
		}

		$dry   = ! empty( $_POST['dry'] );
		$force = ! empty( $_POST['force'] );
		$cats  = ! empty( $_POST['cats'] );
		$last  = isset( $_POST['last_id'] ) ? absint( $_POST['last_id'] ) : 0;

		$all = $this->get_ids( $cats );

		$start = 0;
		if ( $last ) {
			while ( $start < count( $all ) && $all[ $start ] <= $last ) {
				$start++;
			}
		}

		$chunk = array_slice( $all, $start, self::BATCH );
		$total = count( $all );

		// Принудительно включаем все размеры, которые выводит тема.
		$size_filter = function ( $options ) {
			if ( empty( $options['watermark_on'] ) || ! is_array( $options['watermark_on'] ) ) {
				$options['watermark_on'] = array();
			}
			foreach ( self::force_sizes() as $size ) {
				$options['watermark_on'][ $size ] = 1;
			}
			return $options;
		};
		add_filter( 'iw_watermark_options', $size_filter, 99 );

		$allowed_mime    = $plugin->get_allowed_mime_types();
		$watermarked_key = $plugin->get_watermarked_meta_key();

		$items = array();

		foreach ( $chunk as $id ) {
			$mime = get_post_mime_type( $id );
			if ( ! in_array( $mime, $allowed_mime, true ) ) {
				$items[] = array( 'id' => $id, 'status' => 'skip', 'message' => 'формат ' . ( $mime ? $mime : 'unknown' ) );
				continue;
			}

			if ( ! $force && get_post_meta( $id, $watermarked_key, true ) ) {
				$items[] = array( 'id' => $id, 'status' => 'skip', 'message' => 'уже помечено' );
				continue;
			}

			$eligibility = $handler->describe_operation_eligibility( $id, 'manual-apply' );
			if ( empty( $eligibility['valid'] ) ) {
				$code = isset( $eligibility['code'] ) ? $eligibility['code'] : '';
				if ( 'manual_disabled' === $code ) {
					remove_filter( 'iw_watermark_options', $size_filter, 99 );
					wp_send_json_error( array( 'message' => 'В настройках плагина выключено «Manual watermarking».' ) );
				}
				$items[] = array( 'id' => $id, 'status' => 'skip', 'message' => isset( $eligibility['error'] ) ? $eligibility['error'] : 'не прошло проверку' );
				continue;
			}

			if ( $dry ) {
				$items[] = array( 'id' => $id, 'status' => 'ok', 'message' => 'будет обработано' );
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
				$items[] = array( 'id' => $id, 'status' => 'error', 'message' => $error );
			} else {
				$sizes = ! empty( $outcome['sizes'] ) ? implode( ', ', (array) $outcome['sizes'] ) : '';
				$items[] = array( 'id' => $id, 'status' => 'ok', 'message' => $sizes );
			}
		}

		remove_filter( 'iw_watermark_options', $size_filter, 99 );

		$new_last = ! empty( $chunk ) ? (int) end( $chunk ) : $last;

		wp_send_json_success( array(
			'total'   => $total,
			'last_id' => $new_last,
			'done'    => ( $start + count( $chunk ) ) >= $total,
			'items'   => $items,
		) );
	}
}

new Belgranit_Watermark_Admin();
