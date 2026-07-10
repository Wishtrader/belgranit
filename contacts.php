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
				<div id="contacts-map" class="w-full mt-8 lg:mt-0 h-[380px] lg:h-[610px] rounded-xl overflow-hidden bg-gray-100"></div>

			</div>
		</div>
	</section>

	<!-- Yandex Maps -->
	<script src="https://api-maps.yandex.ru/2.1/?apikey=&lang=ru_RU" type="text/javascript"></script>
	<script type="text/javascript">
	(function() {
		var mapAddress = <?php echo wp_json_encode(esc_attr($map_address)); ?>;
		var mapBalloon = <?php echo wp_json_encode(esc_attr($map_balloon)); ?>;

		ymaps.ready(function() {
			var myMap = new ymaps.Map('contacts-map', {
				center: [53.9, 30.3],
				zoom: 14,
				controls: ['zoomControl', 'fullscreenControl']
			});

			var myPlacemark;

			function updateMap(address) {
				ymaps.geocode(address, { results: 1 }).then(function(res) {
					var firstGeoObject = res.geoObjects.get(0);
					if (!firstGeoObject) return;

					var coords = firstGeoObject.geometry.getCoordinates();

					myMap.geoObjects.removeAll();

					myPlacemark = new ymaps.Placemark(coords, {
						balloonContent: mapBalloon
					}, {
						preset: 'islands#redDotIcon',
						iconColor: '#860000'
					});

					myMap.geoObjects.add(myPlacemark);
					myMap.setCenter(coords, 14, { duration: 500 });
				});
			}

			updateMap(mapAddress);
		});
	})();
	</script>

	<!-- Consultation Section -->
	<?php

	$consult_bg = get_field('product_consult_bg', 'options');
	$consult_title = get_field('product_consult_title', 'options') ?: 'Остались вопросы?';
	$consult_icon = get_field('product_consult_icon', 'options');
	$consult_text = get_field('product_consult_text', 'options')
	?: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.';
	$consult_btn_text = get_field('product_consult_btn_text', 'options') ?: 'Получить консультацию';
	$consult_btn_link = get_field('product_consult_btn_link', 'options') ?: '#callback';
	$consult_features = get_field('product_consult_features', 'options');
	?>

	<div class="relative py-12 lg:py-20 lg:mt-[76px] px-[10px] text-center lg:text-start" <?php if (
    	$consult_bg
	): ?>style="background-image: url('<?php echo
    	esc_url($consult_bg)
	; ?>'); background-size: cover; background-position: center;"<?php endif; ?>>

		<div class="relative max-w-[1200px] mx-auto">
			<div class="flex flex-col md:flex-row justify-between">

				<!-- Left: CTA -->
				<div>
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php echo esc_html($consult_title); ?>
					</h2>

					<?php if ($consult_icon): ?>
						<div class="flex items-center justify-center lg:justify-start gap-3 mb-6">
							<img src="<?php echo esc_url($consult_icon); ?>" alt="" class="">
						</div>
					<?php endif; ?>

					<p class="text-gray-600 font-body text-center lg:text-left leading-[1.2] mb-10 max-w-md">
						<?php echo esc_html($consult_text); ?>
					</p>

					<a
						href="<?php echo esc_url($consult_btn_link); ?>"
						data-popup="consult"
						class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 lg:w-[344px] w-full text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body mb-8 lg:mb-0"
					>
						<?php echo esc_html($consult_btn_text); ?>
					<img src="<?php echo get_template_directory_uri(); ?>/img/arr2.svg" alt="arrow" class="" />
					</a>
				</div>

				<!-- Right: Features -->
				<?php if (!empty($consult_features)): ?>
					<div class="space-y-10">
						<?php foreach ($consult_features as $feature):
    						$feat_icon = $feature['product_consult_feat_icon'] ?? '';
    						$feat_title = $feature['product_consult_feat_title'] ?? '';
    						$feat_desc = $feature['product_consult_feat_desc'] ?? '';
    						if (!$feat_title)
        						continue;
    						?>
							<div class="flex items-center gap-4 lg:w-[390px]">
								<div class="w-16 h-16 rounded-full border border-[#860000] flex items-center justify-center shrink-0">
										<img src="<?php echo esc_url($feat_icon); ?>" alt="" class="w-8 h-8">
								</div>
								<div>
								<h3 class="font-manrope text-left font-bold text-[#182028] text-lg mb-1"><?php echo
    								esc_html($feat_title)
								; ?></h3>
								<p class="text-gray-500 text-left font-body text-sm leading-[1.4] max-w-[250px]"><?php echo
    								esc_html($feat_desc)
								; ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

</main>

<?php get_footer();
