<?php
/**
 * The template for displaying product archive pages (catalog)
 *
 * @package BelGranit
 */

get_header();

// Current category
$current_cat   = get_queried_object();
$is_category   = is_tax( 'product_cat' ) && $current_cat instanceof WP_Term;
$is_shop       = is_shop();
$cat_name      = $is_category ? $current_cat->name : 'Памятники';
$cat_slug      = $is_category ? $current_cat->slug : '';
$cat_id        = $is_category ? $current_cat->term_id : 0;
$cat_parent    = $is_category ? $current_cat->parent : 0;
$cat_desc      = $is_category ? term_description( $current_cat->term_id, 'product_cat' ) : '';
$cat_image_id  = $is_category ? get_term_meta( $current_cat->term_id, 'thumbnail_id', true ) : '';
$cat_image_url = $cat_image_id ? wp_get_attachment_image_url( $cat_image_id, 'full' ) : get_template_directory_uri() . '/assets/hero.jpg';
$product_count = $is_category ? $current_cat->count : wc_get_loop_prop( 'total' );

// Get subcategories of "Памятники"
$parent_cat    = get_term_by( 'slug', 'pamyatniki', 'product_cat' );
$parent_cat_id = $parent_cat ? $parent_cat->term_id : 0;

$product_categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'parent'     => $parent_cat_id,
) );

// Sort parameters
$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date';
$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

// Search
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
?>

