<?php
/**
 * Template Name: Examples 
 * The template for examples page.
 *
 * @package BelGranit
 */

$page = get_page_by_path( 'examples' );
$page_id = $page ? $page->ID : 0;

$hero_bg        = $page_id ? get_field( 'examples_hero_bg', $page_id ) : '';
$hero_title     = $page_id ? ( get_field( 'examples_hero_title', $page_id ) ?: 'Примеры работ' ) : 'Примеры работ';
$hero_subtitle  = $page_id ? get_field( 'examples_hero_subtitle', $page_id ) : '';

// Получаем текущую категорию из GET-параметра
$current_category = isset( $_GET['work_cat'] ) ? urldecode( sanitize_text_field( $_GET['work_cat'] ) ) : '';

// Пагинация: 20 карточек на странице
$per_page     = 20;
$current_page = max( 1, get_query_var( 'paged' ) );
$offset       = ( $current_page - 1 ) * $per_page;

// Аргументы запроса
$args = array(
	'post_type'      => 'work_example',
	'posts_per_page' => $per_page,
	'paged'          => $current_page,
	'post_status'    => 'publish',
);

// Фильтрация по категории
if ( $current_category && $current_category !== 'all' ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'work_category',
			'field'    => 'name',
			'terms'    => $current_category,
		),
	);
}

$works_query = new WP_Query( $args );

// Получаем все категории работ
$work_categories = get_terms( array(
	'taxonomy'   => 'work_category',
	'hide_empty' => false,
) );

// Базовый URL для пагинации
$base_url = get_permalink();
if ( $current_category ) {
	$base_url = add_query_arg( 'work_cat', $current_category, $base_url );
}
?>

<?php get_header(); ?>
<?php if ( ! headers_sent() ) {
	header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
	header( 'Pragma: no-cache' );
	header( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
} ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery.min.css">

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

	<!-- Фильтры и карточки работ -->
	<section class="py-10">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-0">

			<!-- Кнопки фильтрации -->
			<?php
			$filter_order = array( 'Памятники', 'Благоустройство', 'Ограды', 'Оформление' );
			$sorted_categories = array();
			foreach ( $filter_order as $name ) {
				foreach ( $work_categories as $cat ) {
					if ( $cat->name === $name ) {
						$sorted_categories[] = $cat;
						break;
					}
				}
			}
			?>
			<?php if ( ! empty( $sorted_categories ) ) : ?>
				<div class="flex flex-wrap gap-3 mb-10" id="filter-buttons">
					<a href="<?php echo esc_url( home_url( '/examples/' ) ); ?>"
					   class="px-4 py-3 rounded-[6px] font-body text-sm font-light transition-all filter-btn <?php echo empty( $current_category ) ? 'text-white' : 'bg-gray-100 text-[#272727] hover:bg-gray-200'; ?>"
					   <?php echo empty( $current_category ) ? 'style="background-color:#860000;"' : ''; ?>
					   data-category="">
						Все работы
					</a>
					<?php foreach ( $sorted_categories as $category ) : ?>
						<?php $decoded_slug = urldecode( $category->slug ); ?>
						<a href="<?php echo esc_url( home_url( '/examples/?work_cat=' . $decoded_slug ) ); ?>"
						   class="px-4 py-3 rounded-[6px] font-body text-sm font-light transition-all filter-btn <?php echo ( $current_category === $decoded_slug ) ? 'text-white' : 'bg-gray-100 text-[#272727] hover:bg-gray-200'; ?>"
						   <?php echo ( $current_category === $decoded_slug ) ? 'style="background-color:#860000;"' : ''; ?>
						   data-category="<?php echo esc_attr( $decoded_slug ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<!-- Карточки работ -->
			<?php if ( $works_query->have_posts() ) : ?>
				<div id="work-gallery" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
					<?php while ( $works_query->have_posts() ) : $works_query->the_post(); ?>
						<?php
						$work_id    = get_the_ID();
						$work_image = get_the_post_thumbnail_url( $work_id, 'large' );
						?>
						<?php if ( $work_image ) : ?>
							<a href="<?php echo esc_url( $work_image ); ?>"
							   class="block overflow-hidden rounded-lg aspect-square group work-item"
							   data-src="<?php echo esc_url( $work_image ); ?>">
								<img src="<?php echo esc_url( $work_image ); ?>"
								     alt="<?php echo esc_attr( get_the_title() ); ?>"
								     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
							</a>
						<?php endif; ?>
					<?php endwhile; ?>
				</div>

				<!-- Пагинация -->
				<?php
				$total_pages = $works_query->max_num_pages;
				if ( $total_pages > 1 ) :
				?>
					<div class="flex justify-center items-center gap-3 mt-7">
						<?php
						$range = 2;
						$start = max( 1, $current_page - $range );
						$end   = min( $total_pages, $current_page + $range );
						?>

						<!-- Previous -->
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

						<!-- Next -->
						<?php if ( $current_page < $total_pages ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', $current_page + 1, $base_url ) ); ?>" class="flex items-center justify-center font-body text-[16px] text-gray-900 hover:text-[#860000] transition-colors">
								<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<div class="text-center py-16">
					<p class="font-manrope text-lg text-[#4c4c4c]">Примеры работ пока не добавлены.</p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div>
	</section>
				<!-- Consultation Section -->
		<?php
		$consult_bg       = $page_id ? get_field( 'examples_consult_bg', $page_id ) : '';
		$consult_title    = $page_id ? ( get_field( 'examples_consult_title', $page_id ) ?: 'Остались вопросы?' ) : 'Остались вопросы?';
		$consult_icon     = $page_id ? get_field( 'examples_consult_icon', $page_id ) : '';
		$consult_text     = $page_id ? ( get_field( 'examples_consult_text', $page_id ) ?: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.' ) : 'Оставьте заявку. Менеджер перезвонит в ближайшее время.';
		$consult_btn_text = $page_id ? ( get_field( 'examples_consult_btn_text', $page_id ) ?: 'Получить консультацию' ) : 'Получить консультацию';
		$consult_btn_link = $page_id ? ( get_field( 'examples_consult_btn_link', $page_id ) ?: '#callback' ) : '#callback';
		$consult_features = $page_id ? get_field( 'examples_consult_features', $page_id ) : array();
		?>

		<div class="relative py-12 lg:py-20 mt-10 lg:mt-14" <?php if ( $consult_bg ) : ?>style="background-image: url('<?php echo esc_url( $consult_bg ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>

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
							$feat_icon  = $feature['examples_consult_feat_icon'] ?? '';
							$feat_title = $feature['examples_consult_feat_title'] ?? '';
							$feat_desc  = $feature['examples_consult_feat_desc'] ?? '';
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/lightgallery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var gallery = document.getElementById('work-gallery');
    if (gallery) {
        lightGallery(gallery, {
            selector: '.work-item',
            download: false,
            zoom: true,
            animateScale: true
        });
    }

    var urlParams = new URLSearchParams(window.location.search);
    var activeCat = urlParams.get('work_cat') || '';
    var buttons = document.querySelectorAll('#filter-buttons .filter-btn');
    buttons.forEach(function(btn) {
        if (btn.getAttribute('data-category') === activeCat) {
            btn.style.backgroundColor = '#860000';
            btn.style.color = '#ffffff';
        }
    });
});
</script>

<?php get_footer(); ?>
