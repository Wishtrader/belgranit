<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BelGranit
 */

$contacts     = belgranit_get_contacts();
$phone_1_link = belgranit_phone_link( $contacts['phone_1'] );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Manrope:wght@700&family=Playfair+Display+SC:wght@400;700&display=swap" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
	<script>
	tailwind.config = {
		theme: {
			extend: {
				colors: {
					brand: '#860000',
					'brand-dark': '#650d10',
					ink: '#182028',
					charcoal: '#272727',
					muted: '#f5f4f3',
				},
				fontFamily: {
					playfair: ['Playfair Display SC', 'serif'],
					manrope: ['Manrope', 'sans-serif'],
					body: ['Inter', 'sans-serif'],
				},
				maxWidth: {
					content: '1200px',
				},
			},
		},
	};
	</script>
	<style>
	/* Font families */
	.font-playfair { font-family: 'Playfair Display SC', serif; }
	.font-manrope { font-family: 'Manrope', sans-serif; }
	.font-body { font-family: 'Inter', sans-serif; }
	.font-heading { font-family: 'Playfair Display SC', serif; }

	/* Mobile menu transitions */
	#mobile-menu {
		transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
	}
	#mobile-menu.hidden-menu {
		transform: translateX(100%);
		opacity: 0;
		pointer-events: none;
	}
	#mobile-menu.visible-menu {
		transform: translateX(0);
		opacity: 1;
		pointer-events: auto;
	}

	/* Submenu animation */
	.mobile-submenu {
		overflow: hidden;
		max-height: 0;
		transition: max-height 0.35s ease-in-out;
	}

	/* Submenu arrow rotation */
	.submenu-arrow {
		transition: transform 0.3s ease;
	}
	.mobile-submenu-toggle[aria-expanded="true"] .submenu-arrow {
		transform: rotate(180deg);
	}

	/* Desktop menu hover */
	.desktop-nav a {
		transition: color 0.2s ease;
	}

	/* Active menu item underline */
	.desktop-nav .current-menu-item > a > span {
		text-decoration: underline;
		text-decoration-color: #650D10;
		text-decoration-thickness: 1px;
		text-underline-offset: 5px;
	}

	/* Remove right padding from last menu item */
	.desktop-nav ul li:last-child a span {
		padding-right: 0;
	}

	/* Header scroll transitions */
	#top-bar {
		transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
		max-height: 100px;
		overflow: hidden;
	}
	#top-bar.hidden-bar {
		max-height: 0;
		opacity: 0;
	}
	#main-header {
		transition: background-color 0.3s ease-in-out;
	}
	#main-header.scrolled {
		background-color: rgba(255, 255, 255, 0.95);
	}

	html {
		scroll-behavior: smooth;
	}
	</style>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

