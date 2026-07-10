<?php
/**
 * Template Name: Models
 * The template for models page.
 *
 * @package BelGranit
 */

$page = get_page_by_path( 'models' );
$page_id = $page ? $page->ID : 0;

$hero_bg        = $page_id ? get_field( 'models_hero_bg', $page_id ) : '';
$hero_title     = $page_id ? ( get_field( 'models_hero_title', $page_id ) ?: 'Модели' ) : 'Модели';
$hero_subtitle  = $page_id ? get_field( 'models_hero_subtitle', $page_id ) : '';

$current_category = isset( $_GET['model_cat'] ) ? urldecode( sanitize_text_field( $_GET['model_cat'] ) ) : '';

$per_page     = 20;
$current_page = max( 1, get_query_var( 'paged' ) );
$offset       = ( $current_page - 1 ) * $per_page;

$args = array(
	'post_type'      => 'model',
	'posts_per_page' => $per_page,
	'paged'          => $current_page,
	'post_status'    => 'publish',
);

if ( $current_category && $current_category !== 'all' ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'model_category',
			'field'    => 'name',
			'terms'    => $current_category,
		),
	);
}

$models_query = new WP_Query( $args );

$model_categories = get_terms( array(
	'taxonomy'   => 'model_category',
	'hide_empty' => false,
) );

