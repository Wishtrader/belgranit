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
$rating_value   = get_field( 'reviews_rating_value' ) ?: '4,8';
$rating_label   = get_field( 'reviews_rating_label' ) ?: 'Средняя оценка нашей компании';
$cta_text       = get_field( 'reviews_cta_text' ) ?: 'Смотреть все отзывы';
$cta_link       = get_field( 'reviews_cta_link' );
$cta_url        = $cta_link['url'] ?? '#';
$cta_target     = $cta_link['target'] ?? '_self';
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
		<?php if ( ! empty( $images ) ) : ?>
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
			<div class="swiper lg:hidden pl-2.5" id="reviews-swiper">
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
			</div>
		<?php endif; ?>
		<div class="mt-11 flex flex-col items-center gap-6 sm:flex-row sm:justify-center">
			<div class="text-center sm:text-left">
				<div class="flex items-center justify-center gap-2 sm:justify-start">
						<img src="<?php echo esc_url( $rating_image ); ?>" alt="" class="h-[31px] w-[130px]">
				</div>
				<p class="mt-3 text-base"><?php echo esc_html( $rating_value ); ?> — <?php echo esc_html( $rating_label ); ?></p>
			</div>
			<a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo esc_attr( $cta_target ); ?>" class="group inline-flex items-center gap-3 rounded-md border border-[#860000] px-8 py-4 text-base font-semibold uppercase text-[#860000] transition hover:bg-[#650D10] hover:text-white">
				<?php echo esc_html( $cta_text ); ?> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/arrow1.svg" alt="arrow" class="transition group-hover:brightness-0 group-hover:invert" />
			</a>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var swiperEl = document.getElementById('reviews-swiper');
	if (!swiperEl) return;

	new Swiper('#reviews-swiper', {
		slidesPerView: 1.2,
		spaceBetween: 6,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
	});
});
</script>
