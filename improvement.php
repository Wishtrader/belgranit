<?php
/**
 * Template Name: Improvement 
 * The template for improvement page.
 *
 * @package BelGranit
 */

$page = get_page_by_path( 'improvement' );
$page_id = $page ? $page->ID : 0;

$hero_bg        = $page_id ? get_field( 'improvement_hero_bg', $page_id ) : '';
$hero_title     = $page_id ? ( get_field( 'improvement_hero_title', $page_id ) ?: 'Благоустройство' ) : 'Благоустройство';
$hero_subtitle  = $page_id ? get_field( 'improvement_hero_subtitle', $page_id ) : '';

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="relative h-[200px] sm:h-[240px] lg:h-[377px] overflow-hidden mt-[72px] lg:mt-0 bg-cover bg-center bg-no-repeat" <?php if ( $hero_bg ) : ?>style="background-image: url('<?php echo esc_url( $hero_bg ); ?>');"<?php endif; ?>>
		<div class="relative z-10 h-full max-w-[1200px] mx-auto flex flex-col justify-center">
			<nav class="font-body text-[12px] text-[#606060] mb-10 flex flex-wrap items-center gap-x-1 mt-[60px] lg:mt-[136px]">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-black transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-[#606060]"><?php echo esc_html( $hero_title ); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] font-bold text-[#272727] uppercase leading-tight mb-2 max-w-[680px]">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="font-manrope font-normal text-lg text-[#4c4c4c]">
					<?php echo esc_html( $hero_subtitle ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Services Section -->
	<?php
	$services = $page_id ? get_field( 'improvement_services', $page_id ) : array();

	if ( ! empty( $services ) ) :
	?>
	<section class="py-12 lg:py-20">
		<div class="mx-auto max-w-[1200px] px-4 xl:px-0 space-y-8">
			<?php foreach ( $services as $index => $block ) :
				$svc_title    = $block['improvement_svc_title'] ?? '';
				$svc_subtitle = $block['improvement_svc_subtitle'] ?? '';
				$svc_image    = $block['improvement_svc_image'] ?? '';
				$svc_btn_text = $block['improvement_svc_btn_text'] ?: 'Рассчитать стоимость';
				$svc_items    = $block['improvement_svc_items'] ?? array();

				if ( ! $svc_title ) {
					continue;
				}

				$is_odd = ( $index % 2 === 0 );
			?>
			<div class="rounded-[8px] bg-[#F5F4F3] p-6 lg:p-10">
				<div class="flex flex-col<?php echo $is_odd ? ' lg:flex-row' : ' lg:flex-row-reverse'; ?> gap-8 lg:gap-12">

					<!-- Image Column -->
					<div class="w-full lg:w-[400px] shrink-0 flex flex-col gap-6">
						<?php if ( $svc_image ) : ?>
							<img src="<?php echo esc_url( $svc_image ); ?>" alt="<?php echo esc_attr( $svc_title ); ?>" class="w-full rounded-[6px] object-cover h-[280px] lg:h-[320px]">
						<?php endif; ?>

						<a href="#callback" class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body w-full">
							<?php echo esc_html( $svc_btn_text ); ?>
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/arr2.svg" alt="" class="w-4 h-4">
						</a>
					</div>

					<!-- Content Column -->
					<div class="flex-1">
						<div class="mb-4 flex items-start gap-3">
							<span class="mt-2 block h-2 w-2 shrink-0 rotate-45 bg-[#860000]"></span>
							<div>
								<h2 class="font-playfair text-[22px] sm:text-[26px] lg:text-[28px] font-bold text-ink leading-[1.3]">
									<?php echo esc_html( $svc_title ); ?>
								</h2>
								<?php if ( $svc_subtitle ) : ?>
									<p class="mt-3 text-sm text-[#4c4c4c] font-body leading-[1.5]">
										<?php echo esc_html( $svc_subtitle ); ?>
									</p>
								<?php endif; ?>
								<div class="mt-5 h-px w-12 bg-[#860000]"></div>
							</div>
						</div>

						<?php if ( ! empty( $svc_items ) ) : ?>
							<div class="mt-6 space-y-0">
								<?php foreach ( $svc_items as $item ) :
									$item_name  = $item['improvement_svc_item_name'] ?? '';
									$item_price = $item['improvement_svc_item_price'] ?? '';
									if ( ! $item_name ) continue;
								?>
									<div class="flex items-center justify-between border-b border-gray-200 py-4">
										<span class="text-[14px] text-[#272727] font-body"><?php echo esc_html( $item_name ); ?></span>
										<?php if ( $item_price ) : ?>
											<span class="ml-4 shrink-0 text-[14px] font-bold text-[#272727] font-body"><?php echo esc_html( $item_price ); ?> <span class="font-normal">BYN</span></span>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- Works Slider Section -->
	<?php
	$works_title = $page_id ? ( get_field( 'improvement_works_title', $page_id ) ?: 'Примеры работ по благоустройству' ) : 'Примеры работ по благоустройству';
	$works_icon  = $page_id ? get_field( 'improvement_works_icon', $page_id ) : '';

	$works_query = new WP_Query( array(
		'post_type'      => 'work_example',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	$all_works = array();
	if ( $works_query->have_posts() ) :
		while ( $works_query->have_posts() ) : $works_query->the_post();
			$img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			$terms = get_the_terms( get_the_ID(), 'work_category' );
			$has_category = false;
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( $term->name === 'Благоустройство' ) {
						$has_category = true;
						break;
					}
				}
			}
			if ( $has_category ) {
				$all_works[] = array(
					'id'    => get_the_ID(),
					'title' => get_the_title(),
					'image' => $img,
				);
			}
		endwhile;
		wp_reset_postdata();
	endif;

	$total_works = count( $all_works );

	if ( $total_works > 0 ) :
		$per_slide = 4;
		$total_slides = ceil( $total_works / $per_slide );
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery.min.css">
	<section class="py-12 lg:py-20" x-data="worksSlider()">
		<div class="mx-auto max-w-[1200px] px-4 xl:px-0">
			<div class="mb-11 flex flex-col items-center gap-5">
				<h2 class="font-playfair text-center text-3xl text-ink lg:text-4xl"><?php echo esc_html( $works_title ); ?></h2>
				<?php if ( $works_icon ) : ?>
					<img src="<?php echo esc_url( $works_icon ); ?>" alt="" class="h-[22px] w-[114px]">
				<?php else : ?>
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
				<?php endif; ?>
			</div>

			<!-- Slider -->
			<div class="overflow-hidden" x-ref="sliderContainer"
				@touchstart="touchStart($event)"
				@touchmove="touchMove($event)"
				@touchend="touchEnd($event)">
				<div class="flex transition-transform duration-500 ease-in-out"
					:style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
					<?php for ( $s = 0; $s < $total_slides; $s++ ) : ?>
						<div class="w-full flex-shrink-0">
							<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
								<?php for ( $i = $s * $per_slide; $i < min( ( $s + 1 ) * $per_slide, $total_works ); $i++ ) :
									$work = $all_works[ $i ];
								?>
									<?php if ( $work['image'] ) : ?>
										<a href="<?php echo esc_url( $work['image'] ); ?>"
										   class="block overflow-hidden rounded-lg aspect-square group work-item-slider"
										   data-src="<?php echo esc_url( $work['image'] ); ?>">
											<img src="<?php echo esc_url( $work['image'] ); ?>"
											     alt="<?php echo esc_attr( $work['title'] ); ?>"
											     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
										</a>
									<?php else : ?>
										<div class="block overflow-hidden rounded-lg aspect-square bg-gray-100 flex items-center justify-center p-4">
											<span class="text-sm text-gray-400 text-center font-body"><?php echo esc_html( $work['title'] ); ?></span>
										</div>
									<?php endif; ?>
								<?php endfor; ?>
							</div>
						</div>
					<?php endfor; ?>
				</div>
			</div>

			<!-- Pagination Dots -->
			<?php if ( $total_slides > 1 ) : ?>
				<div class="flex justify-center gap-2 mt-8">
					<?php for ( $i = 0; $i < $total_slides; $i++ ) : ?>
						<button type="button"
							class="w-2.5 h-2.5 rounded-full transition-colors"
							:class="currentSlide === <?php echo $i; ?> ? 'bg-[#860000]' : 'bg-gray-300'"
							@click="currentSlide = <?php echo $i; ?>">
						</button>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

			<!-- View All Button -->
			<div class="mt-10 text-center">
				<a href="<?php echo esc_url( home_url( '/examples/' ) ); ?>"
				   class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body">
					Смотреть все работы
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/arr2.svg" alt="" class="w-4 h-4">
				</a>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/lightgallery.min.js"></script>
		<script>
		function worksSlider() {
			return {
				currentSlide: 0,
				touchStartX: 0,
				touchEndX: 0,

				init() {
					setTimeout(() => {
						const container = this.$refs.sliderContainer;
						if (container) {
							lightGallery(container, {
								selector: '.work-item-slider',
								download: false,
								zoom: true,
								animateScale: true
							});
						}
					}, 100);
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
					const maxSlide = this.$refs.sliderContainer.querySelectorAll(':scope > div').length - 1;

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
	</section>
	<?php endif; ?>

	<!-- Content Section -->
	<?php
	$content_title   = $page_id ? ( get_field( 'improvement_content_title', $page_id ) ?: 'Благоустройство мест захоронения' ) : 'Благоустройство мест захоронения';
	$content_text_1  = $page_id ? get_field( 'improvement_content_text_1', $page_id ) : '';
	$content_text_2  = $page_id ? get_field( 'improvement_content_text_2', $page_id ) : '';
	$content_text_3  = $page_id ? get_field( 'improvement_content_text_3', $page_id ) : '';
	?>
	<section class="py-12 lg:py-20">
		<div class="mx-auto max-w-[1200px] px-4 xl:px-0">
			<?php if ( $content_title ) : ?>
				<h2 class="font-playfair text-[22px] sm:text-[26px] lg:text-[28px] font-bold text-ink leading-[1.3] mb-6">
					<?php echo esc_html( $content_title ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $content_text_1 ) : ?>
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7] mb-5">
					<?php echo wp_kses_post( $content_text_1 ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $content_text_2 ) : ?>
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7] mb-5">
					<?php echo wp_kses_post( $content_text_2 ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $content_text_3 ) : ?>
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7]">
					<?php echo wp_kses_post( $content_text_3 ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

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



