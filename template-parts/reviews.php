<?php
/**
 * Reviews Section
 *
 * @package BelGranit
 */

$heading        = get_field( 'reviews_heading' ) ?: 'Отзывы клиентов';
$description    = get_field( 'reviews_description' ) ?: 'Нам доверяют память о близких — смотрите реальные отзывы на независимых площадках';
$images         = get_field( 'reviews_images' );
$rating_image   = get_field( 'reviews_rating_platform' );
$yandex_rating  = belgranit_get_yandex_rating();
$rating_value   = belgranit_format_yandex_rating( $yandex_rating ) ?: ( get_field( 'reviews_rating_value' ) ?: '4,8' );
$rating_label   = get_field( 'reviews_rating_label' ) ?: 'Средняя оценка нашей компании';
$cta_text       = get_field( 'reviews_cta_text' ) ?: 'Смотреть все отзывы';
$cta_link       = get_field( 'reviews_cta_link' );
$cta_url        = $cta_link['url'] ?? '';

if ( ! $cta_url && defined( 'BELGRANIT_YANDEX_PROFILE_URL' ) ) {
	$cta_url = BELGRANIT_YANDEX_PROFILE_URL;
}

// Последние 9 отзывов Яндекса, по одной карточке на слайд.
$reviews = belgranit_get_yandex_reviews( 9 );
?>
<!-- Reviews -->
<section class="relative overflow-hidden bg-muted py-16 lg:py-20">
	<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/bg-texture.png" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
	<div class="relative mx-auto max-w-[1200px] px-4 xl:px-0">
		<div class="mb-11 flex flex-col items-center gap-5 text-center">
			<h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
			<p class="text-base text-[#272727]"><?php echo esc_html( $description ); ?></p>
		</div>
		<?php if ( ! empty( $reviews ) ) : ?>
			<!-- Reviews slider: 1 card per slide on mobile, 3 per view on desktop -->
			<div class="swiper" id="reviews-swiper" data-slides-per-view="1" data-slides-per-group="1" data-space-between="16" data-md-slides-per-view="3" data-md-slides-per-group="3">
				<div class="swiper-wrapper">
					<?php foreach ( $reviews as $review ) : ?>
						<div class="swiper-slide h-auto flex">
							<article class="flex h-full w-full flex-col rounded-[6px] bg-white p-5 shadow-[0_2px_16px_rgba(24,32,40,0.06)] ring-1 ring-black/5">
								<div class="flex items-start gap-3">
									<?php if ( $review['avatar'] ) : ?>
										<img src="<?php echo esc_url( $review['avatar'] ); ?>" alt="" class="h-11 w-11 shrink-0 rounded-full bg-[#f5f4f3] object-cover" loading="lazy">
									<?php else : ?>
										<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#860000] text-sm font-semibold text-white"><?php echo esc_html( belgranit_review_initial( $review['name'] ) ); ?></span>
									<?php endif; ?>
									<div class="min-w-0 flex-1">
										<p class="truncate text-sm font-semibold text-ink"><?php echo esc_html( $review['name'] ); ?></p>
										<?php if ( $review['level'] ) : ?>
											<p class="truncate text-xs text-[#707070]"><?php echo esc_html( $review['level'] ); ?></p>
										<?php endif; ?>
									</div>
									<?php echo belgranit_review_stars( $review['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<p class="reviews-card__text mt-4 text-sm leading-relaxed text-[#272727]"><?php echo nl2br( esc_html( $review['text'] ) ); ?></p>
								<div class="mt-4 flex items-center justify-between gap-3 border-t border-black/5 pt-3 text-xs text-[#707070]">
									<time datetime="<?php echo esc_attr( gmdate( 'c', $review['timestamp'] ) ); ?>"><?php echo esc_html( $review['date'] ); ?></time>
									<a href="<?php echo esc_url( defined( 'BELGRANIT_YANDEX_PROFILE_URL' ) ? BELGRANIT_YANDEX_PROFILE_URL : '#' ); ?>" target="_blank" rel="noopener" class="shrink-0 transition hover:text-[#860000]">Яндекс Отзывы</a>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination" id="reviews-swiper-pagination"></div>
			</div>
		<?php elseif ( ! empty( $images ) ) : ?>
			<!-- Fallback: статические скриншоты отзывов (если Яндекс недоступен) -->
			<!-- Desktop: Grid -->
			<div class="hidden lg:grid gap-1 lg:grid-cols-<?php echo count( $images ) > 3 ? '3' : count( $images ); ?>">
				<?php foreach ( $images as $item ) :
					$image = $item['review_image'] ?? '';
					$alt   = $item['review_alt'] ?? 'Отзыв клиента';
				?>
					<?php if ( $image ) : ?>
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="rounded-[6px]">
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<!-- Mobile: Swiper Slider -->
			<div class="swiper lg:hidden pl-2.5" id="reviews-swiper" data-slides-per-view="1.2" data-space-between="6">
				<div class="swiper-wrapper">
					<?php foreach ( $images as $item ) :
						$image = $item['review_image'] ?? '';
						$alt   = $item['review_alt'] ?? 'Отзыв клиента';
					?>
						<?php if ( $image ) : ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="rounded-[6px]">
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination" id="reviews-swiper-pagination"></div>
			</div>
		<?php endif; ?>
		<div class="mt-11 flex flex-col items-center gap-6 sm:flex-row sm:justify-center">
			<div class="text-center sm:text-left">
				<div class="flex items-center justify-center gap-2 sm:justify-start">
						<img src="<?php echo esc_url( $rating_image ); ?>" alt="" class="h-[31px] w-[130px]">
				</div>
				<p class="mt-3 text-base"><?php echo esc_html( $rating_value ); ?> — <?php echo esc_html( $rating_label ); ?></p>
			</div>
			<a href="<?php echo esc_url( $cta_url ); ?>" target="_blank" rel="noopener" class="group inline-flex items-center gap-3 rounded-md border border-[#860000] px-8 py-4 text-base font-semibold uppercase text-[#860000] transition hover:bg-[#650D10] hover:text-white">
				<?php echo esc_html( $cta_text ); ?> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/arrow1.svg" alt="arrow" class="transition group-hover:brightness-0 group-hover:invert" />
			</a>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var swiperEl = document.getElementById('reviews-swiper');
	if (!swiperEl || typeof Swiper === 'undefined') return;

	var params = {
		slidesPerView: parseFloat(swiperEl.dataset.slidesPerView) || 1,
		slidesPerGroup: parseInt(swiperEl.dataset.slidesPerGroup, 10) || 1,
		spaceBetween: parseInt(swiperEl.dataset.spaceBetween, 10) || 16,
		watchOverflow: true,
		pagination: {
			el: '#reviews-swiper-pagination',
			clickable: true,
		},
	};

	var mdSlidesPerView = parseFloat(swiperEl.dataset.mdSlidesPerView);
	if (mdSlidesPerView) {
		params.breakpoints = {
			768: {
				slidesPerView: mdSlidesPerView,
				slidesPerGroup: parseInt(swiperEl.dataset.mdSlidesPerGroup, 10) || mdSlidesPerView,
			},
		};
	}

	new Swiper(swiperEl, params);
});
</script>
