<?php
/**
 * BelGranit functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BelGranit
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function belgranit_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on BelGranit, use a find and replace
		* to change 'belgranit' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'belgranit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'belgranit' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'belgranit_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom_logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// WooCommerce support
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 400,
		'gallery_thumbnail_image_width' => 200,
		'single_image_width' => 600,
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'belgranit_setup' );

/**
 * Products per page in catalog
 */
add_filter( 'loop_shop_per_page', function() {
	return 18;
});

/**
 * Set products per page via pre_get_posts
 */
add_action( 'pre_get_posts', function( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$q->set( 'posts_per_page', 18 );
	}
});

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function belgranit_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'belgranit_content_width', 640 );
}
add_action( 'after_setup_theme', 'belgranit_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function belgranit_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'belgranit' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'belgranit' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
	) );
}

// Thank You Page Options
add_action( 'init', 'belgranit_thank_you_fields' );
function belgranit_thank_you_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_thank_you_options',
		'title'    => 'Страница "Спасибо"',
		'fields'   => array(
			array(
				'key'          => 'field_thank_you_bg_desktop',
				'label'        => 'Фоновое изображение (десктоп)',
				'name'         => 'thank_you_bg_desktop',
				'type'         => 'image',
				'return_format' => 'url',
			),
			array(
				'key'          => 'field_thank_you_bg_mobile',
				'label'        => 'Фоновое изображение (мобайл)',
				'name'         => 'thank_you_bg_mobile',
				'type'         => 'image',
				'return_format' => 'url',
			),
			array(
				'key'          => 'field_thank_you_heading',
				'label'        => 'Заголовок',
				'name'         => 'thank_you_heading',
				'type'         => 'text',
				'default_value' => 'Спасибо, мы получили вашу заявку!',
			),
			array(
				'key'          => 'field_thank_you_divider',
				'label'        => 'Декоративный элемент',
				'name'         => 'thank_you_divider',
				'type'         => 'image',
				'return_format' => 'url',
			),
			array(
				'key'          => 'field_thank_you_text',
				'label'        => 'Описание',
				'name'         => 'thank_you_text',
				'type'         => 'text',
				'default_value' => 'Мы свяжемся с вами в ближайшее время.',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'thank-you.php',
				),
			),
		),
	) );
}

// Form submission handler
add_action( 'wp_ajax_belgranit_form_submit', 'belgranit_form_submit' );
add_action( 'wp_ajax_nopriv_belgranit_form_submit', 'belgranit_form_submit' );
function belgranit_form_submit() {
	if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'belgranit_form_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Ошибка безопасности' ) );
	}

	$name    = sanitize_text_field( $_POST['name'] ?? '' );
	$phone   = sanitize_text_field( $_POST['phone'] ?? '' );
	$comment = sanitize_textarea_field( $_POST['comment'] ?? '' );
	$form    = sanitize_text_field( $_POST['form_type'] ?? '' );

	if ( empty( $name ) || empty( $phone ) ) {
		wp_send_json_error( array( 'message' => 'Заполните обязательные поля' ) );
	}

	$admin_email = get_option( 'admin_email' );
	$site_name   = get_bloginfo( 'name' );
	$form_label  = $form === 'consult' ? 'Консультация' : 'Заказ звонка';

	$subject = "Новая заявка \"{$form_label}\" с сайта {$site_name}";

	$message  = "Имя: {$name}\n";
	$message .= "Телефон: {$phone}\n";
	if ( $comment ) {
		$message .= "Комментарий: {$comment}\n";
	}

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		"From: {$site_name} <{$admin_email}>",
	);

	$sent = wp_mail( $admin_email, $subject, $message, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => 'Заявка отправлена' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Ошибка отправки. Попробуйте позже.' ) );
	}
}

add_action( 'widgets_init', 'belgranit_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function belgranit_scripts() {
	wp_enqueue_style( 'belgranit-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'belgranit-style', 'rtl', 'replace' );

	wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
	wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );

	wp_enqueue_script( 'belgranit-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'belgranit-navigation', 'belgranitAjax', array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'belgranit_form_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'belgranit_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Helper: Render site logo.
 *
 * @param string $css_class Additional CSS classes.
 */
function belgranit_logo( $css_class = '' ) {
	$logo_id    = get_theme_mod( 'custom_logo' );
	$site_name  = get_bloginfo( 'name' );
	$class_attr = $css_class ? ' class="' . esc_attr( $css_class ) . '"' : '';

	if ( $logo_id ) :
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
		?>
		<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>"<?php echo $class_attr; ?>>
	<?php else : ?>
		<span<?php echo $class_attr; ?>><?php echo esc_html( $site_name ); ?></span>
	<?php endif;
}

/**
 * Helper: Format phone number for tel: link.
 *
 * @param string $phone Raw phone string.
 * @return string Clean phone for href.
 */
function belgranit_phone_link( $phone ) {
	return preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Helper: Get all contact fields from SCF Options.
 *
 * @return array
 */
function belgranit_get_contacts() {
	return array(
		'address'          => get_field( 'contact_address', 'option' ) ?: 'г. Могилев, Пушкинский проспект 18',
		'address_2'        => get_field( 'contact_address_2', 'option' ) ?: '(бывший Октябрьский универмаг)',
		'phone_1'          => get_field( 'contact_phone_1', 'option' ) ?: '+375 (29) 640-53-77',
		'phone_2'          => get_field( 'contact_phone_2', 'option' ) ?: '+375 (29) 783-75-70',
		'phone_3'          => get_field( 'contact_phone_3', 'option' ) ?: '+375 (222) 70-70-76',
		'phone_4'          => get_field( 'contact_phone_4', 'option' ) ?: '+375 (29) 222-24-39',
		'email'            => get_field( 'contact_email', 'option' ) ?: 'info@belgranit.by',
		'hours_weekday'    => get_field( 'contact_hours_weekday', 'option' ) ?: '10:00 - 19:00',
		'hours_sat'        => get_field( 'contact_hours_sat', 'option' ) ?: '10:00 - 17:00',
		'hours_sun'        => get_field( 'contact_hours_sun', 'option' ) ?: '10:00 - 16:00',
		'hours_production' => get_field( 'contact_hours_production', 'option' ) ?: '08:00 - 17:00',
		'map_address'      => get_field( 'contact_map_address', 'option' ) ?: 'Могилев, Пушкинский проспект 18',
		'map_balloon'      => get_field( 'contact_map_balloon', 'option' ) ?: 'Белгранит',
	);
}

/**
 * SCF Options Page: Контакты
 */
add_action( 'acf/init', 'belgranit_register_contact_fields' );
function belgranit_register_contact_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_contact_options',
		'title'    => 'Контактные данные',
		'fields'   => array(

			// Tab: Address
			array(
				'key'       => 'field_contact_tab_address',
				'label'     => 'Адрес',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_contact_address',
				'label'        => 'Адрес (строка 1)',
				'name'         => 'contact_address',
				'type'         => 'text',
				'default_value' => 'г. Могилев, Пушкинский проспект 18',
			),

			array(
				'key'          => 'field_contact_address_2',
				'label'        => 'Адрес (строка 2)',
				'name'         => 'contact_address_2',
				'type'         => 'text',
				'default_value' => '(бывший Октябрьский универмаг)',
			),

			// Tab: Phones
			array(
				'key'       => 'field_contact_tab_phones',
				'label'     => 'Телефоны',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_contact_phone_1',
				'label'        => 'Телефон 1',
				'name'         => 'contact_phone_1',
				'type'         => 'text',
				'default_value' => '+375 (29) 640-53-77',
			),

			array(
				'key'          => 'field_contact_phone_2',
				'label'        => 'Телефон 2',
				'name'         => 'contact_phone_2',
				'type'         => 'text',
				'default_value' => '+375 (29) 783-75-70',
			),

			array(
				'key'          => 'field_contact_phone_3',
				'label'        => 'Телефон 3',
				'name'         => 'contact_phone_3',
				'type'         => 'text',
				'default_value' => '+375 (222) 70-70-76',
			),

			array(
				'key'          => 'field_contact_phone_4',
				'label'        => 'Телефон 4 (Производство)',
				'name'         => 'contact_phone_4',
				'type'         => 'text',
				'default_value' => '+375 (29) 222-24-39',
			),

			// Tab: Email
			array(
				'key'       => 'field_contact_tab_email',
				'label'     => 'Email',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_contact_email',
				'label'        => 'Email',
				'name'         => 'contact_email',
				'type'         => 'email',
				'default_value' => 'info@belgranit.by',
			),

			// Tab: Work Hours
			array(
				'key'       => 'field_contact_tab_hours',
				'label'     => 'Режим работы',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_contact_hours_weekday',
				'label'        => 'Пн-Пт',
				'name'         => 'contact_hours_weekday',
				'type'         => 'text',
				'default_value' => '10:00 - 19:00',
			),

			array(
				'key'          => 'field_contact_hours_sat',
				'label'        => 'Сб',
				'name'         => 'contact_hours_sat',
				'type'         => 'text',
				'default_value' => '10:00 - 17:00',
			),

			array(
				'key'          => 'field_contact_hours_sun',
				'label'        => 'Вс',
				'name'         => 'contact_hours_sun',
				'type'         => 'text',
				'default_value' => '10:00 - 16:00',
			),

			array(
				'key'          => 'field_contact_hours_production',
				'label'        => 'Производство (Пн-Пт)',
				'name'         => 'contact_hours_production',
				'type'         => 'text',
				'default_value' => '08:00 - 17:00',
			),

			// Tab: Map
			array(
				'key'       => 'field_contact_tab_map',
				'label'     => 'Карта',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_contact_map_address',
				'label'        => 'Адрес для карты',
				'name'         => 'contact_map_address',
				'type'         => 'text',
				'default_value' => 'Могилев, Пушкинский проспект 18',
				'instructions' => 'Полный адрес для поиска на Яндекс Картах. Изменение этого поля автоматически перемещает метку на карте.',
			),

			array(
				'key'          => 'field_contact_map_balloon',
				'label'        => 'Текст на метке',
				'name'         => 'contact_map_balloon',
				'type'         => 'text',
				'default_value' => 'Белгранит',
			),

		),

		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * Pluralize Russian words based on count
 */
function belgranit_pluralize( $count, $one, $few, $many ) {
	$abs    = abs( $count ) % 100;
	$last   = $abs % 10;
	$result = $many;
	if ( $abs > 10 && $abs < 20 ) {
		$result = $many;
	} elseif ( $last === 1 ) {
		$result = $one;
	} elseif ( $last >= 2 && $last <= 4 ) {
		$result = $few;
	}
	return $result;
}

/**
 * Register SCF Options Page under Settings
 */
add_action( 'admin_menu', 'belgranit_add_contact_options_page' );
function belgranit_add_contact_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' => 'Контакты',
			'menu_title' => 'Контакты',
			'menu_slug'  => 'belgranit-contacts',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'icon_url'   => 'dashicons-location',
			'position'   => 30,
		) );

		acf_add_options_page( array(
			'page_title' => 'Услуги товаров',
			'menu_title' => 'Услуги товаров',
			'menu_slug'  => 'belgranit-product-services',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'icon_id'    => 'dashicons-cart',
			'position'   => 31,
		) );
	}
}

