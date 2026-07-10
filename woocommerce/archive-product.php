<?php

/**
 * The template for displaying product archive pages (catalog)
 *
 * @package BelGranit
 */

get_header();

// Current category
$current_cat = get_queried_object();
$is_category = is_tax('product_cat') && $current_cat instanceof WP_Term;
$is_shop = is_shop();
$cat_name = $is_category ? $current_cat->name : '';
$cat_slug = $is_category ? $current_cat->slug : '';
$cat_id = $is_category ? $current_cat->term_id : 0;
$cat_parent = $is_category ? $current_cat->parent : 0;
$cat_desc = $is_category ? term_description($current_cat->term_id, 'product_cat') : '';

// Mapping: parent category slug => page slug for ACF fields
$catalog_sections = array(
    'pamyatniki' => array(
        'page' => 'monuments',
        'all_label' => 'Все памятники',
    ),
    'ogradi' => array(
        'page' => 'fences',
        'all_label' => 'Все ограды',
    ),
    'oformlenie' => array(
        'page' => 'decoration',
        'all_label' => 'Все оформление',
    ),
);

// Detect parent category: if current cat is a child, use its parent; if current cat IS the parent, use it
$detected_parent_slug = '';
if ($is_category) {
    if (isset($catalog_sections[$current_cat->slug])) {
        // Current category IS a parent category (e.g. viewing "Ограды" directly)
        $detected_parent_slug = $current_cat->slug;
    } elseif ($cat_parent > 0) {
        // Current category is a child — find which parent it belongs to
        $parent_term = get_term($cat_parent, 'product_cat');
        if ($parent_term && isset($catalog_sections[$parent_term->slug])) {
            $detected_parent_slug = $parent_term->slug;
        }
    }
} elseif ($is_shop) {
    // On the main shop page — default to памятники for backwards compatibility
    $detected_parent_slug = 'pamyatniki';
}

// Get parent category object and subcategories
$parent_cat = $detected_parent_slug ? get_term_by('slug', $detected_parent_slug, 'product_cat') : null;
$parent_cat_id = $parent_cat ? $parent_cat->term_id : 0;

// Page slug for ACF fields (hero & content — per section)
$page_slug = isset($catalog_sections[$detected_parent_slug])
    ? $catalog_sections[$detected_parent_slug]['page']
    : 'monuments';
$catalog_page = get_page_by_path($page_slug);
$catalog_page_id = $catalog_page ? $catalog_page->ID : 0;
$scf_title = $catalog_page_id ? get_field('catalog_hero_title', $catalog_page_id) : '';
$scf_image = $catalog_page_id ? get_field('catalog_hero_image', $catalog_page_id) : '';
$scf_content = $catalog_page_id ? get_field('catalog_content_blocks', $catalog_page_id) : array();

// Default name for the "Все X" sidebar link
$all_label = isset($catalog_sections[$detected_parent_slug])
    ? $catalog_sections[$detected_parent_slug]['all_label']
    : ($parent_cat ? $parent_cat->name : 'Каталог');

$cat_name = $is_category && $scf_title ? $scf_title : $cat_name;
$cat_image_id = $is_category ? get_term_meta($current_cat->term_id, 'thumbnail_id', true) : '';
$cat_image_url = $scf_image
    ? $scf_image
    : (
        $cat_image_id
            ? wp_get_attachment_image_url($cat_image_id, 'full')
            : get_template_directory_uri() . '/assets/hero.jpg'
    );
$product_count = wc_get_loop_prop('total') ? wc_get_loop_prop('total') : $current_cat->count;

// Build sidebar categories: only children relevant to the current parent
if ($parent_cat_id > 0) {
    // Viewing a subcategory — show siblings (children of the parent)
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $parent_cat_id,
    ));
} elseif ($parent_cat) {
    // Viewing a known parent category — show only its direct children
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => $parent_cat->term_id,
    ));
} else {
    // Unknown category or shop — show only direct children of all known parents
    $known_parents = array();
    foreach ($catalog_sections as $slug => $section) {
        $term = get_term_by('slug', $slug, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $known_parents[] = $term->term_id;
        }
    }
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent__in' => $known_parents,
    ));
}

// Sort parameters
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
$order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';