<style>
	/* Category sidebar */
	.cat-link { transition: all 0.2s ease; border-radius: 12px; }
	.cat-link.active { background-color: #860000; color: #fff; }
	.cat-link:not(.active):hover { color: #860000; background-color: rgba(134,0,0,0.06); }

	/* Product cards */
	.product-card { transition: box-shadow 0.2s ease, transform 0.2s ease; }
	.product-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }

	/* Sort select */
	.sort-select {
		appearance: none;
		-webkit-appearance: none;
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
		background-repeat: no-repeat;
		background-position: right 12px center;
		padding-right: 36px;
	}

	/* Pagination */
	.page-num { transition: all 0.2s ease; }
	.page-num.active { background-color: #860000; color: #fff; }
	.page-num:not(.active):hover { background-color: #f5f4f3; }
</style>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="relative h-[200px] sm:h-[240px] lg:h-[280px] overflow-hidden mt-[72px] lg:mt-0">
		<div class="absolute inset-0">
			<img src="<?php echo esc_url( $cat_image_url ); ?>" alt="<?php echo esc_attr( $cat_name ); ?>" class="w-full h-full object-cover">
			<div class="absolute inset-0 bg-black/40"></div>
		</div>
		<div class="relative z-10 h-full max-w-[1200px] mx-auto px-4 sm:px-6 flex flex-col justify-center">
			<!-- Breadcrumbs -->
			<nav class="font-body text-[13px] text-white/70 mb-3 flex flex-wrap items-center gap-x-1">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-white"><?php echo esc_html( $cat_name ); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] lg:text-[42px] text-white uppercase leading-tight mb-2">
				<?php echo esc_html( $cat_name ); ?>
			</h1>
			<p class="font-body text-[14px] text-white/80">
				Найдено <?php echo esc_html( $product_count ); ?> <?php echo esc_html( belgranit_pluralize( $product_count, 'единица товаров', 'единицы товаров', 'единиц товаров' ) ); ?>
			</p>
		</div>
	</section>

	<!-- Search & Sort Bar -->
	<section class="py-4 border-b border-gray-100">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6">
			<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
				<!-- Search -->
				<form method="GET" class="flex-1 relative" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php if ( $cat_slug ) : ?>
						<input type="hidden" name="product_cat" value="<?php echo esc_attr( $cat_slug ); ?>">
					<?php endif; ?>
					<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
					</svg>
					<input type="text" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="Поиск по названию..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg font-body text-[14px] text-ink placeholder-gray-400 focus:outline-none focus:border-brand transition-colors">
				</form>

				<!-- Sort -->
				<div class="flex items-center gap-2 shrink-0">
					<span class="font-body text-[13px] text-gray-500 hidden sm:inline">Сортировать по:</span>
					<select class="sort-select border border-gray-200 rounded-lg px-4 py-2.5 font-body text-[14px] text-ink bg-white cursor-pointer focus:outline-none focus:border-brand transition-colors" onchange="window.location.href=this.value">
						<?php
						$current_url = remove_query_arg( array( 'orderby', 'order', 'paged' ) );
						$sort_options = array(
							''          => 'По умолчанию',
							'date'      => 'По дате',
							'title'     => 'По названию',
							'price'     => 'По цене',
							'popularity' => 'По популярности',
						);
						foreach ( $sort_options as $key => $label ) {
							$sort_url = $key ? add_query_arg( array( 'orderby' => $key, 'order' => 'ASC' ), $current_url ) : $current_url;
							$selected = ( $orderby === $key || ( ! $key && ! $orderby ) ) ? ' selected' : '';
							echo '<option value="' . esc_url( $sort_url ) . '"' . $selected . '>' . esc_html( $label ) . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</section>

	<!-- Main Content -->
	<section class="py-8 sm:py-10 lg:py-12">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6">
			<div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

				<!-- Sidebar -->
				<aside class="lg:w-[260px] shrink-0">
					<!-- Categories Block -->
					<div class="bg-[#f7f6f5] rounded-2xl p-6 mb-6">
						<h3 class="font-playfair text-[22px] sm:text-[26px] text-ink mb-6">Категории</h3>
						<ul class="space-y-0 list-none m-0 p-0">
							<li>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
								   class="cat-link block py-3 px-4 rounded-xl font-body text-[15px] font-medium <?php echo ( ! $is_category || $cat_parent !== $parent_cat_id ) ? 'active' : 'text-gray-700 hover:text-brand'; ?>">
									Все памятники
								</a>
							</li>
							<?php if ( ! is_wp_error( $product_categories ) && ! empty( $product_categories ) ) : ?>
								<?php foreach ( $product_categories as $cat ) :
									$is_active = ( (int) $cat->term_id === (int) $cat_id );
								?>
									<li>
										<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
										   class="cat-link block py-3 px-4 rounded-xl font-body text-[15px] font-medium <?php echo $is_active ? 'active' : 'text-gray-700 hover:text-brand'; ?>">
											<?php echo esc_html( $cat->name ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>

					<!-- CTA Card -->
					<div class="bg-brand rounded-2xl p-6 text-white">
						<h4 class="font-playfair text-[20px] sm:text-[22px] mb-3 leading-tight uppercase">Не знаете, какой памятник выбрать?</h4>
						<p class="font-body text-[14px] text-white/85 mb-5 leading-relaxed">Оставьте заявку. Мы поможем подобрать вариант с учетом бюджета, пожеланий и особенностей участка</p>
						<a href="#consultation-form" class="block text-center bg-white text-ink font-body font-semibold text-[15px] px-5 py-3 rounded-[6px] hover:bg-gray-50 transition-colors">
							Получить консультацию
						</a>
					</div>
				</aside>

				<!-- Product Grid -->
				<div class="flex-1">
					<?php if ( wc_get_loop_prop( 'total' ) > 0 ) : ?>
						<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php while ( have_posts() ) : the_post(); ?>
								<?php wc_get_template_part( 'content', 'product' ); ?>
							<?php endwhile; ?>
						</div>

						<!-- Pagination -->
						<?php if ( wc_get_loop_prop( 'max_num_pages' ) > 1 ) : ?>
							<div class="flex justify-center items-center gap-2 mt-10">
								<?php
								$current_page = max( 1, get_query_var( 'paged' ) );
								$total_pages  = wc_get_loop_prop( 'max_num_pages' );
								$base_url     = wc_get_page_permalink( 'shop' );

								if ( $cat_slug ) {
									$base_url = add_query_arg( 'product_cat', $cat_slug, $base_url );
								}
								if ( $search_query ) {
									$base_url = add_query_arg( 's', $search_query, $base_url );
								}
								if ( $orderby ) {
									$base_url = add_query_arg( 'orderby', $orderby, $base_url );
								}

								// Previous
								if ( $current_page > 1 ) :
								?>
									<a href="<?php echo esc_url( add_query_arg( 'paged', $current_page - 1, $base_url ) ); ?>" class="page-num flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 font-body text-[14px] text-gray-600 hover:border-brand hover:text-brand transition-colors">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
									</a>
								<?php endif; ?>

								<?php
								$range = 2;
								$start = max( 1, $current_page - $range );
								$end   = min( $total_pages, $current_page + $range );

								if ( $start > 1 ) :
								?>
									<a href="<?php echo esc_url( add_query_arg( 'paged', 1, $base_url ) ); ?>" class="page-num flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 font-body text-[14px] text-gray-600">1</a>
									<?php if ( $start > 2 ) : ?>
										<span class="text-gray-400 px-1">...</span>
									<?php endif; ?>
								<?php endif; ?>

								<?php for ( $i = $start; $i <= $end; $i++ ) : ?>
									<a href="<?php echo esc_url( add_query_arg( 'paged', $i, $base_url ) ); ?>" class="page-num flex items-center justify-center w-9 h-9 rounded-lg border font-body text-[14px] <?php echo $i === $current_page ? 'active border-brand' : 'border-gray-200 text-gray-600'; ?>">
										<?php echo esc_html( $i ); ?>
									</a>
								<?php endfor; ?>

								<?php if ( $end < $total_pages ) : ?>
									<?php if ( $end < $total_pages - 1 ) : ?>
										<span class="text-gray-400 px-1">...</span>
									<?php endif; ?>
									<a href="<?php echo esc_url( add_query_arg( 'paged', $total_pages, $base_url ) ); ?>" class="page-num flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 font-body text-[14px] text-gray-600"><?php echo esc_html( $total_pages ); ?></a>
								<?php endif; ?>

								<!-- Next -->
								<?php if ( $current_page < $total_pages ) : ?>
									<a href="<?php echo esc_url( add_query_arg( 'paged', $current_page + 1, $base_url ) ); ?>" class="page-num flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 font-body text-[14px] text-gray-600 hover:border-brand hover:text-brand transition-colors">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					<?php else : ?>
						<div class="text-center py-16">
							<p class="font-body text-[16px] text-gray-500 mb-4">Товары не найдены</p>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-body font-semibold text-[14px] px-6 py-3 rounded-[6px] transition-colors">
								Вернуться в каталог
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- SEO Text Section -->
	<?php if ( $cat_desc ) : ?>
		<section class="py-8 sm:py-10 border-t border-gray-100">
			<div class="max-w-[1200px] mx-auto px-4 sm:px-6">
				<div class="font-body text-[14px] text-gray-600 leading-relaxed prose prose-sm max-w-none">
					<?php echo wp_kses_post( $cat_desc ); ?>
				</div>
			</div>
		</section>
	<?php else : ?>
		<section class="py-8 sm:py-10 border-t border-gray-100">
			<div class="max-w-[1200px] mx-auto px-4 sm:px-6">
				<div class="font-body text-[14px] text-gray-600 leading-relaxed space-y-4">
					<p>Памятники одинарные на могилу предназначены для захоронения одного человека. Таким образом, вы помогаете определить чувства к близкому, создав особое пространство. Они становятся красивым, выразительным украшением.</p>

					<h2 class="font-playfair text-[20px] sm:text-[24px] text-ink uppercase mt-6 mb-3">Варианты одинарных памятников из мрамора и гранита</h2>
					<p>Камень, из которого будет изготовлен памятник, зависит от ваших предпочтений. В портфолио нашей компании представлено несколько фотографий выполненных проектов. Вы можете сами обдумать, какой формы и размера они должны быть. Помните, что размер надмогильных сооружений не должен превышать размеры могилы.</p>
					<p>Стандартные памятники выполняются в виде прямоугольных фигур. Они подходят для увековечивания могил мужчины и женщины. Как правило, монументы устанавливаются вертикально, что позволяет не занимать много пространства. Оригинальные одинарные надгробия включают цветники, скамейки и оградки.</p>
					<p>Элитные одинарные памятники создаются по индивидуальным проектам. Для их изготовления применяются привозные камни зарубежных пород. Мастера используют современные станки с алмазными кругами — борфрезы, болгарки, компрессоры, резцы по камню и другое.</p>

					<h2 class="font-playfair text-[20px] sm:text-[24px] text-ink uppercase mt-6 mb-3">Заказать одинарный памятник</h2>
					<p>Подобрать одинарный памятник на могилу или воронец можно в каталоге интернет-магазина. Чтобы сделать это, воспользуйтесь фильтром или обратитесь к менеджеру. Он выслушает вас, рассмотрит предложения и передаст заказ производственному цеху.</p>
					<p>Изготовление одинарных памятников с крестом или без него, с другими деталями на собственном производстве занимает от нескольких дней до месяца. Вы получите готовую работу уже спустя небольшой срок.</p>
					<p>Опытная бригада мастеров готова установить памятник на могилу с учетом всех существующих условий и требований. Обращайтесь по телефону к менеджеру компании, чтобы получить ответы на свои вопросы и оформить заказ.</p>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- CTA Section -->
	<section class="py-10 sm:py-14 lg:py-16 bg-muted" id="consultation-form">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6">
			<div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-center">
				<!-- Left -->
				<div class="lg:w-[45%]">
					<div class="flex items-start gap-4 mb-4">
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/deco.svg" alt="" class="w-12 h-12 opacity-30 shrink-0 mt-1" style="filter: grayscale(1);">
						<div>
							<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[32px] text-ink uppercase leading-tight">
								Не нашли подходящий вариант?
							</h2>
							<div class="flex justify-start mt-2">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/divider.svg" alt="" class="h-3">
							</div>
						</div>
					</div>
					<p class="font-body text-[15px] text-gray-500 mb-6 leading-relaxed pl-0 lg:pl-16">
						Изготовим памятник по вашим эскизам или предложим оптимальное решение.
					</p>
					<div class="pl-0 lg:pl-16">
						<a href="#consultation-form" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-body font-semibold text-[15px] px-6 py-3 rounded-[6px] transition-colors">
							Получить консультацию
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
								<path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
							</svg>
						</a>
					</div>
				</div>

				<!-- Right: Features -->
				<div class="lg:w-[55%] space-y-5">
					<div class="flex items-start gap-4">
						<div class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center shrink-0">
							<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
								<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<h4 class="font-manrope text-[15px] font-bold text-ink mb-0.5">Индивидуальный подбор</h4>
							<p class="font-body text-[13px] text-gray-500">Учтем ваши пожелания и подберем оптимальный вариант</p>
						</div>
					</div>
					<div class="flex items-start gap-4">
						<div class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center shrink-0">
							<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
							</svg>
						</div>
						<div>
							<h4 class="font-manrope text-[15px] font-bold text-ink mb-0.5">Под любой бюджет</h4>
							<p class="font-body text-[13px] text-gray-500">Найдем решение, которое вам подойдет</p>
						</div>
					</div>
					<div class="flex items-start gap-4">
						<div class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center shrink-0">
							<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
								<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
							</svg>
						</div>
						<div>
							<h4 class="font-manrope text-[15px] font-bold text-ink mb-0.5">Опыт и гарантия</h4>
							<p class="font-body text-[13px] text-gray-500">Более 15 лет опыта и гарантия на все работы</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