/**
 * Remove /product-category/ base from product category URLs
 */
add_action( 'init', function() {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}
	foreach ( $terms as $term ) {
		$slug = $term->slug;
		add_rewrite_rule(
			'^' . $slug . '/?$',
			'index.php?product_cat=' . $slug,
			'top'
		);
		add_rewrite_rule(
			'^' . $slug . '/page/([0-9]{1,})/?$',
			'index.php?product_cat=' . $slug . '&paged=$matches[1]',
			'top'
		);
	}
} );

add_filter( 'term_link', function( $url, $term ) {
	if ( $term instanceof WP_Term && 'product_cat' === $term->taxonomy ) {
		$url = home_url( '/' . $term->slug . '/' );
	}
	return $url;
}, 10, 2 );

/**
 * Fix menu item URLs for product categories and pages
 */
add_filter( 'wp_nav_menu_objects', function( $items ) {
	$page_fixes = array(
		'Благоустройство' => 'improvement',
	);

	$product_cat_fixes = array(
		'Ограды'    => 'ogradi',
		'Памятники' => 'pamyatniki',
		'Оформление' => 'oformlenie',
	);

	$current_url = trailingslashit( $_SERVER['REQUEST_URI'] ?? '' );

	foreach ( $items as $item ) {
		if ( isset( $page_fixes[ $item->title ] ) ) {
			$page = get_page_by_path( $page_fixes[ $item->title ] );
			if ( $page ) {
				$item->url = get_permalink( $page );
			}
		} elseif ( isset( $product_cat_fixes[ $item->title ] ) ) {
			$term = get_term_by( 'slug', $product_cat_fixes[ $item->title ], 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				$item->url = get_term_link( $term );
			}
		}

		$item_url = trailingslashit( wp_parse_url( $item->url, PHP_URL_PATH ) ?: '/' );
		if ( $item_url === $current_url ) {
			$item->classes[] = 'current-menu-item';
		}
	}

	return $items;
});

/**
 * SCF Fields: Catalog Hero (Product Category)
 */
