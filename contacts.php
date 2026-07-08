<?php
/**
 * Template Name: Contacts
 * The template for contacts page.
 *
 * @package BelGranit
 */

$page     = get_page_by_path( 'contacts' );
$page_id  = $page ? $page->ID : 0;
$contacts = belgranit_get_contacts();

$hero_bg       = $page_id ? get_field( 'contacts_hero_bg', $page_id ) : '';
$hero_title    = $page_id ? ( get_field( 'contacts_hero_title', $page_id ) ?: 'Контакты' ) : 'Контакты';
$hero_subtitle = $page_id ? get_field( 'contacts_hero_subtitle', $page_id ) : '';

$map_address = $contacts['map_address'];
$map_balloon = $contacts['map_balloon'];

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="relative h-[200px] sm:h-[240px] lg:h-[334px] overflow-hidden mt-[72px] lg:mt-0 bg-cover bg-center bg-no-repeat" <?php if ( $hero_bg ) : ?>style="background-image: url('<?php echo esc_url( $hero_bg ); ?>');"<?php endif; ?>>
		<div class="relative z-10 h-full max-w-[1200px] mx-auto flex flex-col justify-center">
			<nav class="font-body text-[12px] text-[#606060] mb-10 flex flex-wrap items-center gap-x-1 mt-[60px] lg:mt-[136px]">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-black transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-[#606060]"><?php echo esc_html( $hero_title ); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] font-bold text-[#272727] uppercase leading-tight mb-2">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="font-manrope font-normal text-lg text-[#4c4c4c]">
					<?php echo esc_html( $hero_subtitle ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Contacts Info Section -->
	<section class="py-12 lg:py-20 bg-white">
		<div class="max-w-[1200px] mx-auto px-4 lg:px-0">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

				<!-- Left: Contact Cards -->
				<div class="flex flex-col gap-6">

					<!-- Address Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-6 transition-shadow hover:shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[52px] h-[52px] rounded-full border border-[#860000]/20">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 22C12 22 19.5 16 19.5 9.5C19.5 5.358 16.142 2 12 2C7.858 2 4.5 5.358 4.5 9.5C4.5 16 12 22 12 22Z" stroke="#650D10" stroke-linejoin="round"/>
								<path d="M12 12.5C12.394 12.5 12.7841 12.4224 13.1481 12.2716C13.512 12.1209 13.8427 11.8999 14.1213 11.6213C14.3999 11.3427 14.6209 11.012 14.7716 10.6481C14.9224 10.2841 15 9.89397 15 9.5C15 9.10603 14.9224 8.71593 14.7716 8.35195C14.6209 7.98797 14.3999 7.65726 14.1213 7.37868C13.8427 7.1001 13.512 6.87913 13.1481 6.72836C12.7841 6.5776 12.394 6.5 12 6.5C11.2044 6.5 10.4413 6.81607 9.87868 7.37868C9.31607 7.94129 9 8.70435 9 9.5C9 10.2956 9.31607 11.0587 9.87868 11.6213C10.4413 12.1839 11.2044 12.5 12 12.5Z" stroke="#650D10" stroke-linejoin="round"/>
							</svg>
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">Адрес офиса</h3>
							<p class="font-body text-sm text-[#4c4c4c] leading-relaxed">
								<?php echo esc_html( $contacts['address'] ); ?><br>
								<?php echo esc_html( $contacts['address_2'] ); ?>
							</p>
						</div>
					</div>

					<!-- Phones Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-6 transition-shadow hover:shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[52px] h-[52px] rounded-full border border-[#860000]/20">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8.49823 3.84299C8.67669 3.84299 8.85189 3.89075 9.00566 3.98131C9.15944 4.07186 9.28618 4.20192 9.37273 4.35799L10.5957 6.56099C10.6746 6.70309 10.7176 6.86228 10.7211 7.02476C10.7246 7.18725 10.6884 7.34813 10.6157 7.49349L9.43773 9.85049C9.43773 9.85049 9.77923 11.606 11.2082 13.0355C12.6377 14.4645 14.3872 14.8005 14.3872 14.8005L16.7437 13.622C16.8891 13.5492 17.0501 13.513 17.2127 13.5165C17.3753 13.52 17.5346 13.563 17.6767 13.642L19.8867 14.871C20.0424 14.9577 20.1721 15.0844 20.2624 15.2381C20.3527 15.3918 20.4003 15.5668 20.4002 15.745V18.2815C20.4002 19.5735 19.2002 20.5065 17.9762 20.0935C15.4622 19.245 11.5597 17.63 9.08623 15.1565C6.61323 12.683 4.99773 8.78099 4.14973 6.26649C3.73673 5.04299 4.66973 3.84299 5.96173 3.84299H8.49823Z" stroke="#650D10" stroke-linejoin="round"/>
							</svg>
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">Телефоны</h3>
							<div class="font-body text-sm text-[#4c4c4c] leading-relaxed">
								<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_1'] ) ); ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html( $contacts['phone_1'] ); ?></a>
								<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_2'] ) ); ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html( $contacts['phone_2'] ); ?></a>
								<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_3'] ) ); ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html( $contacts['phone_3'] ); ?></a>
								<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_4'] ) ); ?>" class="block hover:text-[#860000] transition-colors"><?php echo esc_html( $contacts['phone_4'] ); ?> (Производство)</a>
							</div>
						</div>
					</div>

					<!-- Work Hours Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-6 transition-shadow hover:shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[52px] h-[52px] rounded-full border border-[#860000]/20">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="12" cy="12" r="9.5" stroke="#650D10"/>
								<path d="M12 7V12L15 14" stroke="#650D10" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">Режим работы</h3>
							<div class="font-body text-sm text-[#4c4c4c] leading-relaxed">
								<span>Пн-Пт <?php echo esc_html( $contacts['hours_weekday'] ); ?></span><br>
								<span>Сб <?php echo esc_html( $contacts['hours_sat'] ); ?></span><br>
								<span>Вс <?php echo esc_html( $contacts['hours_sun'] ); ?></span><br>
								<span>Производство: Пн-Пт <?php echo esc_html( $contacts['hours_production'] ); ?></span>
							</div>
						</div>
					</div>

					<!-- Email Card -->
					<div class="flex items-start gap-5 rounded-xl border border-gray-200 p-6 transition-shadow hover:shadow-md">
						<div class="flex-shrink-0 flex items-center justify-center w-[52px] h-[52px] rounded-full border border-[#860000]/20">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="2.5" y="4.5" width="19" height="15" rx="2" stroke="#650D10"/>
								<path d="M2.5 6.5L12 13.5L21.5 6.5" stroke="#650D10" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						<div>
							<h3 class="font-manrope text-lg font-bold text-[#272727] mb-1">E-mail</h3>
							<a href="mailto:<?php echo esc_attr( $contacts['email'] ); ?>" class="font-body text-sm text-[#4c4c4c] hover:text-[#860000] transition-colors">
								<?php echo esc_html( $contacts['email'] ); ?>
							</a>
						</div>
					</div>

				</div>

				<!-- Right: Yandex Map -->
				<div id="contacts-map" class="w-full h-[400px] lg:h-[520px] rounded-xl overflow-hidden bg-gray-100"></div>

			</div>
		</div>
	</section>

	<!-- Yandex Maps -->
	<script src="https://api-maps.yandex.ru/2.1/?apikey=&lang=ru_RU" type="text/javascript"></script>
	<script type="text/javascript">
	(function() {
		var mapAddress = <?php echo wp_json_encode( esc_attr( $map_address ) ); ?>;
		var mapBalloon = <?php echo wp_json_encode( esc_attr( $map_balloon ) ); ?>;

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
	$consult_bg       = get_field( 'product_consult_bg', 'options' );
	$consult_title    = get_field( 'product_consult_title', 'options' ) ?: 'Остались вопросы?';
	$consult_icon     = get_field( 'product_consult_icon', 'options' );
	$consult_text     = get_field( 'product_consult_text', 'options' ) ?: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.';
	$consult_btn_text = get_field( 'product_consult_btn_text', 'options' ) ?: 'Получить консультацию';
	$consult_btn_link = get_field( 'product_consult_btn_link', 'options' ) ?: '#callback';
	$consult_features = get_field( 'product_consult_features', 'options' );
	?>

	<div class="relative py-12 lg:py-20" <?php if ( $consult_bg ) : ?>style="background-image: url('<?php echo esc_url( $consult_bg ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>

		<div class="relative max-w-[1200px] mx-auto">
			<div class="flex flex-col md:flex-row justify-between">

				<!-- Left: CTA -->
				<div>
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php echo esc_html( $consult_title ); ?>
					</h2>

					<?php if ( $consult_icon ) : ?>
						<div class="flex items-center gap-3 mb-6">
							<img src="<?php echo esc_url( $consult_icon ); ?>" alt="" class="">
						</div>
					<?php endif; ?>

					<p class="text-gray-600 font-body leading-[1.4] mb-10 max-w-md">
						<?php echo esc_html( $consult_text ); ?>
					</p>

					<a
						href="<?php echo esc_url( $consult_btn_link ); ?>"
						data-popup="consult"
						class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 lg:w-[344px] text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body"
					>
						<?php echo esc_html( $consult_btn_text ); ?>
					<img src="<?php echo get_template_directory_uri(); ?>/img/arr2.svg" alt="arrow" class="" />
					</a>
				</div>

				<!-- Right: Features -->
				<?php if ( ! empty( $consult_features ) ) : ?>
					<div class="space-y-10">
						<?php foreach ( $consult_features as $feature ) :
							$feat_icon  = $feature['product_consult_feat_icon'] ?? '';
							$feat_title = $feature['product_consult_feat_title'] ?? '';
							$feat_desc  = $feature['product_consult_feat_desc'] ?? '';
							if ( ! $feat_title ) continue;
						?>
							<div class="flex items-center gap-4 lg:w-[390px]">
								<div class="w-16 h-16 rounded-full border border-[#860000] flex items-center justify-center shrink-0">
										<img src="<?php echo esc_url( $feat_icon ); ?>" alt="" class="w-8 h-8">
								</div>
								<div>
									<h3 class="font-manrope font-bold text-[#182028] text-lg mb-1"><?php echo esc_html( $feat_title ); ?></h3>
									<p class="text-gray-500 font-body text-sm leading-[1.4] max-w-[250px]"><?php echo esc_html( $feat_desc ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

</main>

<?php get_footer(); ?>
