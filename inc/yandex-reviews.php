<?php
/**
 * Отзывы Яндекса: загрузка, кэш и нормализация данных для секции «Отзывы».
 *
 * @package BelGranit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BELGRANIT_YANDEX_REVIEWS_URL' ) ) {
	/** Страница отзывов организации в Яндекс Картах (источник отзывов). */
	define( 'BELGRANIT_YANDEX_REVIEWS_URL', 'https://yandex.by/maps/org/belgranit/194264489305/reviews/' );
}

if ( ! defined( 'BELGRANIT_YANDEX_PROFILE_URL' ) ) {
	/** Публичный профиль компании в Яндексе (запасная ссылка для кнопки). */
	define( 'BELGRANIT_YANDEX_PROFILE_URL', 'https://yandex.by/profile/194264489305' );
}

if ( ! defined( 'BELGRANIT_YANDEX_REVIEWS_TTL' ) ) {
	/** Срок жизни кэша с отзывами. */
	define( 'BELGRANIT_YANDEX_REVIEWS_TTL', 6 * HOUR_IN_SECONDS );
}

if ( ! defined( 'BELGRANIT_YANDEX_REVIEWS_RETRY_TTL' ) ) {
	/** Срок жизни кэша, если Яндекс не ответил (чтобы не долбить его при каждой загрузке). */
	define( 'BELGRANIT_YANDEX_REVIEWS_RETRY_TTL', 10 * MINUTE_IN_SECONDS );
}

/**
 * Последние отзывы Яндекса (отсортированы от новых к старым).
 *
 * @param int $limit Сколько отзывов вернуть.
 * @return array Массив отзывов: name, level, avatar, rating, text, timestamp, date.
 */
function belgranit_get_yandex_reviews( $limit = 9 ) {
	$limit = max( 1, (int) $limit );

	$data = get_transient( 'belgranit_yandex_reviews' );

	if ( ! is_array( $data ) || ! isset( $data['items'] ) || ! is_array( $data['items'] ) ) {
		$data = array( 'items' => belgranit_refresh_yandex_reviews() );
	}

	return array_slice( $data['items'], 0, $limit );
}

/**
 * Средняя оценка компании со страницы Яндекса (например «5,0» в числовом виде — 5.0).
 *
 * @return float|null Оценка либо null, если её получить не удалось.
 */
function belgranit_get_yandex_rating() {
	$data = get_transient( 'belgranit_yandex_reviews' );

	// Старый формат кэша без оценки — обновляем один раз.
	if ( ! is_array( $data ) || ! array_key_exists( 'rating', $data ) ) {
		belgranit_refresh_yandex_reviews();
		$data = get_transient( 'belgranit_yandex_reviews' );
	}

	if ( ! is_array( $data ) || ! isset( $data['rating'] ) ) {
		return null;
	}

	return $data['rating'];
}

/**
 * Оценка в формате, как её показывает Яндекс: «5,0», «4,8».
 *
 * @param float|null $rating Оценка.
 * @return string Строка вида «5,0» либо пустая строка.
 */
function belgranit_format_yandex_rating( $rating ) {
	if ( null === $rating || ! is_numeric( $rating ) ) {
		return '';
	}

	return str_replace( '.', ',', number_format( (float) $rating, 1, '.', '' ) );
}

/**
 * Загрузить отзывы заново и положить их в транзиент.
 *
 * @return array Нормализованные отзывы (пустой массив при ошибке).
 */
function belgranit_refresh_yandex_reviews() {
	$data   = belgranit_fetch_yandex_reviews();
	$raw    = is_array( $data ) ? $data['reviews'] : null;
	$rating = is_array( $data ) ? $data['rating'] : null;
	$items  = array();

	if ( is_array( $raw ) ) {
		foreach ( $raw as $review ) {
			$item = belgranit_normalize_yandex_review( $review );

			if ( $item ) {
				$items[] = $item;
			}
		}

		usort( $items, 'belgranit_sort_reviews_by_date' );
	}

	set_transient(
		'belgranit_yandex_reviews',
		array(
			'items'  => $items,
			'rating' => $rating,
			'error'  => ! is_array( $raw ),
		),
		$items ? BELGRANIT_YANDEX_REVIEWS_TTL : BELGRANIT_YANDEX_REVIEWS_RETRY_TTL
	);

	return $items;
}

/**
 * Периодическое обновление кэша, чтобы запрос к Яндексу не выполнялся при загрузке страницы.
 */