add_action( 'acf/init', 'belgranit_register_catalog_hero_fields' );
function belgranit_register_catalog_hero_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'monuments' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_catalog_hero',
		'title'    => 'Настройки каталога',
		'fields'   => array(
			array(
				'key'          => 'field_catalog_hero_title',
				'label'        => 'Заголовок баннера',
				'name'         => 'catalog_hero_title',
				'type'         => 'text',
				'placeholder'  => 'Оставьте пустым для стандартного заголовка',
			),
			array(
				'key'          => 'field_catalog_hero_image',
				'label'        => 'Фоновое изображение',
				'name'         => 'catalog_hero_image',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
			),
			array(
				'key'       => 'field_catalog_content_tab',
				'label'     => 'Контент под товарами',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),
			array(
				'key'          => 'field_catalog_content_blocks',
				'label'        => 'Блоки контента',
				'name'         => 'catalog_content_blocks',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить блок',
				'min'          => 1,
				'sub_fields'   => array(
					array(
						'key'          => 'field_catalog_block_heading',
						'label'        => 'Заголовок',
						'name'         => 'catalog_block_heading',
						'type'         => 'text',
						'placeholder'  => 'Заголовок секции',
					),
					array(
						'key'          => 'field_catalog_block_text',
						'label'        => 'Текст',
						'name'         => 'catalog_block_text',
						'type'         => 'textarea',
						'rows'         => 6,
						'placeholder'  => 'Текст секции',
					),
				),
			),
			array(
				'key'       => 'field_catalog_consult_tab',
				'label'     => 'Остались вопросы?',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),
			array(
				'key'          => 'field_catalog_consult_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'catalog_consult_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_catalog_consult_title',
				'label'        => 'Заголовок',
				'name'         => 'catalog_consult_title',
				'type'         => 'text',
				'default_value' => 'Остались вопросы?',
			),
			array(
				'key'          => 'field_catalog_consult_icon',
				'label'        => 'Декоративная иконка',
				'name'         => 'catalog_consult_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_catalog_consult_text',
				'label'        => 'Текст',
				'name'         => 'catalog_consult_text',
				'type'         => 'textarea',
				'default_value' => 'Оставьте заявку. Менеджер перезвонит в ближайшее время.',
				'rows'         => 3,
			),
			array(
				'key'          => 'field_catalog_consult_btn_text',
				'label'        => 'Текст кнопки',
				'name'         => 'catalog_consult_btn_text',
				'type'         => 'text',
				'default_value' => 'Получить консультацию',
			),
			array(
				'key'          => 'field_catalog_consult_btn_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'catalog_consult_btn_link',
				'type'         => 'url',
			),
			array(
				'key'          => 'field_catalog_consult_features',
				'label'        => 'Преимущества',
				'name'         => 'catalog_consult_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 4,
				'sub_fields'   => array(
					array(
						'key'          => 'field_catalog_consult_feat_icon',
						'label'        => 'Иконка',
						'name'         => 'catalog_consult_feat_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_catalog_consult_feat_title',
						'label'        => 'Заголовок',
						'name'         => 'catalog_consult_feat_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_catalog_consult_feat_desc',
						'label'        => 'Описание',
						'name'         => 'catalog_consult_feat_desc',
						'type'         => 'textarea',
						'rows'         => 2,
					),
				),
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Fences Catalog Page (Ограды) — hero & content only
 */
add_action( 'acf/init', 'belgranit_register_fences_page_fields' );
function belgranit_register_fences_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'fences' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_fences_hero',
		'title'    => 'Настройки каталога Ограды',
		'fields'   => array(
			array(
				'key'          => 'field_fences_hero_title',
				'label'        => 'Заголовок баннера',
				'name'         => 'catalog_hero_title',
				'type'         => 'text',
				'placeholder'  => 'Оставьте пустым для стандартного заголовка',
			),
			array(
				'key'          => 'field_fences_hero_image',
				'label'        => 'Фоновое изображение',
				'name'         => 'catalog_hero_image',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
			),
			array(
				'key'       => 'field_fences_content_tab',
				'label'     => 'Контент под товарами',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),
			array(
				'key'          => 'field_fences_content_blocks',
				'label'        => 'Блоки контента',
				'name'         => 'catalog_content_blocks',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить блок',
				'min'          => 1,
				'sub_fields'   => array(
					array(
						'key'          => 'field_fences_block_heading',
						'label'        => 'Заголовок',
						'name'         => 'catalog_block_heading',
						'type'         => 'text',
						'placeholder'  => 'Заголовок секции',
					),
					array(
						'key'          => 'field_fences_block_text',
						'label'        => 'Текст',
						'name'         => 'catalog_block_text',
						'type'         => 'textarea',
						'rows'         => 6,
						'placeholder'  => 'Текст секции',
					),
				),
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Decoration Catalog Page (Оформление) — hero & content only
 */
add_action( 'acf/init', 'belgranit_register_decoration_page_fields' );
function belgranit_register_decoration_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'decoration' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_decoration_hero',
		'title'    => 'Настройки каталога Оформление',
		'fields'   => array(
			array(
				'key'          => 'field_decoration_hero_title',
				'label'        => 'Заголовок баннера',
				'name'         => 'catalog_hero_title',
				'type'         => 'text',
				'placeholder'  => 'Оставьте пустым для стандартного заголовка',
			),
			array(
				'key'          => 'field_decoration_hero_image',
				'label'        => 'Фоновое изображение',
				'name'         => 'catalog_hero_image',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
			),
			array(
				'key'       => 'field_decoration_content_tab',
				'label'     => 'Контент под товарами',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),
			array(
				'key'          => 'field_decoration_content_blocks',
				'label'        => 'Блоки контента',
				'name'         => 'catalog_content_blocks',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить блок',
				'min'          => 1,
				'sub_fields'   => array(
					array(
						'key'          => 'field_decoration_block_heading',
						'label'        => 'Заголовок',
						'name'         => 'catalog_block_heading',
						'type'         => 'text',
						'placeholder'  => 'Заголовок секции',
					),
					array(
						'key'          => 'field_decoration_block_text',
						'label'        => 'Текст',
						'name'         => 'catalog_block_text',
						'type'         => 'textarea',
						'rows'         => 6,
						'placeholder'  => 'Текст секции',
					),
				),
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Models Page
 */
add_action( 'acf/init', 'belgranit_register_models_page_fields' );
function belgranit_register_models_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'models' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_models_page',
		'title'    => 'Настройки страницы «Модели»',
		'fields'   => array(
			array(
				'key'          => 'field_models_hero_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'models_hero_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_models_hero_title',
				'label'        => 'Заголовок',
				'name'         => 'models_hero_title',
				'type'         => 'text',
				'default_value' => 'Модели',
			),
			array(
				'key'          => 'field_models_hero_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'models_hero_subtitle',
				'type'         => 'textarea',
				'rows'         => 2,
			),

			// Section: Content
			array(
				'key'          => 'field_models_content_title',
				'label'        => 'Заголовок секции контента',
				'name'         => 'models_content_title',
				'type'         => 'text',
				'default_value' => 'Модели памятников',
			),
			array(
				'key'          => 'field_models_content_text_1',
				'label'        => 'Текст абзаца 1',
				'name'         => 'models_content_text_1',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_models_content_text_2',
				'label'        => 'Текст абзаца 2',
				'name'         => 'models_content_text_2',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_models_content_text_3',
				'label'        => 'Текст абзаца 3',
				'name'         => 'models_content_text_3',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_models_content_text_4',
				'label'        => 'Текст абзаца 4',
				'name'         => 'models_content_text_4',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_models_content_text_5',
				'label'        => 'Текст абзаца 5',
				'name'         => 'models_content_text_5',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Examples Page
 */
add_action( 'acf/init', 'belgranit_register_examples_page_fields' );
function belgranit_register_examples_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// Удаляем старый field group из БД, если он там есть (чтобы PHP-регистрация перезаписала его).
	$old_fg = acf_get_field_group( 'group_examples_page' );
	if ( $old_fg && isset( $old_fg['ID'] ) ) {
		wp_delete_post( $old_fg['ID'], true );
	}

	$page = get_page_by_path( 'examples' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_examples_page',
		'title'    => 'Настройки страницы «Примеры работ»',
		'fields'   => array(
			array(
				'key'          => 'field_examples_hero_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'examples_hero_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_examples_hero_title',
				'label'        => 'Заголовок',
				'name'         => 'examples_hero_title',
				'type'         => 'text',
				'default_value' => 'Примеры работ',
			),
			array(
				'key'          => 'field_examples_hero_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'examples_hero_subtitle',
				'type'         => 'textarea',
				'rows'         => 2,
			),

			// Tab: Consultation
			array(
				'key'       => 'field_examples_consult_tab',
				'label'     => 'Консультация',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),
			array(
				'key'          => 'field_examples_consult_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'examples_consult_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_examples_consult_title',
				'label'        => 'Заголовок',
				'name'         => 'examples_consult_title',
				'type'         => 'text',
				'default_value' => 'Остались вопросы?',
			),
			array(
				'key'          => 'field_examples_consult_icon',
				'label'        => 'Декоративная иконка',
				'name'         => 'examples_consult_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_examples_consult_text',
				'label'        => 'Текст',
				'name'         => 'examples_consult_text',
				'type'         => 'textarea',
				'default_value' => 'Оставьте заявку. Менеджер перезвонит в ближайшее время.',
				'rows'         => 3,
			),
			array(
				'key'          => 'field_examples_consult_btn_text',
				'label'        => 'Текст кнопки',
				'name'         => 'examples_consult_btn_text',
				'type'         => 'text',
				'default_value' => 'Получить консультацию',
			),
			array(
				'key'          => 'field_examples_consult_btn_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'examples_consult_btn_link',
				'type'         => 'url',
			),
			array(
				'key'          => 'field_examples_consult_features',
				'label'        => 'Преимущества',
				'name'         => 'examples_consult_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 4,
				'sub_fields'   => array(
					array(
						'key'          => 'field_examples_consult_feat_icon',
						'label'        => 'Иконка',
						'name'         => 'examples_consult_feat_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_examples_consult_feat_title',
						'label'        => 'Заголовок',
						'name'         => 'examples_consult_feat_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_examples_consult_feat_desc',
						'label'        => 'Описание',
						'name'         => 'examples_consult_feat_desc',
						'type'         => 'textarea',
						'rows'         => 2,
					),
				),
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Work Example (Пример работы)
 */
add_action( 'acf/init', 'belgranit_register_work_example_fields' );
function belgranit_register_work_example_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_work_example_fields',
		'title'    => 'Детали примера работы',
		'fields'   => array(
			array(
				'key'          => 'field_work_example_description',
				'label'        => 'Описание',
				'name'         => 'work_example_description',
				'type'         => 'textarea',
				'rows'         => 3,
				'placeholder'  => 'Краткое описание работы',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'work_example',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Contacts Page
 */
add_action( 'acf/init', 'belgranit_register_contacts_page_fields' );
function belgranit_register_contacts_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'contacts' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_contacts_page',
		'title'    => 'Настройки страницы «Контакты»',
		'fields'   => array(
			array(
				'key'          => 'field_contacts_hero_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'contacts_hero_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_contacts_hero_title',
				'label'        => 'Заголовок',
				'name'         => 'contacts_hero_title',
				'type'         => 'text',
				'default_value' => 'Контакты',
			),
			array(
				'key'          => 'field_contacts_hero_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'contacts_hero_subtitle',
				'type'         => 'textarea',
				'rows'         => 2,
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Improvement Page
 */
add_action( 'acf/init', 'belgranit_register_improvement_page_fields' );
function belgranit_register_improvement_page_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$page = get_page_by_path( 'improvement' );
	$page_id = $page ? $page->ID : 0;

	if ( $page_id ) {
		$location = array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => (string) $page_id,
				),
			),
		);
	} else {
		$location = array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_improvement_page',
		'title'    => 'Настройки страницы «Благоустройство»',
		'fields'   => array(
			array(
				'key'          => 'field_improvement_hero_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'improvement_hero_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),
			array(
				'key'          => 'field_improvement_hero_title',
				'label'        => 'Заголовок',
				'name'         => 'improvement_hero_title',
				'type'         => 'text',
				'default_value' => 'Благоустройство',
			),
			array(
				'key'          => 'field_improvement_hero_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'improvement_hero_subtitle',
				'type'         => 'textarea',
				'rows'         => 2,
			),

			// Section: Works Slider
			array(
				'key'          => 'field_improvement_works_title',
				'label'        => 'Заголовок секции работ',
				'name'         => 'improvement_works_title',
				'type'         => 'text',
				'default_value' => 'Примеры работ по благоустройству',
			),
			array(
				'key'          => 'field_improvement_works_icon',
				'label'        => 'Иконка-декор',
				'name'         => 'improvement_works_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),

			// Section: Content
			array(
				'key'          => 'field_improvement_content_title',
				'label'        => 'Заголовок секции контента',
				'name'         => 'improvement_content_title',
				'type'         => 'text',
				'default_value' => 'Благоустройство мест захоронения',
			),
			array(
				'key'          => 'field_improvement_content_text_1',
				'label'        => 'Текст абзаца 1',
				'name'         => 'improvement_content_text_1',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_improvement_content_text_2',
				'label'        => 'Текст абзаца 2',
				'name'         => 'improvement_content_text_2',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'          => 'field_improvement_content_text_3',
				'label'        => 'Текст абзаца 3',
				'name'         => 'improvement_content_text_3',
				'type'         => 'wysiwyg',
				'tabs'         => 'text',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			),

			// Section: Services
			array(
				'key'          => 'field_improvement_services_repeater',
				'label'        => 'Блоки услуг',
				'name'         => 'improvement_services',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить блок',
				'min'          => 1,
				'max'          => 10,
				'sub_fields'   => array(
					array(
						'key'          => 'field_improvement_svc_title',
						'label'        => 'Заголовок',
						'name'         => 'improvement_svc_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_improvement_svc_subtitle',
						'label'        => 'Подзаголовок',
						'name'         => 'improvement_svc_subtitle',
						'type'         => 'textarea',
						'rows'         => 2,
					),
					array(
						'key'          => 'field_improvement_svc_image',
						'label'        => 'Изображение',
						'name'         => 'improvement_svc_image',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_improvement_svc_btn_text',
						'label'        => 'Текст кнопки',
						'name'         => 'improvement_svc_btn_text',
						'type'         => 'text',
						'default_value' => 'Рассчитать стоимость',
					),
					array(
						'key'          => 'field_improvement_svc_items',
						'label'        => 'Услуги',
						'name'         => 'improvement_svc_items',
						'type'         => 'repeater',
						'layout'       => 'table',
						'button_label' => 'Добавить услугу',
						'min'          => 1,
						'max'          => 15,
						'sub_fields'   => array(
							array(
								'key'      => 'field_improvement_svc_item_name',
								'label'    => 'Название',
								'name'     => 'improvement_svc_item_name',
								'type'     => 'text',
							),
							array(
								'key'      => 'field_improvement_svc_item_price',
								'label'    => 'Цена',
								'name'     => 'improvement_svc_item_price',
								'type'     => 'text',
							),
						),
					),
				),
			),
		),
		'location' => $location,
	) );
}

/**
 * SCF Fields: Product Services (Installation & Art Design)
 */
add_action( 'acf/init', 'belgranit_register_product_services_fields' );
function belgranit_register_product_services_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_product_services',
		'title'    => 'Услуги товара',
		'fields'   => array(

			// Section Background
			array(
				'key'          => 'field_product_svc_bg_image',
				'label'        => 'Фон секции',
				'name'         => 'product_svc_bg_image',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),

			// Tab: Installation
			array(
				'key'       => 'field_product_svc_tab_install',
				'label'     => 'Установка',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_product_svc_install_icon',
				'label'        => 'Иконка заголовка',
				'name'         => 'product_svc_install_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_product_svc_install_items',
				'label'        => 'Услуги установки',
				'name'         => 'product_svc_install_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить услугу',
				'min'          => 1,
				'max'          => 10,
				'sub_fields'   => array(
					array(
						'key'   => 'field_product_svc_install_name',
						'label' => 'Название',
						'name'  => 'product_svc_install_name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_product_svc_install_price',
						'label' => 'Цена',
						'name'  => 'product_svc_install_price',
						'type'  => 'text',
					),
				),
			),

			array(
				'key'          => 'field_product_svc_install_guarantee_title',
				'label'        => 'Заголовок гарантии',
				'name'         => 'product_svc_install_guarantee_title',
				'type'         => 'text',
				'default_value' => 'Гарантия на камень',
			),

			array(
				'key'          => 'field_product_svc_install_guarantee_text',
				'label'        => 'Текст гарантии',
				'name'         => 'product_svc_install_guarantee_text',
				'type'         => 'text',
				'default_value' => 'Сохраняем качество на долгие годы',
			),

			array(
				'key'          => 'field_product_svc_install_guarantee_years',
				'label'        => 'Лет гарантии',
				'name'         => 'product_svc_install_guarantee_years',
				'type'         => 'number',
				'default_value' => 50,
			),

			array(
				'key'          => 'field_product_svc_install_guarantee_icon',
				'label'        => 'Иконка гарантии',
				'name'         => 'product_svc_install_guarantee_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			// Tab: Art Design
			array(
				'key'       => 'field_product_svc_tab_art',
				'label'     => 'Художественное оформление',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_product_svc_art_icon',
				'label'        => 'Иконка заголовка',
				'name'         => 'product_svc_art_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_product_svc_art_items',
				'label'        => 'Услуги оформления',
				'name'         => 'product_svc_art_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить услугу',
				'min'          => 1,
				'max'          => 10,
				'sub_fields'   => array(
					array(
						'key'   => 'field_product_svc_art_name',
						'label' => 'Название',
						'name'  => 'product_svc_art_name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_product_svc_art_price',
						'label' => 'Цена',
						'name'  => 'product_svc_art_price',
						'type'  => 'text',
					),
				),
			),

			array(
				'key'          => 'field_product_svc_art_note',
				'label'        => 'Примечание внизу',
				'name'         => 'product_svc_art_note',
				'type'         => 'text',
				'default_value' => 'Все работы выполняются нашими специалистами с соблюдением технологий и использованием качественных материалов',
			),

			array(
				'key'          => 'field_product_svc_art_note_icon',
				'label'        => 'Иконка примечания',
				'name'         => 'product_svc_art_note_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			// Tab: Granite Types
			array(
				'key'       => 'field_product_svc_tab_granite',
				'label'     => 'Виды гранита',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_product_svc_granite_title',
				'label'        => 'Заголовок секции',
				'name'         => 'product_svc_granite_title',
				'type'         => 'text',
				'default_value' => 'Виды гранита',
			),

			array(
				'key'          => 'field_product_svc_granite_icon',
				'label'        => 'Иконка под заголовком',
				'name'         => 'product_svc_granite_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			// Tab: Consultation
			array(
				'key'       => 'field_product_svc_tab_consult',
				'label'     => 'Консультация',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_product_consult_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'product_consult_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_product_consult_title',
				'label'        => 'Заголовок',
				'name'         => 'product_consult_title',
				'type'         => 'text',
				'default_value' => 'Остались вопросы?',
			),

			array(
				'key'          => 'field_product_consult_icon',
				'label'        => 'Декоративная иконка',
				'name'         => 'product_consult_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_product_consult_text',
				'label'        => 'Текст',
				'name'         => 'product_consult_text',
				'type'         => 'textarea',
				'default_value' => 'Оставьте заявку. Менеджер перезвонит в ближайшее время.',
				'rows'         => 3,
			),

			array(
				'key'          => 'field_product_consult_btn_text',
				'label'        => 'Текст кнопки',
				'name'         => 'product_consult_btn_text',
				'type'         => 'text',
				'default_value' => 'Получить консультацию',
			),

			array(
				'key'          => 'field_product_consult_btn_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'product_consult_btn_link',
				'type'         => 'url',
			),

			array(
				'key'          => 'field_product_consult_features',
				'label'        => 'Преимущества',
				'name'         => 'product_consult_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 4,
				'sub_fields'   => array(
					array(
						'key'          => 'field_product_consult_feat_icon',
						'label'        => 'Иконка',
						'name'         => 'product_consult_feat_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'   => 'field_product_consult_feat_title',
						'label' => 'Заголовок',
						'name'  => 'product_consult_feat_title',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_product_consult_feat_desc',
						'label' => 'Описание',
						'name'  => 'product_consult_feat_desc',
						'type'  => 'text',
					),
				),
			),

			// Tab: Related Products
			array(
				'key'       => 'field_product_svc_tab_related',
				'label'     => 'Похожие товары',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_product_related_title',
				'label'        => 'Заголовок секции',
				'name'         => 'product_related_title',
				'type'         => 'text',
				'default_value' => 'Похожие товары',
			),

			array(
				'key'          => 'field_product_related_icon',
				'label'        => 'Декоративная иконка',
				'name'         => 'product_related_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'belgranit-product-services',
				),
			),
		),
	) );
}

/**
 * Custom Post Type: Виды гранита
 */
add_action( 'init', 'belgranit_register_granite_type' );
function belgranit_register_granite_type() {
	$labels = array(
		'name'                  => 'Виды гранита',
		'singular_name'         => 'Вид гранита',
		'menu_name'             => 'Виды гранита',
		'add_new'               => 'Добавить вид',
		'add_new_item'          => 'Добавить новый вид гранита',
		'edit_item'             => 'Редактировать вид гранита',
		'new_item'              => 'Новый вид гранита',
		'view_item'             => 'Посмотреть вид гранита',
		'search_items'          => 'Искать виды гранита',
		'not_found'             => 'Виды гранита не найдены',
		'not_found_in_trash'    => 'Виды гранита не найдены в корзине',
		'all_items'             => 'Все виды гранита',
		'archives'              => 'Архив видов гранита',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'granite-types' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'menu_icon'          => 'dashicons-admin-multisite',
		'supports'           => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'granite_type', $args );
}

/**
 * Custom Post Type: Примеры работ
 */
add_action( 'init', 'belgranit_register_work_example' );
function belgranit_register_work_example() {
	$labels = array(
		'name'                  => 'Примеры работ',
		'singular_name'         => 'Пример работы',
		'menu_name'             => 'Примеры работ',
		'add_new'               => 'Добавить работу',
		'add_new_item'          => 'Добавить новый пример работы',
		'edit_item'             => 'Редактировать пример работы',
		'new_item'              => 'Новый пример работы',
		'view_item'             => 'Посмотреть пример работы',
		'search_items'          => 'Искать примеры работ',
		'not_found'             => 'Примеры работ не найдены',
		'not_found_in_trash'    => 'Примеры работ не найдены в корзине',
		'all_items'             => 'Все примеры работ',
		'archives'              => 'Архив примеров работ',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'work-examples' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 27,
		'menu_icon'          => 'dashicons-format-gallery',
		'supports'           => array( 'title', 'thumbnail', 'editor' ),
	);

	register_post_type( 'work_example', $args );
}

/**
 * Custom Taxonomy: Категории работ
 */
add_action( 'init', 'belgranit_register_work_category' );
function belgranit_register_work_category() {
	$labels = array(
		'name'              => 'Категории работ',
		'singular_name'     => 'Категория работы',
		'search_items'      => 'Искать категории',
		'all_items'         => 'Все категории',
		'parent_item'       => 'Родительская категория',
		'parent_item_colon' => 'Родительская категория:',
		'edit_item'         => 'Редактировать категорию',
		'update_item'       => 'Обновить категорию',
		'add_new_item'      => 'Добавить новую категорию',
		'new_item_name'     => 'Название новой категории',
		'menu_name'         => 'Категории работ',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'work-category' ),
	);

	register_taxonomy( 'work_category', array( 'work_example' ), $args );

	// Создаем предустановленные категории
	$categories = array(
		'Памятники'     => 'Памятники',
		'Благоустройство' => 'Благоустройство',
		'Ограды'         => 'Ограды',
		'Оформление'     => 'Оформление',
	);

	foreach ( $categories as $name => $term_name ) {
		if ( ! term_exists( $name, 'work_category' ) ) {
			wp_insert_term( $name, 'work_category' );
		}
	}
}

/**
 * Flush rewrite rules on theme activation
 */
add_action( 'after_switch_theme', 'belgranit_flush_rewrite_rules' );
function belgranit_flush_rewrite_rules() {
	belgranit_register_granite_type();
	belgranit_register_work_example();
	belgranit_register_work_category();
	belgranit_register_model();
	belgranit_register_model_category();
	flush_rewrite_rules();
}

/**
 * Custom Post Type: Модели
 */
add_action( 'init', 'belgranit_register_model' );
function belgranit_register_model() {
	$labels = array(
		'name'                  => 'Модели',
		'singular_name'         => 'Модель',
		'menu_name'             => 'Модели',
		'add_new'               => 'Добавить модель',
		'add_new_item'          => 'Добавить новую модель',
		'edit_item'             => 'Редактировать модель',
		'new_item'              => 'Новая модель',
		'view_item'             => 'Посмотреть модель',
		'search_items'          => 'Искать модели',
		'not_found'             => 'Модели не найдены',
		'not_found_in_trash'    => 'Модели не найдены в корзине',
		'all_items'             => 'Все модели',
		'archives'              => 'Архив моделей',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'model-archive' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 28,
		'menu_icon'          => 'dashicons-car',
		'supports'           => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'model', $args );
}

/**
 * Custom Taxonomy: Категории моделей
 */
add_action( 'init', 'belgranit_register_model_category' );
function belgranit_register_model_category() {
	$labels = array(
		'name'              => 'Категории моделей',
		'singular_name'     => 'Категория моделей',
		'search_items'      => 'Искать категории',
		'all_items'         => 'Все категории',
		'parent_item'       => 'Родительская категория',
		'parent_item_colon' => 'Родительская категория:',
		'edit_item'         => 'Редактировать категорию',
		'update_item'       => 'Обновить категорию',
		'add_new_item'      => 'Добавить новую категорию',
		'new_item_name'     => 'Название новой категории',
		'menu_name'         => 'Категории моделей',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'model-category' ),
	);

	register_taxonomy( 'model_category', array( 'model' ), $args );

	$categories = array(
		'Одинарные памятники',
		'Двойные памятники',
	);

	foreach ( $categories as $name ) {
		if ( ! term_exists( $name, 'model_category' ) ) {
			wp_insert_term( $name, 'model_category' );
		}
	}
}

/**
 * Migrate product services data from individual products to global options
 */
add_action( 'admin_post_belgranit_migrate_product_services', 'belgranit_migrate_product_services' );
function belgranit_migrate_product_services() {
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'belgranit_migrate_product_services' ) ) {
		wp_die( 'Нет доступа' );
	}

	$products = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'meta_key'       => 'product_svc_install_items',
	) );

	if ( empty( $products ) ) {
		wp_die( 'Нет товаров со старыми данными для миграции.' );
	}

	$source = $products[0];
	$fields = array(
		'product_svc_bg_image',
		'product_svc_install_icon',
		'product_svc_install_items',
		'product_svc_install_guarantee_title',
		'product_svc_install_guarantee_text',
		'product_svc_install_guarantee_years',
		'product_svc_install_guarantee_icon',
		'product_svc_art_icon',
		'product_svc_art_items',
		'product_svc_art_note',
		'product_svc_art_note_icon',
		'product_svc_granite_title',
		'product_svc_granite_icon',
		'product_consult_bg',
		'product_consult_title',
		'product_consult_icon',
		'product_consult_text',
		'product_consult_btn_text',
		'product_consult_btn_link',
		'product_consult_features',
	);

	$migrated = 0;
	foreach ( $fields as $field ) {
		$value = get_post_meta( $source->ID, $field, true );
		if ( $value !== '' && $value !== false ) {
			update_field( $field, $value, 'options' );
			$migrated++;
		}
	}

	wp_redirect( admin_url( 'admin.php?page=belgranit-product-services&migrated=' . $migrated ) );
	exit;
}

/**
 * Add migration notice on product services options page
 */
add_action( 'admin_notices', 'belgranit_migration_notice' );
function belgranit_migration_notice() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'toplevel_page_belgranit-product-services' ) {
		return;
	}

	$has_old_data = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => 1,
		'meta_key'       => 'product_svc_install_items',
	) );

	if ( empty( $has_old_data ) ) {
		return;
	}

	if ( isset( $_GET['migrated'] ) ) {
		echo '<div class="notice notice-success"><p>Миграция завершена. Перенесено полей: ' . esc_html( $_GET['migrated'] ) . '</p></div>';
		return;
	}

	$migrate_url = wp_nonce_url(
		admin_url( 'admin-post.php?action=belgranit_migrate_product_services' ),
		'belgranit_migrate_product_services'
	);

	echo '<div class="notice notice-warning"><p><strong>Найдены старые данные в товарах.</strong> <a href="' . esc_url( $migrate_url ) . '">Перенести данные в глобальные настройки</a></p></div>';
}

