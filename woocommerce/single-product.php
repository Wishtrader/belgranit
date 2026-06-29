<?php
/**
 * The template for displaying the product page
 *
 * @package BelGranit
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php while ( have_posts() ) : the_post();

		global $product;
		if ( ! $product ) {
			continue;
		}

		$gallery_images = $product->get_gallery_image_ids();
		$main_image_id  = $product->get_image_id();
		$all_images     = array_merge( array( $main_image_id ), $gallery_images );
		$attributes     = $product->get_attributes();
		$short_desc     = $product->get_short_description();
		$price          = $product->get_price_html();
	?>

		<!-- Breadcrumb -->
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6 pt-6 mt-[72px] lg:mt-0">
			<nav class="text-sm text-gray-400 font-body" aria-label="Хлебные крошки">
				<ol class="flex flex-wrap items-center gap-1 list-none m-0 p-0">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-red-700 transition-colors">Главная</a></li>
					<?php
					$terms      = get_the_terms( get_the_ID(), 'product_cat' );
					$term       = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
					$parent_id  = $term ? $term->parent : 0;
					$parent_term = $parent_id ? get_term( $parent_id ) : null;
					?>
					<?php if ( $term ) : ?>
						<li class="before:content-['/'] before:mx-1 before:text-gray-300">
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="hover:text-red-700 transition-colors"><?php echo esc_html( $term->name ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $parent_term && ! is_wp_error( $parent_term ) ) : ?>
						<li class="before:content-['/'] before:mx-1 before:text-gray-300">
							<a href="<?php echo esc_url( get_term_link( $parent_term ) ); ?>" class="hover:text-red-700 transition-colors"><?php echo esc_html( $parent_term->name ); ?></a>
						</li>
					<?php endif; ?>
					<li class="before:content-['/'] before:mx-1 before:text-gray-300 text-gray-600">
						<?php the_title(); ?>
					</li>
				</ol>
			</nav>
		</div>

		<!-- Product Section -->
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-8 lg:py-12">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

				<!-- Left: Gallery -->
				<div class="product-gallery" x-data="{ active: 0 }">
					<div class="relative rounded-xl overflow-hidden bg-gray-50 aspect-square mb-4">
						<?php foreach ( $all_images as $index => $image_id ) :
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
							?>
							<img
								src="<?php echo esc_url( $image_url ); ?>"
								alt="<?php echo esc_attr( $image_alt ? $image_alt : get_the_title() ); ?>"
								class="absolute inset-0 w-full h-full object-contain p-6 transition-opacity duration-300"
								x-show="active === <?php echo esc_attr( $index ); ?>"
								x-transition:enter="transition ease-out duration-300"
								x-transition:enter-start="opacity-0"
								x-transition:enter-end="opacity-100"
							>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $all_images ) > 1 ) : ?>
						<div class="grid grid-cols-4 gap-3">
							<?php foreach ( $all_images as $index => $image_id ) :
								$thumb_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
								$thumb_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
								?>
								<button
									type="button"
									class="aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200 bg-gray-50 hover:border-gray-400"
									:class="active === <?php echo esc_attr( $index ); ?> ? 'border-red-800 shadow-md' : 'border-transparent'"
									@click="active = <?php echo esc_attr( $index ); ?>"
								>
									<img
										src="<?php echo esc_url( $thumb_url ); ?>"
										alt="<?php echo esc_attr( $thumb_alt ? $thumb_alt : get_the_title() ); ?>"
										class="w-full h-full object-contain p-1"
									>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right: Info -->
				<div class="bg-white rounded-2xl p-6 sm:p-8 lg:p-10">
					<h1 class="font-playfair text-[24px] sm:text-[28px] lg:text-[32px] text-ink uppercase leading-tight mb-4">
						<?php the_title(); ?>
					</h1>

					<div class="w-16 h-0.5 bg-red-800 mb-6"></div>

					<?php if ( $product->get_price() ) : ?>
						<div class="mb-4">
							<span class="text-base text-gray-500 font-body">Цена: </span>
							<span class="text-[28px] sm:text-[32px] font-bold text-red-800 font-body"><?php echo wp_strip_all_tags( $price ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $short_desc ) : ?>
						<p class="text-gray-600 font-body leading-relaxed mb-6">
							<?php echo wp_kses_post( $short_desc ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $attributes ) ) : ?>
						<div class="border-t border-gray-100 pt-4 mb-6">
							<?php foreach ( $attributes as $attribute ) :
								$name  = wc_attribute_label( $attribute->get_name() );
								$value = $attribute->is_taxonomy()
									? implode( ', ', wc_get_product_attribute_term_names( $attribute->get_id() ) )
									: implode( ', ', $attribute->get_options() );
								?>
								<div class="flex justify-between items-baseline py-3 border-b border-gray-100 last:border-0">
									<span class="text-gray-500 font-body text-sm sm:text-base"><?php echo esc_html( $name ); ?>:</span>
									<span class="text-gray-800 font-body font-semibold text-sm sm:text-base text-right"><?php echo esc_html( $value ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="flex items-start gap-3 bg-gray-50 rounded-lg p-4 mb-6">
						<svg class="w-5 h-5 text-red-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
						<p class="text-sm text-gray-600 font-body leading-relaxed">
							Данная форма памятника может быть изготовлена в любых размерах
						</p>
					</div>

					<a
						href="#callback"
						class="block w-full text-center bg-[#860000] hover:bg-red-700 text-white text-base font-semibold uppercase tracking-wide rounded-lg py-4 transition-colors font-body"
					>
						Оставить заявку
					</a>
				</div>

			</div>
		</div>

		<!-- Services Section -->
		<?php
		$install_items     = get_field( 'product_svc_install_items' );
		$install_icon      = get_field( 'product_svc_install_icon' );
		$install_g_title   = get_field( 'product_svc_install_guarantee_title' ) ?: 'Гарантия на камень';
		$install_g_text    = get_field( 'product_svc_install_guarantee_text' ) ?: 'Сохраняем качество на долгие годы';
		$install_g_years   = get_field( 'product_svc_install_guarantee_years' ) ?: 50;
		$install_g_icon    = get_field( 'product_svc_install_guarantee_icon' );
		$art_items         = get_field( 'product_svc_art_items' );
		$art_icon          = get_field( 'product_svc_art_icon' );
		$art_note          = get_field( 'product_svc_art_note' ) ?: 'Все работы выполняются нашими специалистами с соблюдением технологий и использованием качественных материалов';
		$art_note_icon     = get_field( 'product_svc_art_note_icon' );
		$svc_bg_image      = get_field( 'product_svc_bg_image' );

		if ( ! empty( $install_items ) || ! empty( $art_items ) ) :
		?>
			<div class="relative py-8 lg:py-12" <?php if ( $svc_bg_image ) : ?>style="background-image: url('<?php echo esc_url( $svc_bg_image ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
				<?php if ( $svc_bg_image ) : ?>
					<div class="absolute inset-0 bg-white/60"></div>
				<?php endif; ?>
				<div class="relative max-w-[1200px] mx-auto px-4 sm:px-6">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

					<?php if ( ! empty( $install_items ) ) : ?>
						<!-- Installation Card -->
						<div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col">
							<div class="flex items-center gap-3 mb-6">
								<?php if ( $install_icon ) : ?>
									<img src="<?php echo esc_url( $install_icon ); ?>" alt="" class="w-10 h-10">
								<?php endif; ?>
								<h3 class="font-playfair text-xl sm:text-2xl font-bold text-ink">Установка</h3>
							</div>

							<div class="flex-1">
								<?php foreach ( $install_items as $item ) :
									$svc_name  = $item['product_svc_install_name'] ?? '';
									$svc_price = $item['product_svc_install_price'] ?? '';
									if ( ! $svc_name ) continue;
								?>
									<div class="flex justify-between items-baseline py-3 border-b border-gray-100 last:border-0">
										<span class="text-gray-600 font-body text-sm sm:text-base"><?php echo esc_html( $svc_name ); ?></span>
										<span class="font-semibold text-ink font-body text-sm sm:text-base whitespace-nowrap ml-4"><?php echo esc_html( $svc_price ); ?> BYN</span>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-between">
								<div>
									<p class="text-red-800 font-semibold text-sm font-body"><?php echo esc_html( $install_g_title ); ?></p>
									<p class="text-gray-400 text-xs font-body mt-0.5"><?php echo esc_html( $install_g_text ); ?></p>
								</div>
								<div class="flex items-center gap-2">
									<?php if ( $install_g_icon ) : ?>
										<img src="<?php echo esc_url( $install_g_icon ); ?>" alt="" class="w-6 h-6">
									<?php else : ?>
										<svg class="w-6 h-6 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
											<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
										</svg>
									<?php endif; ?>
									<span class="text-red-800 font-bold text-lg font-body"><?php echo esc_html( $install_g_years ); ?> <span class="text-sm font-normal">лет</span></span>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $art_items ) ) : ?>
						<!-- Art Design Card -->
						<div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col">
							<div class="flex items-center gap-3 mb-6">
								<?php if ( $art_icon ) : ?>
									<img src="<?php echo esc_url( $art_icon ); ?>" alt="" class="w-10 h-10">
								<?php endif; ?>
								<h3 class="font-playfair text-xl sm:text-2xl font-bold text-ink">Художественное оформление</h3>
							</div>

							<div class="flex-1">
								<?php foreach ( $art_items as $item ) :
									$svc_name  = $item['product_svc_art_name'] ?? '';
									$svc_price = $item['product_svc_art_price'] ?? '';
									if ( ! $svc_name ) continue;
								?>
									<div class="flex justify-between items-baseline py-3 border-b border-gray-100 last:border-0">
										<span class="text-gray-600 font-body text-sm sm:text-base"><?php echo esc_html( $svc_name ); ?></span>
										<span class="font-semibold text-ink font-body text-sm sm:text-base whitespace-nowrap ml-4"><?php echo esc_html( $svc_price ); ?> BYN</span>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mt-6 pt-4 border-t border-gray-200">
								<div class="flex items-start gap-3">
									<?php if ( $art_note_icon ) : ?>
										<img src="<?php echo esc_url( $art_note_icon ); ?>" alt="" class="w-5 h-5 shrink-0 mt-0.5">
									<?php else : ?>
										<svg class="w-5 h-5 text-red-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
											<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
										</svg>
									<?php endif; ?>
									<p class="text-sm text-gray-500 font-body leading-relaxed"><?php echo esc_html( $art_note ); ?></p>
								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>
				</div>
			</div>
		<?php endif; ?>

	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