function belgranit_schedule_yandex_reviews_refresh() {
	if ( ! wp_next_scheduled( 'belgranit_yandex_reviews_refresh' ) ) {
		wp_schedule_event( time() + 5 * MINUTE_IN_SECONDS, 'hourly', 'belgranit_yandex_reviews_refresh' );
	}
}
add_action( 'init', 'belgranit_schedule_yandex_reviews_refresh' );
add_action( 'belgranit_yandex_reviews_refresh', 'belgranit_refresh_yandex_reviews' );

/**
 * Загрузка страницы отзывов Яндекса и разбор встроенного JSON.
 *
 * @return array|null Массив с ключами reviews/rating либо null, если данные получить не удалось.
 */
function belgranit_fetch_yandex_reviews() {
	$response = wp_remote_get(
		BELGRANIT_YANDEX_REVIEWS_URL,
		array(
			'timeout'     => 6,
			'redirection' => 5,
			'user-agent'  => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
			'headers'     => array(
				'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				'Accept-Language' => 'ru-RU,ru;q=0.9,en;q=0.8',
			),
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return null;
	}

	$body  = wp_remote_retrieve_body( $response );
	$state = belgranit_parse_yandex_state( $body );

	return array(
		'reviews' => belgranit_find_reviews_in_state( $state ),
		'rating'  => belgranit_parse_yandex_rating( $body, $state ),
	);
}

/**
 * Средняя оценка организации со страницы Яндекса.
 *
 * Сначала берём значение из текста страницы («Рейтинг 5,0 на основе 47 оценок…»),
 * затем из бейджа организации и в конце из JSON-состояния страницы.
 *
 * @param string $html  HTML страницы.
 * @param array  $state Декодированное состояние страницы.
 * @return float|null Оценка либо null.
 */
function belgranit_parse_yandex_rating( $html, $state ) {
	if ( is_string( $html ) ) {
		if ( preg_match( '/Рейтинг\s+([0-9]+(?:[.,][0-9]+)?)/u', $html, $matches ) ) {
			return (float) str_replace( ',', '.', $matches[1] );
		}

		if ( preg_match( '/business-rating-badge-view__rating-text">\s*([0-9]+(?:[.,][0-9]+)?)\s*</u', $html, $matches ) ) {
			return (float) str_replace( ',', '.', $matches[1] );
		}
	}

	$rating_data = belgranit_find_rating_data_in_state( $state );

	if ( isset( $rating_data['ratingValue'] ) && is_numeric( $rating_data['ratingValue'] ) ) {
		return (float) $rating_data['ratingValue'];
	}

	return null;
}

/**
 * Найти в состоянии страницы ratingData организации.
 *
 * @param mixed $state Узел состояния.
 * @param int   $depth Текущая глубина обхода.
 * @return array|null ratingData либо null.
 */
function belgranit_find_rating_data_in_state( $state, $depth = 0 ) {
	if ( ! is_array( $state ) || $depth > 8 ) {
		return null;
	}

	if ( isset( $state['ratingData'] ) && is_array( $state['ratingData'] ) && isset( $state['ratingData']['ratingValue'] ) ) {
		return $state['ratingData'];
	}

	foreach ( $state as $child ) {
		if ( ! is_array( $child ) ) {
			continue;
		}

		$found = belgranit_find_rating_data_in_state( $child, $depth + 1 );

		if ( null !== $found ) {
			return $found;
		}
	}

	return null;
}

/**
 * Извлечь JSON-состояние страницы из тега <script class="state-view">.
 *
 * @param string $html HTML страницы.
 * @return array|null Декодированное состояние либо null.
 */
function belgranit_parse_yandex_state( $html ) {
	if ( ! is_string( $html ) || ! preg_match( '#<script[^>]*class=["\'][^"\']*state-view[^"\']*["\'][^>]*>(.*?)</script>#is', $html, $matches ) ) {
		return null;
	}

	$state = json_decode( $matches[1], true );

	return is_array( $state ) ? $state : null;
}

/**
 * Найти в состоянии страницы блок с отзывами (stack[…].results.items[…].reviewResults).
 *
 * @param mixed $state  Узел состояния.
 * @param int   $depth  Текущая глубина обхода.
 * @return array|null Список отзывов либо null.
 */
function belgranit_find_reviews_in_state( $state, $depth = 0 ) {
	if ( ! is_array( $state ) || $depth > 8 ) {
		return null;
	}

	if ( isset( $state['reviewResults']['reviews'] ) && is_array( $state['reviewResults']['reviews'] ) ) {
		return $state['reviewResults']['reviews'];
	}

	foreach ( $state as $child ) {
		if ( ! is_array( $child ) ) {
			continue;
		}

		$found = belgranit_find_reviews_in_state( $child, $depth + 1 );

		if ( null !== $found ) {
			return $found;
		}
	}

	return null;
}

/**
 * Привести отзыв Яндекса к виду, удобному для шаблона.
 *
 * @param array $review Сырой отзыв.
 * @return array|null Нормализованный отзыв либо null, если отзыв пустой.
 */
function belgranit_normalize_yandex_review( $review ) {
	if ( ! is_array( $review ) ) {
		return null;
	}

	$text = isset( $review['text'] ) ? trim( wp_strip_all_tags( (string) $review['text'] ) ) : '';

	if ( '' === $text ) {
		return null;
	}

	$author = ( isset( $review['author'] ) && is_array( $review['author'] ) ) ? $review['author'] : array();

	$name = isset( $author['name'] ) ? trim( wp_strip_all_tags( (string) $author['name'] ) ) : '';

	if ( '' === $name ) {
		$name = 'Клиент';
	}

	$avatar = '';

	if ( ! empty( $author['avatarUrl'] ) ) {
		$avatar = str_replace( '{size}', 'small', (string) $author['avatarUrl'] );
	}

	$level = '';

	if ( ! empty( $author['professionLevel'] ) ) {
		$level = trim( wp_strip_all_tags( (string) $author['professionLevel'] ) );
	}

	$rating = isset( $review['rating'] ) ? (int) $review['rating'] : 5;

	$timestamp = 0;

	if ( ! empty( $review['updatedTime'] ) ) {
		$timestamp = strtotime( (string) $review['updatedTime'] );
	}

	if ( ! $timestamp ) {
		$timestamp = time();
	}

	return array(
		'id'        => isset( $review['reviewId'] ) ? (string) $review['reviewId'] : '',
		'name'      => $name,
		'level'     => $level,
		'avatar'    => $avatar,
		'rating'    => max( 1, min( 5, $rating ) ),
		'text'      => $text,
		'timestamp' => $timestamp,
		'date'      => belgranit_format_review_date( $timestamp ),
	);
}

/**
 * Сравнение отзывов для сортировки от новых к старым.
 *
 * @param array $a Первый отзыв.
 * @param array $b Второй отзыв.
 * @return int
 */
function belgranit_sort_reviews_by_date( $a, $b ) {
	return $b['timestamp'] - $a['timestamp'];
}

/**
 * Дата отзыва вида «15 апреля 2026».
 *
 * @param int $timestamp UNIX-время.
 * @return string
 */
function belgranit_format_review_date( $timestamp ) {
	$months = array(
		'января',
		'февраля',
		'марта',
		'апреля',
		'мая',
		'июня',
		'июля',
		'августа',
		'сентября',
		'октября',
		'ноября',
		'декабря',
	);

	$month_index = (int) date( 'n', $timestamp ) - 1;

	return (int) date( 'j', $timestamp ) . ' ' . $months[ $month_index ] . ' ' . date( 'Y', $timestamp );
}

/**
 * Разметка звёзд рейтинга.
 *
 * @param int $rating Оценка от 1 до 5.
 * @return string HTML.
 */
function belgranit_review_stars( $rating ) {
	$rating = max( 1, min( 5, (int) $rating ) );

	$html = '<span class="reviews-stars" role="img" aria-label="Оценка ' . $rating . ' из 5">';

	for ( $i = 1; $i <= 5; $i++ ) {
		$html .= '<svg class="reviews-stars__icon' . ( $i <= $rating ? ' is-active' : '' ) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
	}

	return $html . '</span>';
}

/**
 * Инициал для аватара без картинки.
 *
 * @param string $name Имя автора.
 * @return string
 */
function belgranit_review_initial( $name ) {
	$name = trim( (string) $name );

	if ( '' === $name ) {
		return 'К';
	}

	if ( function_exists( 'mb_substr' ) ) {
		return mb_strtoupper( mb_substr( $name, 0, 1, 'UTF-8' ) );
	}

	return strtoupper( substr( $name, 0, 1 ) );
}