/**
 * Duplicate Granite Type
 */
add_filter( 'post_row_actions', 'belgranit_granite_type_row_actions', 10, 2 );
function belgranit_granite_type_row_actions( $actions, $post ) {
	if ( $post->post_type !== 'granite_type' ) {
		return $actions;
	}

	$url = wp_nonce_url(
		admin_url( 'admin.php?action=duplicate_granit_as_draft&post=' . $post->ID ),
		'duplicate_granit_' . $post->ID
	);

	$actions['duplicate'] = '<a href="' . esc_url( $url ) . '" title="Дублировать" rel="permalink">Дублировать</a>';

	return $actions;
}

add_action( 'admin_action_duplicate_granit_as_draft', 'belgranit_duplicate_granite_type' );
function belgranit_duplicate_granite_type() {
	if ( ! ( isset( $_GET['post'] ) && current_user_can( 'edit_posts' ) ) ) {
		wp_die( 'Нет доступа' );
	}

	$post_id = absint( $_GET['post'] );
	$post    = get_post( $post_id );

	if ( ! $post || $post->post_type !== 'granite_type' ) {
		wp_die( 'Запись не найдена' );
	}

	check_admin_referer( 'duplicate_granit_' . $post_id );

	$current_user = wp_get_current_user();
	$new_post = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $current_user->ID,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'draft',
		'post_title'     => $post->post_title . ' (копия)',
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order,
	);

	$new_post_id = wp_insert_post( $new_post );

	if ( $new_post_id ) {
		$post_meta = get_post_meta( $post_id );
		foreach ( $post_meta as $key => $values ) {
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, $value );
			}
		}

		if ( has_post_thumbnail( $post_id ) ) {
			$thumbnail_id = get_post_thumbnail_id( $post_id );
			set_post_thumbnail( $new_post_id, $thumbnail_id );
		}
	}

	wp_redirect( admin_url( 'edit.php?post_type=granite_type' ) );
	exit;
}