// Search
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="catalog-hero relative h-[300px] px-[10px] lg:px-5 lg:h-[334px] overflow-hidden mt-[72px] lg:mt-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo
    	esc_url($cat_image_url)
	; ?>');">
		<div class="relative z-10 h-full max-w-[1200px] mx-auto flex flex-col justify-center">
			<!-- Breadcrumbs -->
			<nav class="font-body text-[12px] text-[#606060] mb-10 flex flex-wrap items-start lg:items-center gap-x-1 lg:mt-[136px]">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-black transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-[#606060]"><?php echo esc_html($cat_name); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] font-bold text-[#272727] uppercase leading-tight mb-2">
				<?php echo esc_html($cat_name); ?>
			</h1>
			<p class="font-manrope font-normal text-lg text-[#4c4c4c]">
				Найдено <?php echo esc_html($product_count); ?> <?php echo
    				esc_html(belgranit_pluralize($product_count, 'единица товаров', 'единицы товаров', 'единиц товаров'))
				; ?>
			</p>
		</div>
	</section>

	<!-- Search & Sort Bar -->
	<section class="py-[32px] sticky top-[72px] z-30 bg-white px-[10px] lg:px-5">
		<div class="max-w-[1200px] mx-auto">
			<div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
				<!-- Search -->
				<form method="GET" class="relative" style="width: 992px; max-width: 100%;" action="<?php echo
    				esc_url(wc_get_page_permalink('shop'))
				; ?>">
					<?php if ($cat_slug): ?>
						<input type="hidden" name="product_cat" value="<?php echo esc_attr($cat_slug); ?>">
					<?php endif; ?>
					<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
					</svg>
					<input type="text" name="s" value="<?php echo
    					esc_attr($search_query)
					; ?>" placeholder="Поиск по названию..." class="search-input w-full h-12 pl-10 pr-4 rounded-[8px] border border-[1px] border-[#724246]/20 font-body text-[14px] text-ink focus:outline-none focus:border-brand transition-colors bg-[#F7F5F3]">
				</form>

				<!-- Sort -->
				<div class="flex items-center gap-2 shrink-0">
					<select class="sort-select h-12 w-full lg:w-[166px] border border-[1px] border-[#724246]/20 rounded-[6px] px-4 py-2.5 font-body text-[14px] text-ink bg-[#F7F5F3] cursor-pointer focus:outline-none focus:border-brand transition-colors" onchange="window.location.href=this.value">
						<?php

						$current_url = remove_query_arg(array('orderby', 'order', 'paged'));
						$sort_options = array(
    						'' => 'Сортировать по:',
    						'date' => 'По дате',
    						'title' => 'По названию',
    						'price' => 'По цене',
    						'popularity' => 'По популярности',
						);
						foreach ($sort_options as $key => $label) {
    						$sort_url = $key ? add_query_arg(array('orderby' => $key, 'order' => 'ASC'), $current_url) : $current_url;
    						$selected = $orderby === $key || !$key && !$orderby ? ' selected' : '';
    						echo '<option value="' . esc_url($sort_url) . '"' . $selected . '>' . esc_html($label) . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</section>

	<!-- Main Content -->
	<section class="py-8 sm:py-10 lg:py-0 px-[10px] lg:px-5">
		<div class="max-w-[1200px] mx-auto">
			<div class="flex flex-col lg:flex-row gap-6">

				<!-- Sidebar -->
				<aside class="lg:w-[282px] shrink-0 lg:sticky lg:top-[184px] lg:self-start">
					<!-- Categories Block -->
					<div class="bg-[#f7f6f5] rounded-[6px] border border-px border-[#724246]/20 p-5 -mb-3">
						<h3 class="font-manrope text-[22px] sm:text-[24px] text-[#272727] mb-6">Категории</h3>
						<ul class="space-y-2 list-none m-0 p-0 pb-6">
							<?php if ($parent_cat): ?>
								<li>
								<a href="<?php echo esc_url(get_term_link($parent_cat)); ?>"
								   class="cat-link block py-2 px-3 rounded-[6px] font-body text-[14px] font-medium <?php echo
    								   !$is_category || (int) $cat_id === (int) $parent_cat_id ? 'active' : 'text-gray-700 hover:text-brand'
								   ; ?>">
									<?php echo esc_html($all_label); ?>
								</a>
								</li>
							<?php endif; ?>
							<?php if (!is_wp_error($product_categories) && !empty($product_categories)): ?>
								<?php foreach ($product_categories as $cat):
    								$is_active = (int) $cat->term_id === (int) $cat_id;
    								?>
									<li>
										<a href="<?php echo esc_url(get_term_link($cat)); ?>"
										   class="cat-link block py-2 px-3 rounded-[6px] font-body text-[14px] font-medium <?php echo
    										   $is_active ? 'active' : 'text-gray-700 hover:text-brand'
										   ; ?>">
											<?php echo esc_html($cat->name); ?>
										</a>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>

					<!-- CTA Card -->
					<div class="bg-[#860000] flex flex-col gap-[16px] rounded-[16px] p-6 text-white">
						<h4 class="font-manrope text-lg mb-1 leading-tight">Не знаете, какой <?php

						$cta_words = array(
    						'ogradi' => 'ограду',
    						'oformlenie' => 'оформление',
						);
						echo esc_html($cta_words[$detected_parent_slug] ?? 'памятник');
						?> выбрать?</h4>
						<p class="font-body text-[14px] text-white/85 leading-[1.4]">Оставьте заявку. Мы поможем подобрать вариант с учетом бюджета, пожеланий и особенностей участка</p>
						<a href="#callback" data-popup="consult" class="block text-center bg-white text-[#272727] font-manrope font-semibold text-base py-2 rounded-[6px] hover:bg-gray-50 transition-colors">
							Получить консультацию
						</a>
					</div>
				</aside>

				<!-- Product Grid -->
				<div class="flex-1">
					<?php if (wc_get_loop_prop('total') > 0): ?>
						<div class="grid grid-cols-1 lg:grid-cols-3 gap-2 lg:gap-6 px-[10px] lg:px-5">
							<?php while (have_posts()):
    							the_post(); ?>
								<?php wc_get_template_part('content', 'product'); ?>
							<?php endwhile; ?>
						</div>

					<!-- Pagination -->
					<?php

					global $wp_query;
					$total_pages = $wp_query->max_num_pages;
					if ($total_pages > 1): ?>
						<div class="flex justify-center items-center gap-3 mt-10">
							<?php

							$current_page = max(1, get_query_var('paged'));
							$base_url = wc_get_page_permalink('shop');

							if ($cat_slug) {
    							$base_url = add_query_arg('product_cat', $cat_slug, $base_url);
							}
							if ($search_query) {
    							$base_url = add_query_arg('s', $search_query, $base_url);
							}
							if ($orderby) {
    							$base_url = add_query_arg('orderby', $orderby, $base_url);
							}

							// Previous
							if ($current_page > 1): ?>
								<a href="<?php echo
    								esc_url(add_query_arg('paged', $current_page - 1, $base_url))
								; ?>" class="flex items-center justify-center font-body text-[16px] text-gray-900 hover:text-brand transition-colors">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
								</a>
							<?php endif; ?>

							<?php

							$range = 2;
							$start = max(1, $current_page - $range);
							$end = min($total_pages, $current_page + $range);

							if ($start > 1): ?>
								<a href="<?php echo
    								esc_url(add_query_arg('paged', 1, $base_url))
								; ?>" class="flex items-center justify-center w-[42px] h-[42px] rounded-[8px] font-body text-[16px] text-gray-900 hover:text-brand transition-colors">1</a>
								<?php if ($start > 2): ?>
									<span class="text-gray-900 font-body text-[16px]">...</span>
								<?php endif; ?>
							<?php endif; ?>

							<?php for ($i = $start; $i <= $end; $i++): ?>
								<?php if ($i === $current_page): ?>
									<span class="flex items-center justify-center w-[42px] h-[42px] rounded-[8px] bg-[#860000] text-white font-body text-[16px] font-semibold"><?php echo
    									esc_html($i)
									; ?></span>
								<?php else: ?>
									<a href="<?php echo
    									esc_url(add_query_arg('paged', $i, $base_url))
									; ?>" class="flex items-center justify-center w-[42px] h-[42px] rounded-[8px] font-body text-[16px] text-gray-900 hover:text-brand transition-colors"><?php echo
    									esc_html($i)
									; ?></a>
								<?php endif; ?>
							<?php endfor; ?>

							<?php if ($end < $total_pages): ?>
								<?php if ($end < ($total_pages - 1)): ?>
									<span class="text-gray-900 font-body text-[16px]">...</span>
								<?php endif; ?>
								<a href="<?php echo
    								esc_url(add_query_arg('paged', $total_pages, $base_url))
								; ?>" class="flex items-center justify-center w-[42px] h-[42px] rounded-[8px] font-body text-[16px] text-gray-900 hover:text-brand transition-colors"><?php echo
    								esc_html($total_pages)
								; ?></a>
							<?php endif; ?>

							<!-- Next -->
							<?php if ($current_page < $total_pages): ?>
								<a href="<?php echo
    								esc_url(add_query_arg('paged', $current_page + 1, $base_url))
								; ?>" class="flex items-center justify-center font-body text-[16px] text-gray-900 hover:text-brand transition-colors">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php else: ?>
						<div class="text-center py-16">
							<p class="font-body text-[16px] text-gray-500 mb-4">Товары не найдены</p>
							<a href="<?php echo
    							esc_url(wc_get_page_permalink('shop'))
							; ?>" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-body font-semibold text-[14px] px-6 py-3 rounded-[6px] transition-colors">
								Вернуться в каталог
							</a>
						</div>
					<?php endif; ?>

					<!-- Content Section -->
					<?php if (!empty($scf_content) || $cat_desc): ?>
						<div class="mt-8 sm:mt-10 mb-[40px] lg:mb-[96px]">
							<?php if (!empty($scf_content)): ?>
								<?php foreach ($scf_content as $block): ?>
									<?php if ($block['catalog_block_heading'] || $block['catalog_block_text']): ?>
										<div class="mb-6 last:mb-0">
											<?php if ($block['catalog_block_heading']): ?>
												<h2 class="font-manrope text-[20px] sm:text-[24px] text-ink mb-3">
													<?php echo esc_html($block['catalog_block_heading']); ?>
												</h2>
											<?php endif; ?>
											<?php if ($block['catalog_block_text']): ?>
												<div class="font-body text-[14px] text-[#182028] leading-relaxed">
													<?php echo wp_kses_post(nl2br(esc_html($block['catalog_block_text']))); ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php elseif ($cat_desc): ?>
								<div class="font-body text-[14px] text-gray-600 leading-relaxed prose prose-sm max-w-none">
									<?php echo wp_kses_post($cat_desc); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

			<!-- Consultation Section (universal — always from monuments page) -->
		<?php

		$monuments_page = get_page_by_path('monuments');
		$monuments_page_id = $monuments_page ? $monuments_page->ID : 0;
		$consult_bg = $monuments_page_id ? get_field('catalog_consult_bg', $monuments_page_id) : '';
		$consult_title = $monuments_page_id
    		? (get_field('catalog_consult_title', $monuments_page_id) ?: 'Остались вопросы?')
    		: 'Остались вопросы?';
		$consult_icon = $monuments_page_id ? get_field('catalog_consult_icon', $monuments_page_id) : '';
		$consult_text = $monuments_page_id
    		? (
        		get_field('catalog_consult_text', $monuments_page_id)
        		?: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.'
    		)
    		: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.';
		$consult_btn_text = $monuments_page_id
    		? (get_field('catalog_consult_btn_text', $monuments_page_id) ?: 'Получить консультацию')
    		: 'Получить консультацию';
		$consult_btn_link = $monuments_page_id
    		? (get_field('catalog_consult_btn_link', $monuments_page_id) ?: '#callback')
    		: '#callback';
		$consult_features = $monuments_page_id ? get_field('catalog_consult_features', $monuments_page_id) : array();
		?>

		<div class="relative py-12 lg:py-20 mt-10 lg:mt-[76px] px-[10px] text-center lg:text-start" <?php if (
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
    							$feat_icon = $feature['catalog_consult_feat_icon'] ?? '';
    							$feat_title = $feature['catalog_consult_feat_title'] ?? '';
    							$feat_desc = $feature['catalog_consult_feat_desc'] ?? '';
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
