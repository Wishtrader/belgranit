<?php
/**
 * Portfolio Section
 *
 * @package BelGranit
 */

$heading     = get_field( 'portfolio_heading' ) ?: 'Наши работы';
$description = get_field( 'portfolio_description' ) ?: 'Каждое изделие — это уважение к памяти и внимание к деталям';
$cta_text    = get_field( 'portfolio_cta_text' ) ?: 'Смотреть все работы';
$cta_link    = get_field( 'portfolio_cta_link' );
$cta_url     = $cta_link['url'] ?? home_url( '/examples/' );
$cta_target  = $cta_link['target'] ?? '_self';

$works_query = new WP_Query( array(
	'post_type'      => 'work_example',
	'posts_per_page' => 8,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$works = array();
if ( $works_query->have_posts() ) :
	while ( $works_query->have_posts() ) : $works_query->the_post();
		$img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		if ( $img ) {
			$works[] = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'image' => $img,
			);
		}
	endwhile;
	wp_reset_postdata();
endif;

$total_works = count( $works );
?>
<!-- Portfolio -->
<section id="works" class="mx-auto max-w-[1200px] px-4 py-16 lg:py-20 xl:px-0" x-data="portfolioSlider()">
	<div class="mb-11 flex flex-col items-center gap-5 text-center">
		<h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		<p class="text-base text-charcoal"><?php echo esc_html( $description ); ?></p>
	</div>

	<?php if ( $total_works > 0 ) : ?>
		<!-- Desktop: Grid -->
		<div class="hidden lg:grid gap-6 lg:grid-cols-4">
			<?php foreach ( $works as $work ) : ?>
				<button type="button" @click="openLightbox('<?php echo esc_url( $work['image'] ); ?>', '<?php echo esc_attr( $work['title'] ); ?>')" class="block overflow-hidden rounded-md group cursor-pointer" style="width: 282px; height: 282px;">
					<img src="<?php echo esc_url( $work['image'] ); ?>"
					     alt="<?php echo esc_attr( $work['title'] ); ?>"
					     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
				</button>
			<?php endforeach; ?>
		</div>

		<!-- Mobile: Slider -->
		<div class="lg:hidden overflow-hidden" x-ref="mobileSlider"
			@touchstart="touchStart($event)"
			@touchmove="touchMove($event)"
			@touchend="touchEnd($event)">
			<div class="flex transition-transform duration-500 ease-in-out"
				:style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
				<?php foreach ( $works as $work ) : ?>
					<div class="w-full flex-shrink-0 px-3">
						<button type="button" @click="openLightbox('<?php echo esc_url( $work['image'] ); ?>', '<?php echo esc_attr( $work['title'] ); ?>')" class="block overflow-hidden rounded-md group mx-auto cursor-pointer" style="width: 282px; height: 282px;">
							<img src="<?php echo esc_url( $work['image'] ); ?>"
							     alt="<?php echo esc_attr( $work['title'] ); ?>"
							     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Mobile Pagination Dots -->
		<?php if ( $total_works > 1 ) : ?>
			<div class="lg:hidden flex justify-center gap-2 mt-6">
				<?php for ( $i = 0; $i < $total_works; $i++ ) : ?>
					<button type="button"
						class="w-2.5 h-2.5 rounded-full transition-colors"
						:class="currentSlide === <?php echo $i; ?> ? 'bg-[#860000]' : 'bg-gray-300'"
						@click="currentSlide = <?php echo $i; ?>">
					</button>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="mt-11 flex justify-center">
		<a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo esc_attr( $cta_target ); ?>" class="inline-flex items-center gap-3 rounded-md border border-[#860000] px-8 py-4 text-base font-semibold uppercase text-[#860000] transition hover:bg-[#860000] hover:text-white">
			<?php echo esc_html( $cta_text ); ?> →
		</a>
	</div>

	<!-- Lightbox -->
	<div x-show="lightboxOpen"
		x-transition:enter="transition ease-out duration-200"
		x-transition:enter-start="opacity-0"
		x-transition:enter-end="opacity-100"
		x-transition:leave="transition ease-in duration-150"
		x-transition:leave-start="opacity-100"
		x-transition:leave-end="opacity-0"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
		@click.self="lightboxOpen = false"
		@keydown.escape.window="lightboxOpen = false"
		style="display: none;">
		<button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 text-white text-3xl leading-none hover:text-gray-300 transition-colors">&times;</button>
		<img :src="lightboxImage" :alt="lightboxTitle" class="max-h-[90vh] max-w-[90vw] rounded-md object-contain">
	</div>
</section>

<script>
function portfolioSlider() {
	return {
		currentSlide: 0,
		touchStartX: 0,
		touchEndX: 0,
		lightboxOpen: false,
		lightboxImage: '',
		lightboxTitle: '',

		openLightbox(image, title) {
			this.lightboxImage = image;
			this.lightboxTitle = title;
			this.lightboxOpen = true;
		},

		touchStart(e) {
			this.touchStartX = e.changedTouches[0].screenX;
		},

		touchMove(e) {
			this.touchEndX = e.changedTouches[0].screenX;
		},

		touchEnd(e) {
			this.touchEndX = e.changedTouches[0].screenX;
			const diff = this.touchStartX - this.touchEndX;
			const maxSlide = this.$refs.mobileSlider.children.length - 1;

			if (Math.abs(diff) > 50) {
				if (diff > 0 && this.currentSlide < maxSlide) {
					this.currentSlide++;
				} else if (diff < 0 && this.currentSlide > 0) {
					this.currentSlide--;
				}
			}
		}
	};
}
</script>