/**
 * Duplicate Work Example
 */
add_filter( 'post_row_actions', 'belgranit_work_example_row_actions', 10, 2 );
function belgranit_work_example_row_actions( $actions, $post ) {
	if ( $post->post_type !== 'work_example' ) {
		return $actions;
	}

	$url = wp_nonce_url(
		admin_url( 'admin.php?action=duplicate_work_example_as_draft&post=' . $post->ID ),
		'duplicate_work_example_' . $post->ID
	);

	$actions['duplicate'] = '<a href="' . esc_url( $url ) . '" title="Дублировать" rel="permalink">Дублировать</a>';

	return $actions;
}

add_action( 'admin_action_duplicate_work_example_as_draft', 'belgranit_duplicate_work_example' );
function belgranit_duplicate_work_example() {
	if ( ! ( isset( $_GET['post'] ) && current_user_can( 'edit_posts' ) ) ) {
		wp_die( 'Нет доступа' );
	}

	$post_id = absint( $_GET['post'] );
	$post    = get_post( $post_id );

	if ( ! $post || $post->post_type !== 'work_example' ) {
		wp_die( 'Запись не найдена' );
	}

	check_admin_referer( 'duplicate_work_example_' . $post_id );

	$current_user = wp_get_current_user();
	$new_post = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $current_user->ID,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'draft',
		'post_title'     => $post->post_title . ' (копия)',
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order,
	);

	$new_post_id = wp_insert_post( $new_post );

	if ( $new_post_id ) {
		// Копируем все мета-поля
		$post_meta = get_post_meta( $post_id );
		foreach ( $post_meta as $key => $values ) {
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, $value );
			}
		}

		// Копируем миниатюру
		if ( has_post_thumbnail( $post_id ) ) {
			$thumbnail_id = get_post_thumbnail_id( $post_id );
			set_post_thumbnail( $new_post_id, $thumbnail_id );
		}

		// Копируем таксономию (категории)
		$terms = get_the_terms( $post_id, 'work_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$term_ids = wp_list_pluck( $terms, 'term_id' );
			wp_set_object_terms( $new_post_id, $term_ids, 'work_category' );
		}
	}

	wp_redirect( admin_url( 'edit.php?post_type=work_example' ) );
	exit;
}