$base_url = get_permalink();
if ( $current_category ) {
	$base_url = add_query_arg( 'model_cat', $current_category, $base_url );
}

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

	<!-- Models Catalog -->
	<section class="py-10">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-0">

			<!-- Filter Buttons -->
			<?php
			$default_cats = array( 'Одинарные памятники', 'Двойные памятники' );
			$sorted_categories = array();

			// "Все" button first
			$all_active = empty( $current_category ) || $current_category === 'all';
			?>
			<div class="flex flex-wrap gap-3 mb-10" id="filter-buttons">
				<a href="<?php echo esc_url( home_url( '/models/' ) ); ?>"
				   class="px-4 py-3 rounded-[6px] font-body text-sm font-light transition-all filter-btn <?php echo $all_active ? 'text-white' : 'bg-gray-100 text-[#272727] hover:bg-gray-200'; ?>"
				   <?php echo $all_active ? 'style="background-color:#860000;"' : ''; ?>
				   data-category="">
					Все
				</a>
				<?php
				// Sort: default categories first, then others
				$ordered_cats = array();
				foreach ( $default_cats as $def_name ) {
					foreach ( $model_categories as $cat ) {
						if ( $cat->name === $def_name ) {
							$ordered_cats[] = $cat;
							break;
						}
					}
				}
				foreach ( $model_categories as $cat ) {
					$found = false;
					foreach ( $ordered_cats as $oc ) {
						if ( $oc->term_id === $cat->term_id ) {
							$found = true;
							break;
						}
					}
					if ( ! $found ) {
						$ordered_cats[] = $cat;
					}
				}
				?>
				<?php foreach ( $ordered_cats as $category ) : ?>
					<?php $decoded_name = urldecode( $category->name ); ?>
					<a href="<?php echo esc_url( home_url( '/models/?model_cat=' . rawurlencode( $category->name ) ) ); ?>"
					   class="px-4 py-3 rounded-[6px] font-body text-sm font-light transition-all filter-btn <?php echo ( $current_category === $category->name ) ? 'text-white' : 'bg-gray-100 text-[#272727] hover:bg-gray-200'; ?>"
					   <?php echo ( $current_category === $category->name ) ? 'style="background-color:#860000;"' : ''; ?>
					   data-category="<?php echo esc_attr( $category->name ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>

			<!-- Models Grid -->
			<?php if ( $models_query->have_posts() ) : ?>
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
					<?php while ( $models_query->have_posts() ) : $models_query->the_post();
						$model_id    = get_the_ID();
						$model_image = get_the_post_thumbnail_url( $model_id, 'large' );
						$model_title = get_the_title();
					?>
						<article class="group block bg-white rounded-[6px] border border-gray-100 overflow-hidden shadow-lg">
							<div class="overflow-hidden">
								<?php if ( $model_image ) : ?>
									<img src="<?php echo esc_url( $model_image ); ?>"
									     alt="<?php echo esc_attr( $model_title ); ?>"
									     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
								<?php else : ?>
									<div class="w-full h-[240px] bg-gray-100 flex items-center justify-center">
										<span class="text-gray-400 text-sm font-body">Нет изображения</span>
									</div>
								<?php endif; ?>
							</div>
							<div class="p-4 bg-[#F5F4F3]">
								<h3 class="font-manrope text-lg text-ink mb-3 leading-[1.2] min-h-[40px]">
									<?php echo esc_html( $model_title ); ?>
								</h3>
								<div class="h-px bg-[#860000]/30 w-[46px] mb-3"></div>
								<a href="#callback" class="inline-flex items-center gap-3 font-body text-[16px] font-normal text-[#860000] hover:opacity-80 transition-opacity">
									Выбрать как основу
									<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/arr.svg" alt="" class="w-4 h-4">
								</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<?php
				$total_pages = $models_query->max_num_pages;
				if ( $total_pages > 1 ) :
				?>
					<div class="flex justify-center items-center gap-3 mt-10">
						<?php
						$range = 2;
						$start = max( 1, $current_page - $range );
						$end   = min( $total_pages, $current_page + $range );
						?>
						<?php if ( $current_page > 1 ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', $current_page - 1, $base_url ) ); ?>" class="flex items-center justify-center font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
							</a>
						<?php endif; ?>

						<?php if ( $start > 1 ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', 1, $base_url ) ); ?>" class="flex items-center justify-center w-[40px] h-[40px] rounded-[6px] font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors">1</a>
							<?php if ( $start > 2 ) : ?>
								<span class="text-gray-900 font-body text-[16px]">...</span>
							<?php endif; ?>
						<?php endif; ?>

						<?php for ( $i = $start; $i <= $end; $i++ ) : ?>
							<?php if ( $i === $current_page ) : ?>
								<span class="flex items-center justify-center w-[40px] h-[40px] rounded-[6px] bg-[#860000] text-white font-body text-[16px] font-semibold"><?php echo esc_html( $i ); ?></span>
							<?php else : ?>
								<a href="<?php echo esc_url( add_query_arg( 'paged', $i, $base_url ) ); ?>" class="flex items-center justify-center w-[40px] h-[40px] rounded-[6px] font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors"><?php echo esc_html( $i ); ?></a>
							<?php endif; ?>
						<?php endfor; ?>

						<?php if ( $end < $total_pages ) : ?>
							<?php if ( $end < $total_pages - 1 ) : ?>
								<span class="text-gray-900 font-body text-[16px]">...</span>
							<?php endif; ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', $total_pages, $base_url ) ); ?>" class="flex items-center justify-center w-[42px] h-[42px] rounded-[8px] font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors"><?php echo esc_html( $total_pages ); ?></a>
						<?php endif; ?>

						<?php if ( $current_page < $total_pages ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', $current_page + 1, $base_url ) ); ?>" class="flex items-center justify-center font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<div class="text-center py-16">
					<p class="font-manrope text-lg text-[#4c4c4c]">Модели пока не добавлены.</p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div>
	</section>

	<!-- Content Section -->
	<?php
	$content_title   = $page_id ? ( get_field( 'models_content_title', $page_id ) ?: 'Модели памятников' ) : 'Модели памятников';
	$content_text_1  = $page_id ? get_field( 'models_content_text_1', $page_id ) : '';
	$content_text_2  = $page_id ? get_field( 'models_content_text_2', $page_id ) : '';
	$content_text_3  = $page_id ? get_field( 'models_content_text_3', $page_id ) : '';
	$content_text_4  = $page_id ? get_field( 'models_content_text_4', $page_id ) : '';
	$content_text_5  = $page_id ? get_field( 'models_content_text_5', $page_id ) : '';
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
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7] mb-5">
					<?php echo wp_kses_post( $content_text_3 ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $content_text_4 ) : ?>
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7] mb-5">
					<?php echo wp_kses_post( $content_text_4 ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $content_text_5 ) : ?>
				<p class="text-[14px] text-[#4c4c4c] font-body leading-[1.7]">
					<?php echo wp_kses_post( $content_text_5 ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

</main>

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

	<div class="relative py-12 lg:py-20 mt-10 lg:mt-[76px] px-[10px] text-center lg:text-start" <?php if ( $consult_bg ) : ?>style="background-image: url('<?php echo esc_url( $consult_bg ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>

		<div class="relative max-w-[1200px] mx-auto">
			<div class="flex flex-col md:flex-row justify-between">

				<!-- Left: CTA -->
				<div>
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php echo esc_html( $consult_title ); ?>
					</h2>

					<?php if ( $consult_icon ) : ?>
						<div class="flex items-center justify-center lg:justify-start gap-3 mb-6">
							<img src="<?php echo esc_url( $consult_icon ); ?>" alt="" class="">
						</div>
					<?php endif; ?>

					<p class="text-gray-600 font-body text-center lg:text-left leading-[1.2] mb-10 max-w-md">
						<?php echo esc_html( $consult_text ); ?>
					</p>

					<a
						href="<?php echo esc_url( $consult_btn_link ); ?>"
						data-popup="consult"
						class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 lg:w-[344px] w-full text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body mb-8 lg:mb-0"
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
								<h3 class="font-manrope text-left font-bold text-[#182028] text-lg mb-1"><?php echo esc_html( $feat_title ); ?></h3>
								<p class="text-gray-500 text-left font-body text-sm leading-[1.4] max-w-[250px]"><?php echo esc_html( $feat_desc ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

<?php get_footer(); ?>
