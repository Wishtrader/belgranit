<?php

/**
 * Template Name: Contacts
 * The template for contacts page.
 *
 * @package BelGranit
 */

$page = get_page_by_path('contacts');
$page_id = $page ? $page->ID : 0;
$contacts = belgranit_get_contacts();

$hero_bg = $page_id ? get_field('contacts_hero_bg', $page_id) : '';
$hero_title = $page_id ? (get_field('contacts_hero_title', $page_id) ?: 'Контакты') : 'Контакты';
$hero_subtitle = $page_id ? get_field('contacts_hero_subtitle', $page_id) : '';

$map_address = $contacts['map_address'];
$map_balloon = $contacts['map_balloon'];

$map_src = 'https://yandex.ru/map-widget/v1/?' . http_build_query(
	array(
		'mode' => 'search',
		'text' => $map_address,
		'z'    => 17,
		'lang' => 'ru_RU',
	)
);

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="relative h-[200px] h-[300px] lg:h-[334px] px-[10px] lg:px-5 overflow-hidden mt-[72px] lg:mt-0 bg-cover bg-center bg-no-repeat" <?php if (
    	$hero_bg
	): ?>style="background-image: url('<?php echo esc_url($hero_bg); ?>');"<?php endif; ?>>
		<div class="relative z-10 h-full max-w-[1200px] mx-auto flex flex-col justify-center">
			<nav class="font-body text-[12px] text-[#606060] mb-10 flex flex-wrap items-center gap-x-1 mt-[60px] lg:mt-[136px]">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-black transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-[#606060]"><?php echo esc_html($hero_title); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] font-bold text-[#272727] uppercase leading-tight mb-2">
				<?php echo esc_html($hero_title); ?>
			</h1>
			<?php if ($hero_subtitle): ?>
				<p class="font-manrope font-normal text-lg text-[#4c4c4c]">
					<?php echo esc_html($hero_subtitle); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Contacts Info Section -->
	<section class="py-12 lg:py-9 bg-white">
		<div class="max-w-[1200px] mx-auto px-4 lg:px-0">
			<div class="flex flex-col md:flex-row lg:gap-11">

				<!-- Left: Contact Cards -->
				<div class="flex flex-col gap-5 lg:min-w-[453px]">

					<!-- Address Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-5 transition-shadow hover:shadow-lg shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[64px] h-[64px] rounded-full border border-[#860000]">
							<img src="<?php echo get_template_directory_uri(); ?>/img/pin.svg" alt="pin" class="w-8 h-8" />
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-[10px]">Адрес офиса</h3>
							<p class="font-body !font-light text-sm text-[#182028] leading-[1.2]">
								<?php echo esc_html($contacts['address']); ?><br>
								<?php echo esc_html($contacts['address_2']); ?>
							</p>
						</div>
					</div>

					<!-- Phones Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-5 transition-shadow hover:shadow-lg shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[64px] h-[64px] rounded-full border border-[#860000]">
							<img src="<?php echo get_template_directory_uri(); ?>/img/phone.svg" alt="phone" class="w-8 h-8" />
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">Телефоны</h3>
							<div class="font-body text-sm text-[#4c4c4c] leading-relaxed">
								<a href="tel:<?php echo
    								esc_attr(belgranit_phone_link($contacts['phone_1']))
								; ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html($contacts['phone_1']); ?></a>
								<a href="tel:<?php echo
    								esc_attr(belgranit_phone_link($contacts['phone_2']))
								; ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html($contacts['phone_2']); ?></a>
								<a href="tel:<?php echo
    								esc_attr(belgranit_phone_link($contacts['phone_3']))
								; ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html($contacts['phone_3']); ?></a>
								<a href="tel:<?php echo
    								esc_attr(belgranit_phone_link($contacts['phone_4']))
								; ?>" class="block hover:text-[#860000] transition-colors"><?php echo
    								esc_html($contacts['phone_4'])
								; ?> (Производство)</a>
							</div>
						</div>
					</div>

					<!-- Work Hours Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-5 transition-shadow hover:shadow-lg shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[64px] h-[64px] rounded-full border border-[#860000]">
							<img src="<?php echo get_template_directory_uri(); ?>/img/clock.svg" alt="clock" class="w-8 h-8" />
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">Режим работы</h3>
							<div class="font-body text-sm text-[#4c4c4c] leading-relaxed">
								<span>Пн-Пт <?php echo esc_html($contacts['hours_weekday']); ?></span><br>
								<span>Сб <?php echo esc_html($contacts['hours_sat']); ?></span><br>
								<span>Вс <?php echo esc_html($contacts['hours_sun']); ?></span><br>
								<span>Производство: Пн-Пт <?php echo esc_html($contacts['hours_production']); ?></span>
							</div>
						</div>
					</div>

					<!-- Email Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-5 transition-shadow hover:shadow-lg shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[64px] h-[64px] rounded-full border border-[#860000]">
							<img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg" alt="mail" class="w-8 h-8" />
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">E-mail</h3>
							<a href="mailto:<?php echo
    							esc_attr($contacts['email'])
							; ?>" class="font-body text-sm text-[#4c4c4c] hover:text-[#860000] transition-colors">
								<?php echo esc_html($contacts['email']); ?>
							</a>
						</div>
					</div>

				</div>

				<!-- Right: Yandex Map -->
				<div id="contacts-map" class="w-full mt-8 lg:mt-0 h-[380px] lg:h-[610px] rounded-xl overflow-hidden bg-gray-100">
					<iframe
						src="<?php echo esc_url( $map_src ); ?>"
						class="w-full h-full border-0"
						frameborder="0"
						allowfullscreen
						loading="lazy"
						title="<?php echo esc_attr( $map_balloon ? $map_balloon : $map_address ); ?>"
					></iframe>
				</div>

			</div>
		</div>
	</section>
</main>

<?php get_footer();