/**
 * SCF Fields: Stats Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_stats_fields' );
function belgranit_register_stats_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_stats_fields',
		'title'    => 'Секция «Статистика»',
		'fields'   => array(

			// Tab: Background
			array(
				'key'       => 'field_stats_tab_bg',
				'label'     => 'Фон',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_stats_bg_image',
				'label'        => 'Фоновое изображение',
				'name'         => 'stats_bg_image',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),

			// Tab: Items
			array(
				'key'       => 'field_stats_tab_items',
				'label'     => 'Пункты',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_stats_items',
				'label'        => 'Элементы статистики',
				'name'         => 'stats_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить элемент',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_stats_icon',
						'label'        => 'Иконка',
						'name'         => 'stats_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_stats_value',
						'label'        => 'Значение',
						'name'         => 'stats_value',
						'type'         => 'text',

					),
					array(
						'key'          => 'field_stats_label',
						'label'        => 'Подпись',
						'name'         => 'stats_label',
						'type'         => 'text',

					),
					array(
						'key'          => 'field_stats_description',
						'label'        => 'Описание',
						'name'         => 'stats_description',
						'type'         => 'text',
					),
				),
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Reviews Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_reviews_fields' );
function belgranit_register_reviews_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_reviews_fields',
		'title'    => 'Секция «Отзывы»',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_reviews_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_reviews_heading',
				'label'        => 'Заголовок',
				'name'         => 'reviews_heading',
				'type'         => 'text',
				'default_value' => 'Отзывы клиентов',
			),

			array(
				'key'          => 'field_reviews_description',
				'label'        => 'Описание',
				'name'         => 'reviews_description',
				'type'         => 'text',
				'default_value' => 'Нам доверяют память о близких — смотрите реальные отзывы на независимых площадках',
			),

			// Tab: Images
			array(
				'key'       => 'field_reviews_tab_images',
				'label'     => 'Изображения отзывов',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_reviews_images',
				'label'        => 'Скриншоты отзывов',
				'name'         => 'reviews_images',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить отзыв',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_review_image',
						'label'        => 'Изображение',
						'name'         => 'review_image',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_review_alt',
						'label'        => 'Alt текст',
						'name'         => 'review_alt',
						'type'         => 'text',
						'default_value' => 'Отзыв клиента',
					),
				),
			),

			// Tab: Rating
			array(
				'key'       => 'field_reviews_tab_rating',
				'label'     => 'Рейтинг',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_reviews_rating_platform',
				'label'        => 'Площадка (иконка)',
				'name'         => 'reviews_rating_platform',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_reviews_rating_value',
				'label'        => 'Оценка',
				'name'         => 'reviews_rating_value',
				'type'         => 'text',
				'default_value' => '4,8',
			),

			array(
				'key'          => 'field_reviews_rating_label',
				'label'        => 'Подпись к оценке',
				'name'         => 'reviews_rating_label',
				'type'         => 'text',
				'default_value' => 'Средняя оценка нашей компании',
			),

			// Tab: CTA
			array(
				'key'       => 'field_reviews_tab_cta',
				'label'     => 'Кнопка',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_reviews_cta_text',
				'label'        => 'Текст кнопки',
				'name'         => 'reviews_cta_text',
				'type'         => 'text',
				'default_value' => 'Смотреть все отзывы',
			),

			array(
				'key'          => 'field_reviews_cta_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'reviews_cta_link',
				'type'         => 'link',
				'return_format' => 'array',
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Portfolio Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_portfolio_fields' );
function belgranit_register_portfolio_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_portfolio_fields',
		'title'    => 'Секция «Портфолио»',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_portfolio_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_portfolio_heading',
				'label'        => 'Заголовок',
				'name'         => 'portfolio_heading',
				'type'         => 'text',
				'default_value' => 'Наши работы',
			),

			array(
				'key'          => 'field_portfolio_description',
				'label'        => 'Описание',
				'name'         => 'portfolio_description',
				'type'         => 'text',
				'default_value' => 'Каждое изделие — это уважение к памяти и внимание к деталям',
			),

			// Tab: Images
			array(
				'key'       => 'field_portfolio_tab_images',
				'label'     => 'Изображения',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_portfolio_images',
				'label'        => 'Работы',
				'name'         => 'portfolio_images',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить работу',
				'min'          => 1,
				'max'          => 12,
				'sub_fields'   => array(
					array(
						'key'          => 'field_portfolio_image',
						'label'        => 'Изображение',
						'name'         => 'portfolio_image',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_portfolio_alt',
						'label'        => 'Alt текст',
						'name'         => 'portfolio_alt',
						'type'         => 'text',
						'default_value' => 'Пример работы',
					),
				),
			),

			// Tab: CTA
			array(
				'key'       => 'field_portfolio_tab_cta',
				'label'     => 'Кнопка',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_portfolio_cta_text',
				'label'        => 'Текст кнопки',
				'name'         => 'portfolio_cta_text',
				'type'         => 'text',
				'default_value' => 'Смотреть все работы',
			),

			array(
				'key'          => 'field_portfolio_cta_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'portfolio_cta_link',
				'type'         => 'link',
				'return_format' => 'array',
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Consultation Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_consultation_fields' );
function belgranit_register_consultation_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_consultation_fields',
		'title'    => 'Секция «Консультация»',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_consultation_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_consultation_heading',
				'label'        => 'Заголовок',
				'name'         => 'consultation_heading',
				'type'         => 'text',
				'default_value' => 'Не знаете, какой памятник выбрать?',
			),

			array(
				'key'          => 'field_consultation_bg',
				'label'        => 'Фоновое изображение',
				'name'         => 'consultation_bg',
				'type'         => 'image',
				'return_format' => 'url',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_consultation_description',
				'label'        => 'Описание',
				'name'         => 'consultation_description',
				'type'         => 'text',
				'default_value' => 'Мы поможем подобрать вариант с учетом бюджета, пожеланий и особенностей участка',
			),

			// Tab: Features
			array(
				'key'       => 'field_consultation_tab_features',
				'label'     => 'Преимущества',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_consultation_features',
				'label'        => 'Преимущества',
				'name'         => 'consultation_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_consultation_feature_icon',
						'label'        => 'Иконка',
						'name'         => 'consultation_feature_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_consultation_feature_title',
						'label'        => 'Заголовок',
						'name'         => 'consultation_feature_title',
						'type'         => 'text',

					),
					array(
						'key'          => 'field_consultation_feature_description',
						'label'        => 'Описание',
						'name'         => 'consultation_feature_description',
						'type'         => 'text',
					),
				),
			),

			// Tab: Form
			array(
				'key'       => 'field_consultation_tab_form',
				'label'     => 'Форма',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_consultation_form_heading',
				'label'        => 'Заголовок формы',
				'name'         => 'consultation_form_heading',
				'type'         => 'text',
				'default_value' => 'Получите бесплатную консультацию',
			),

			array(
				'key'          => 'field_consultation_form_policy',
				'label'        => 'Текст политики',
				'name'         => 'consultation_form_policy',
				'type'         => 'text',
				'default_value' => 'Соглашаюсь с политикой обработки персональных данных',
			),

			array(
				'key'          => 'field_consultation_form_submit',
				'label'        => 'Текст кнопки отправки',
				'name'         => 'consultation_form_submit',
				'type'         => 'text',
				'default_value' => 'Получить консультацию',
			),

			// Tab: Benefits
			array(
				'key'       => 'field_consultation_tab_benefits',
				'label'     => 'Преимущества формы',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_consultation_benefits',
				'label'        => 'Преимущества',
				'name'         => 'consultation_benefits',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_consultation_benefit_icon',
						'label'        => 'Иконка',
						'name'         => 'consultation_benefit_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size' => 'thumbnail',
						'library'      => 'uploadedTo',
					),
					array(
						'key'          => 'field_consultation_benefit_highlight',
						'label'        => 'Выделенный текст',
						'name'         => 'consultation_benefit_highlight',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_consultation_benefit_text',
						'label'        => 'Текст',
						'name'         => 'consultation_benefit_text',
						'type'         => 'text',

					),
				),
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Popular Products Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_popular_products_fields' );
function belgranit_register_popular_products_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_popular_products_fields',
		'title'    => 'Секция «Популярные решения»',
		'fields'   => array(

			array(
				'key'          => 'field_popular_heading',
				'label'        => 'Заголовок',
				'name'         => 'popular_heading',
				'type'         => 'text',
				'default_value' => 'Популярные решения',
			),

			array(
				'key'          => 'field_popular_icon',
				'label'        => 'Декоративная иконка',
				'name'         => 'popular_icon',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
			),

			array(
				'key'          => 'field_popular_products',
				'label'        => 'Товары',
				'name'         => 'popular_products',
				'type'         => 'relationship',
				'post_type'    => array( 'product' ),
				'filters'      => array( 'search', 'post_tag', 'tax_tax_cat' ),
				'max'          => 12,
				'return_format' => 'id',
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Process Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_process_fields' );
function belgranit_register_process_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_process_fields',
		'title'    => 'Секция «Процесс»',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_process_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_process_heading',
				'label'        => 'Заголовок',
				'name'         => 'process_heading',
				'type'         => 'text',
				'default_value' => 'Берем все этапы на себя',
			),

			// Tab: Steps
			array(
				'key'       => 'field_process_tab_steps',
				'label'     => 'Этапы',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_process_steps',
				'label'        => 'Этапы процесса',
				'name'         => 'process_steps',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить этап',
				'min'          => 1,
				'max'          => 8,
				'sub_fields'   => array(
					array(
						'key'          => 'field_process_step_number',
						'label'        => 'Номер',
						'name'         => 'process_step_number',
						'type'         => 'text',

					),
					array(
						'key'          => 'field_process_step_icon',
						'label'        => 'Иконка',
						'name'         => 'process_step_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_process_step_title',
						'label'        => 'Заголовок',
						'name'         => 'process_step_title',
						'type'         => 'text',

					),
					array(
						'key'          => 'field_process_step_description',
						'label'        => 'Описание',
						'name'         => 'process_step_description',
						'type'         => 'text',
					),
				),
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: 3D Mockup Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_3d_fields' );
function belgranit_register_3d_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_3d_fields',
		'title'    => 'Секция «3D-макет»',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_3d_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_3d_heading',
				'label'        => 'Заголовок',
				'name'         => 'section_3d_heading',
				'type'         => 'text',
				'default_value' => '3D-макет',
			),

			array(
				'key'          => 'field_3d_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'section_3d_subtitle',
				'type'         => 'text',
				'default_value' => 'Мы подготовим реалистичную 3D-визуализацию памятника бесплатно',
			),

			array(
				'key'          => 'field_3d_image',
				'label'        => 'Изображение 3D-макета',
				'name'         => 'section_3d_image',
				'type'         => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
				'library'       => 'all',
			),

			// Tab: Features
			array(
				'key'       => 'field_3d_tab_features',
				'label'     => 'Преимущества',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_3d_features_title',
				'label'        => 'Заголовок списка',
				'name'         => 'section_3d_features_title',
				'type'         => 'text',
				'default_value' => 'Почему вам нужен 3D-макет:',
			),

			array(
				'key'          => 'field_3d_features',
				'label'        => 'Преимущества',
				'name'         => 'section_3d_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить преимущество',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_3d_feature_icon',
						'label'        => 'Иконка',
						'name'         => 'feature_icon',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_3d_feature_title',
						'label'        => 'Заголовок',
						'name'         => 'feature_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_3d_feature_description',
						'label'        => 'Описание',
						'name'         => 'feature_description',
						'type'         => 'textarea',
						'rows'         => 2,
						'new_lines'    => 'br',
					),
				),
			),

			// Tab: CTA
			array(
				'key'       => 'field_3d_tab_cta',
				'label'     => 'Кнопка',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_3d_cta_text',
				'label'        => 'Текст кнопки',
				'name'         => 'section_3d_cta_text',
				'type'         => 'text',
				'default_value' => 'Получить 3D-макет бесплатно',
			),

			array(
				'key'          => 'field_3d_cta_link',
				'label'        => 'Ссылка кнопки',
				'name'         => 'section_3d_cta_link',
				'type'         => 'link',
				'return_format' => 'array',
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Categories Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_categories_fields' );
function belgranit_register_categories_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_categories_fields',
		'title'    => 'Секция «Выберите что вам нужно»',
		'fields'   => array(

			// Tab: Section Title
			array(
				'key'       => 'field_categories_tab_title',
				'label'     => 'Заголовок секции',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_categories_heading',
				'label'        => 'Заголовок',
				'name'         => 'categories_heading',
				'type'         => 'text',
				'default_value' => 'Выберите что вам нужно',
			),

			// Tab: Cards
			array(
				'key'       => 'field_categories_tab_cards',
				'label'     => 'Карточки',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_categories_items',
				'label'        => 'Карточки категорий',
				'name'         => 'categories_items',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить карточку',
				'min'          => 1,
				'max'          => 6,
				'sub_fields'   => array(
					array(
						'key'          => 'field_category_image',
						'label'        => 'Изображение',
						'name'         => 'category_image',
						'type'         => 'image',
						'return_format' => 'url',
						'preview_size'  => 'medium',
						'library'       => 'all',
					),
					array(
						'key'          => 'field_category_title',
						'label'        => 'Название',
						'name'         => 'category_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_category_description',
						'label'        => 'Краткое описание',
						'name'         => 'category_description',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_category_link',
						'label'        => 'Ссылка на страницу',
						'name'         => 'category_link',
						'type'         => 'link',
						'return_format' => 'array',
					),
				),
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

/**
 * SCF Fields: Hero Section (Front Page)
 */