<header id="masthead" class="site-header fixed top-0 left-0 right-0 z-[100]">
	<!-- ========== TOP BAR (Desktop only) ========== -->
	<div id="top-bar" class="hidden lg:block relative z-40">
		<div class="max-w-[1200px] mx-auto py-3 flex items-center justify-between gap-8">
			<!-- Address -->
			<div class="flex items-start gap-3 shrink-0">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/img/pin.svg' ); ?>" alt="" class="w-5 h-5 mt-0.5 shrink-0">
				<span class="text-sm leading-[1.4] font-body">
					<span class="font-semibold"><?php echo esc_html( $contacts['address'] ); ?></span><br>
					<span class="font-normal"><?php echo esc_html( $contacts['address_2'] ); ?></span>
				</span>
			</div>
			<div class="border-[1px] border-[#F3E9E9]/10 h-10"></div>

			<!-- Phones -->
			<div class="flex items-center gap-4 flex-wrap justify-center font-body">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/img/phone.svg' ); ?>" alt="" class="w-5 h-5 shrink-0">
				<?php
				$top_phone_fields = array( 'phone_1', 'phone_2', 'phone_3' );
				foreach ( $top_phone_fields as $tp ) :
					$pn = $contacts[ $tp ];
				?>
				<a href="tel:<?php echo esc_attr( belgranit_phone_link( $pn ) ); ?>" class="text-sm font-medium text-gray-900 hover:text-red-700 hover:underline whitespace-nowrap transition-colors">
					<?php
					if ( preg_match( '/^(.+?\)\s*)(.+)$/', $pn, $pm ) ) {
						echo esc_html( $pm[1] ) . '<strong class="font-bold">' . esc_html( $pm[2] ) . '</strong>';
					} else {
						echo esc_html( $pn );
					}
					?>
				</a>
				<?php endforeach; ?>
			</div>

			<div class="border-[1px] border-[#F3E9E9]/10 h-10"></div>

			<!-- CTA Button -->
			<a href="#callback" class="inline-flex pl-[10px] items-center gap-[6px] bg-[#860000] hover:bg-red-600 text-white text-sm font-semibold uppercase tracking-wide rounded-[6px] transition-colors shrink-0 lg:w-[182px] lg:h-[29px] font-body !font-normal">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/img/phone-white.svg' ); ?>" alt="" class="w-4 h-4">
				Заказать звонок
			</a>
		</div>
	</div>

	<!-- ========== MAIN HEADER ========== -->
	<div id="main-header" class="bg-white/50 backdrop-blur-xl">
		<div class="max-w-[1200px] px-4 mx-auto flex items-center justify-between h-[72px]">

			<!-- Logo -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center shrink-0 h-14" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php belgranit_logo( 'h-auto w-[233px]' ); ?>
			</a>

			<!-- Desktop Navigation -->
			<nav id="site-navigation" class="desktop-nav hidden lg:flex items-center gap-1 xl:gap-1" aria-label="Основная навигация">
				<?php
				wp_nav_menu(
				array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'flex gap-1 xl:gap-2 items-center list-none m-0 p-0',
				'container'      => false,
				'depth'          => 1,
				'link_before'    => '<span class="flex px-2 py-2 text-[14px] font-body font-bold leading-[120%] tracking-normal uppercase text-gray-900 hover:text-red-700 transition-colors cursor-pointer whitespace-nowrap">',
				'link_after'     => '</span>',
				)
				);
				?>
			</nav>

			<!-- Mobile Controls -->
			<div class="flex items-center gap-2 lg:hidden">
				<!-- Phone dropdown -->
				<button id="header-phone-toggle" class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 bg-red-800 hover:bg-red-700 rounded-[6px] transition-colors" aria-label="Позвонить" aria-expanded="false">
					<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
					</svg>
				</button>

				<button id="menu-toggle" class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 bg-red-800 hover:bg-red-700 rounded-[6px] transition-colors" aria-label="Меню" aria-expanded="false">
					<svg id="icon-hamburger" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
						<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
					</svg>
					<svg id="icon-close" class="w-5 h-5 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
					</svg>
				</button>
			</div>
		</div>
	</div>

	<!-- Header Contacts Dropdown (full-width) -->
	<div id="header-contacts-dropdown" class="hidden lg:hidden fixed inset-x-0 top-[72px] z-[90] bg-white shadow-lg">
		<div class="max-w-[1200px] mx-auto px-6 py-6">
			<div class="space-y-4 mx-auto max-w-[190px] mt-8">
				<?php
				$phone_fields = array( 'phone_1', 'phone_2', 'phone_3' );
				foreach ( $phone_fields as $phone_key ) :
				$phone_number = $contacts[ $phone_key ];
				if ( preg_match( '/^(.+?\)\s*)(.+)$/', $phone_number, $m ) ) :
				?>
				<a href="tel:<?php echo esc_attr( belgranit_phone_link( $phone_number ) ); ?>" class="flex items-center gap-3 text-ink hover:text-red-700 transition-colors">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/phone.svg' ); ?>" alt="" class="w-6 h-6 shrink-0">
					<span class="font-body text-sm"><?php echo esc_html( $m[1] ); ?><strong class="font-bold"><?php echo esc_html( $m[2] ); ?></strong></span>
				</a>
				<?php
				else :
				?>
				<a href="tel:<?php echo esc_attr( belgranit_phone_link( $phone_number ) ); ?>" class="flex items-center gap-3 text-ink hover:text-red-700 transition-colors">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/phone.svg' ); ?>" alt="" class="w-6 h-6 shrink-0">
					<span class="font-body text-sm font-bold"><?php echo esc_html( $phone_number ); ?></span>
				</a>
				<?php
				endif;
				endforeach;
				?>
			</div>
			<a href="#callback" class="header-phone-callback mt-6 mx-auto max-w-[190px] h-8 block w-full text-center bg-[#860000] hover:bg-red-700 text-white font-body text-sm font-bold uppercase tracking-wide rounded-[6px] px-2 py-2 transition-colors">
				Заказать звонок
			</a>
		</div>
	</div>

	<!-- Backdrop overlay for dropdown -->
	<div id="header-dropdown-backdrop" class="hidden lg:hidden fixed inset-0 z-[80] bg-black/40 backdrop-blur-sm"></div>

	<!-- ========== MOBILE MENU ========== -->
	<div id="mobile-menu" class="fixed inset-0 z-[150] lg:hidden hidden-menu bg-white overflow-y-auto" style="top: 0;">
		<!-- Mobile header inside menu -->
		<div class="flex items-center justify-between px-6 h-16 sm:h-20 bg-white">
			<!-- Logo -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center h-14" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php belgranit_logo( 'h-auto w-[233px]' ); ?>
			</a>

			<button id="menu-close" class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 bg-red-800 hover:bg-red-700 rounded-[6px] transition-colors" aria-label="Закрыть меню">
				<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
				</svg>
			</button>
		</div>

		<!-- Mobile Navigation -->
		<nav class="px-6 py-4" aria-label="Мобильная навигация">
			<?php
			$catalog_categories = array(
			'pamyatniki'      => 'Памятники',
			'blagoustrojstvo' => 'Благоустройство',
			'ogradi'          => 'Ограды',
			'oformlenie'      => 'Оформление',
			);

			foreach ( $catalog_categories as $slug => $title ) :
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $term || is_wp_error( $term ) ) {
			continue;
			}

			$children = get_terms( array(
			'taxonomy'   => 'product_cat',
			'parent'     => $term->term_id,
			'hide_empty' => false,
			) );

			$has_children = ! empty( $children ) && ! is_wp_error( $children );
			?>
			<div class="mb-1">
				<?php if ( $has_children ) : ?>
				<button class="mobile-submenu-toggle w-full flex items-center justify-between py-4 text-lg font-bold uppercase tracking-wider text-gray-900 hover:text-red-700 transition-colors" data-submenu="submenu-<?php echo esc_attr( $term->term_id ); ?>">
					<span><?php echo esc_html( $title ); ?></span>
					<svg class="submenu-arrow w-5 h-5 text-gray-900 shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
					</svg>
				</button>
				<div id="submenu-<?php echo esc_attr( $term->term_id ); ?>" class="mobile-submenu">
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="block py-2.5 pl-4 text-base text-gray-900 hover:text-red-700 transition-colors">
						Все <?php echo esc_html( mb_strtolower( $title ) ); ?>
					</a>
					<?php foreach ( $children as $child ) : ?>
					<a href="<?php echo esc_url( get_term_link( $child ) ); ?>" class="block py-2.5 pl-4 text-base text-gray-900 hover:text-red-700 transition-colors <?php echo is_product_category( $child->term_id ) ? 'text-red-800 font-semibold' : ''; ?>">
						<?php echo esc_html( $child->name ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php else : ?>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="block py-4 text-lg font-bold uppercase tracking-wider text-gray-900 hover:text-red-700 transition-colors">
					<?php echo esc_html( $title ); ?>
				</a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>

			<?php
			$static_pages = array(
			array( 'title' => 'Модели', 'slug' => 'models' ),
			array( 'title' => 'Примеры работ', 'slug' => 'examples' ),
			array( 'title' => 'Контакты', 'slug' => 'contacts' ),
			);
			foreach ( $static_pages as $page ) :
			$page_obj = get_page_by_path( $page['slug'] );
			$url = $page_obj ? get_permalink( $page_obj ) : '#';
			?>
			<a href="<?php echo esc_url( $url ); ?>" class="block py-4 text-lg font-bold uppercase tracking-wider text-gray-900 hover:text-red-700 transition-colors">
				<?php echo esc_html( $page['title'] ); ?>
			</a>
			<?php endforeach; ?>
		</nav>
	</div>
</header>

<script>
(function() {
	// Elements
	var menuToggle       = document.getElementById('menu-toggle');
	var menuClose        = document.getElementById('menu-close');
	var mobileMenu       = document.getElementById('mobile-menu');
	var iconHamburger    = document.getElementById('icon-hamburger');
	var iconClose        = document.getElementById('icon-close');
	var headerPhoneBtn   = document.getElementById('header-phone-toggle');
	var headerDropdown   = document.getElementById('header-contacts-dropdown');
	var headerBackdrop   = document.getElementById('header-dropdown-backdrop');

	// --- Helpers ---
	function closeAllDropdowns() {
		if (headerDropdown) {
			headerDropdown.classList.add('hidden');
			if (headerPhoneBtn) headerPhoneBtn.setAttribute('aria-expanded', 'false');
		}
		if (headerBackdrop) {
			headerBackdrop.classList.add('hidden');
		}
	}

	// --- Mobile Menu ---
	function openMenu() {
		closeAllDropdowns();
		mobileMenu.classList.remove('hidden-menu');
		mobileMenu.classList.add('visible-menu');
		iconHamburger.classList.add('hidden');
		iconClose.classList.remove('hidden');
		menuToggle.setAttribute('aria-expanded', 'true');
		document.body.style.overflow = 'hidden';
	}

	function closeMenu() {
		mobileMenu.classList.remove('visible-menu');
		mobileMenu.classList.add('hidden-menu');
		iconHamburger.classList.remove('hidden');
		iconClose.classList.add('hidden');
		menuToggle.setAttribute('aria-expanded', 'false');
		document.body.style.overflow = '';
	}

	if (menuToggle) {
		menuToggle.addEventListener('click', function() {
			var isOpen = mobileMenu.classList.contains('visible-menu');
			if (isOpen) {
				closeMenu();
			} else {
				closeAllDropdowns();
				openMenu();
			}
		});
	}

	if (menuClose) {
		menuClose.addEventListener('click', closeMenu);
	}

	// --- Header Phone Dropdown ---
	if (headerPhoneBtn && headerDropdown) {
		headerPhoneBtn.addEventListener('click', function(e) {
			e.stopPropagation();
			var isExpanded = this.getAttribute('aria-expanded') === 'true';
			closeAllDropdowns();
			if (!isExpanded) {
				// Если меню открыто — закрыть его
				if (mobileMenu.classList.contains('visible-menu')) {
					closeMenu();
				}
				headerDropdown.classList.remove('hidden');
				if (headerBackdrop) headerBackdrop.classList.remove('hidden');
				this.setAttribute('aria-expanded', 'true');
				document.body.style.overflow = 'hidden';
			}
		});
	}

	// --- Backdrop click closes dropdown ---
	if (headerBackdrop) {
		headerBackdrop.addEventListener('click', function() {
			closeAllDropdowns();
			document.body.style.overflow = '';
		});
	}

	// --- Callback links ---
	document.querySelectorAll('.header-phone-callback, .mobile-phone-callback').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			closeAllDropdowns();
			closeMenu();
			var popup = document.getElementById('callback-popup');
			if (popup) {
				popup.classList.remove('hidden');
				popup.classList.add('flex');
				document.body.style.overflow = 'hidden';
			}
		});
	});

	// --- Click outside ---
	document.addEventListener('click', function(e) {
		if (headerDropdown && !headerDropdown.contains(e.target) && e.target !== headerPhoneBtn) {
			headerDropdown.classList.add('hidden');
			if (headerBackdrop) headerBackdrop.classList.add('hidden');
			headerPhoneBtn.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
		}
	});

	// --- Close on resize ---
	window.addEventListener('resize', function() {
		if (window.innerWidth >= 1024) {
			closeMenu();
			closeAllDropdowns();
			document.body.style.overflow = '';
		}
	});

	// --- Submenu toggle (accordion) ---
	document.querySelectorAll('.mobile-submenu-toggle').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var submenuId = this.getAttribute('data-submenu');
			var submenu   = document.getElementById(submenuId);
			var isExpanded = this.getAttribute('aria-expanded') === 'true';

			if (submenu) {
				if (isExpanded) {
					submenu.style.maxHeight = '0';
					this.setAttribute('aria-expanded', 'false');
				} else {
					submenu.style.maxHeight = submenu.scrollHeight + 'px';
					this.setAttribute('aria-expanded', 'true');
				}
			}
		});
	});

	// Scroll behavior: hide top bar, white bg on main header
	var topBar     = document.getElementById('top-bar');
	var mainHeader = document.getElementById('main-header');
	var scrollThreshold = 50;

	function handleScroll() {
		var scrollY = window.scrollY || window.pageYOffset;

		if (window.innerWidth >= 1024) {
			if (scrollY > scrollThreshold) {
				topBar.classList.add('hidden-bar');
				mainHeader.classList.add('scrolled');
			} else {
				topBar.classList.remove('hidden-bar');
				mainHeader.classList.remove('scrolled');
			}
		} else {
			topBar.classList.remove('hidden-bar');
			mainHeader.classList.remove('scrolled');
		}
	}

	window.addEventListener('scroll', handleScroll, { passive: true });
	window.addEventListener('resize', handleScroll, { passive: true });
	handleScroll();
})();
</script>
