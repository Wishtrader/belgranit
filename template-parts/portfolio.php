<?php

/**
 * Portfolio Section
 *
 * @package BelGranit
 */

$heading = get_field('portfolio_heading') ?: 'Наши работы';
$description = get_field('portfolio_description') ?: 'Каждое изделие — это уважение к памяти и внимание к деталям';
$cta_text = get_field('portfolio_cta_text') ?: 'Смотреть все работы';
$cta_link = get_field('portfolio_cta_link');
$cta_url = $cta_link['url'] ?? home_url('/examples/');
$cta_target = $cta_link['target'] ?? '_self';

$works_query = new WP_Query(array(
    'post_type' => 'work_example',
    'posts_per_page' => 8,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
));

$works = array();
if ($works_query->have_posts()):
    while ($works_query->have_posts()):
        $works_query->the_post();
        $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if ($img) {
            $works[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'image' => $img,
            );
        }
    endwhile;
    wp_reset_postdata();
endif;

$total_works = count($works);
?>
<!-- Portfolio -->
<section id="works" class="mx-auto max-w-[1200px] px-4 py-16 lg:py-20 xl:px-0" x-data="portfolioSlider()">

	<div class="mb-11 flex flex-col items-center gap-5 text-center">
		<h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo
    		esc_html($heading)
		; ?></h2>
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		<p class="text-base text-charcoal"><?php echo esc_html($description); ?></p>
	</div>

	<?php if ($total_works > 0): ?>
		<!-- Desktop: Grid -->
		<div class="hidden lg:grid gap-6 lg:grid-cols-4">
			<?php foreach ($works as $work): ?>
				<button type="button" @click="openLightbox('<?php echo esc_url($work['image']); ?>', '<?php echo
    				esc_attr($work['title'])
				; ?>')" class="block overflow-hidden rounded-md group cursor-pointer" style="width: 282px; height: 282px;">
					<img src="<?php echo esc_url($work['image']); ?>"
					     alt="<?php echo esc_attr($work['title']); ?>"
					     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
				</button>
			<?php endforeach; ?>
		</div>

		<!-- Mobile: Swiper Slider -->
		<div class="swiper lg:hidden pl-2.5" id="portfolio-swiper">
			<div class="swiper-wrapper">
				<?php foreach ($works as $work): ?>
					<div class="swiper-slide">
						<button type="button" @click="openLightbox('<?php echo esc_url($work['image']); ?>', '<?php echo
    						esc_attr($work['title'])
						; ?>')" class="block overflow-hidden rounded-md group cursor-pointer aspect-square">
							<img src="<?php echo esc_url($work['image']); ?>"
							     alt="<?php echo esc_attr($work['title']); ?>"
							     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="mt-11 flex justify-center">
		<a href="<?php echo esc_url($cta_url); ?>" target="<?php echo
    		esc_attr($cta_target)
		; ?>" class="inline-flex items-center justify-center w-full lg:max-w-[290px] gap-3 rounded-md border border-[#860000] px-4 py-4 text-base font-semibold uppercase text-[#860000] transition hover:bg-[#860000] hover:text-white">
			<?php echo esc_html($cta_text); ?>
		<svg width="17" height="12" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[17px] h-3 transition-colors"><path d="M16.5657 6.4568C16.8781 6.14438 16.8781 5.63785 16.5657 5.32543L11.4745 0.234259C11.1621 -0.0781603 10.6556 -0.0781603 10.3431 0.234259C10.0307 0.546679 10.0307 1.05321 10.3431 1.36563L14.8686 5.89111L10.3431 10.4166C10.0307 10.729 10.0307 11.2355 10.3431 11.548C10.6556 11.8604 11.1621 11.8604 11.4745 11.548L16.5657 6.4568ZM0 5.89111V6.69111H16V5.89111V5.09111H0V5.89111Z" fill="currentColor"/></svg>
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
		lightboxOpen: false,
		lightboxImage: '',
		lightboxTitle: '',

		openLightbox(image, title) {
			this.lightboxImage = image;
			this.lightboxTitle = title;
			this.lightboxOpen = true;
		}
	};
}

document.addEventListener('DOMContentLoaded', function() {
	var swiperEl = document.getElementById('portfolio-swiper');
	if (!swiperEl) return;

	new Swiper('#portfolio-swiper', {
		slidesPerView: 1.2,
		spaceBetween: 6,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
	});
});
</script>