add_action( 'acf/init', 'belgranit_register_hero_fields' );
function belgranit_register_hero_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_hero_fields',
		'title'    => 'Hero секция',
		'fields'   => array(

			// Tab: Main
			array(
				'key'       => 'field_hero_tab_main',
				'label'     => 'Основное',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_hero_heading',
				'label'        => 'Заголовок',
				'name'         => 'hero_heading',
				'type'         => 'textarea',
				'default_value' => "Изготовление памятников\nпод ключ в Могилёве",
				'rows'         => 2,
				'new_lines'    => 'br',
			),

			array(
				'key'          => 'field_hero_bg_image',
				'label'        => 'Фоновое изображение',
				'name'         => 'hero_bg_image',
				'type'         => 'image',
				'return_format' => 'url',
			),

			array(
				'key'          => 'field_hero_subtitle',
				'label'        => 'Подзаголовок',
				'name'         => 'hero_subtitle',
				'type'         => 'text',
				'default_value' => 'Спокойно и без лишних хлопот: от подбора до установки',
			),

			array(
				'key'          => 'field_hero_description',
				'label'        => 'Описание',
				'name'         => 'hero_description',
				'type'         => 'text',
				'default_value' => 'с согласованием на каждом этапе',
			),

			// Tab: Features
			array(
				'key'       => 'field_hero_tab_features',
				'label'     => 'Блоки снизу',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
			),

			array(
				'key'          => 'field_hero_features',
				'label'        => 'Блоки',
				'name'         => 'hero_features',
				'type'         => 'repeater',
				'layout'       => 'table',
				'button_label' => 'Добавить блок',
				'min'          => 1,
				'max'          => 4,
				'sub_fields'   => array(
					array(
						'key'          => 'field_hero_feature_title',
						'label'        => 'Заголовок',
						'name'         => 'hero_feature_title',
						'type'         => 'text',
					),
					array(
						'key'          => 'field_hero_feature_icon',
						'label'        => 'Иконка',
						'name'         => 'hero_feature_icon',
						'type'         => 'image',
						'return_format' => 'url',
					),
				),
			),

		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
	) );
}

