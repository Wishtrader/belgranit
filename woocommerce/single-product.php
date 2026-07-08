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
		<div class="max-w-[1200px] mx-auto pt-6 mt-[72px] lg:mt-[146px]">
			<nav class="text-xs text-[#6F6F6F] font-body !font-light" aria-label="Хлебные крошки">
				<ol class="flex flex-wrap items-center gap-1 list-none m-0 p-0">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-red-700 transition-colors">Главная</a></li>
					<?php
					$terms       = get_the_terms( get_the_ID(), 'product_cat' );
					$current_term = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
					$parent_id   = $current_term ? $current_term->parent : 0;
					$parent_term = $parent_id ? get_term( $parent_id ) : null;
					?>
					<?php if ( $parent_term && ! is_wp_error( $parent_term ) ) : ?>
						<li class="before:content-['/'] before:mx-1 before:text-gray-300">
							<a href="<?php echo esc_url( get_term_link( $parent_term ) ); ?>" class="hover:text-red-700 transition-colors"><?php echo esc_html( $parent_term->name ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $current_term ) : ?>
						<li class="before:content-['/'] before:mx-1 before:text-gray-300">
							<a href="<?php echo esc_url( get_term_link( $current_term ) ); ?>" class="hover:text-red-700 transition-colors"><?php echo esc_html( $current_term->name ); ?></a>
						</li>
					<?php endif; ?>
					<li class="before:content-['/'] before:mx-1 before:text-gray-300 text-gray-600">
						<?php the_title(); ?>
					</li>
				</ol>
			</nav>
		</div>

		<!-- Product Section -->
		<div class="max-w-[1200px] mx-auto lg:pt-16 lg:pb-24">
			<div class="flex flex-col md:flex-row lg:gap-[64px]">

				<!-- Left: Gallery -->
				<div class="px-0" x-data="{ active: 0 }">
					<div class="relative rounded-[24px] overflow-hidden mb-2 lg:w-[486px] lg:h-[457px]">
						<?php foreach ( $all_images as $index => $image_id ) :
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
							?>
							<img
								src="<?php echo esc_url( $image_url ); ?>"
								alt="<?php echo esc_attr( $image_alt ? $image_alt : get_the_title() ); ?>"
								class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300"
								x-show="active === <?php echo esc_attr( $index ); ?>"
								x-transition:enter="transition ease-out duration-300"
								x-transition:enter-start="opacity-0"
								x-transition:enter-end="opacity-100"
							>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $all_images ) > 1 ) : ?>
						<div class="grid grid-cols-4 gap-[15px] mt-6">
							<?php foreach ( $all_images as $index => $image_id ) :
								$thumb_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
								$thumb_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
								?>
								<button
									type="button"
									class="aspect-square rounded-[16px] overflow-hidden border-[1px] max-w-[110px] transition-all duration-200 bg-gray-50 hover:border-gray-400"
									:class="active === <?php echo esc_attr( $index ); ?> ? 'border-red-800 shadow-md' : 'border-transparent'"
									@click="active = <?php echo esc_attr( $index ); ?>"
								>
									<img
										src="<?php echo esc_url( $thumb_url ); ?>"
										alt="<?php echo esc_attr( $thumb_alt ? $thumb_alt : get_the_title() ); ?>"
										class="w-full h-full object-contain"
									>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right: Info -->
				<div class="bg-[#F5F4F3] p-8 shadow-md">
					<h1 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php the_title(); ?>
					</h1>

					<div class="w-[46px] h-[1px] bg-[#690008] mb-[14px]"></div>

					<?php if ( $product->get_price() ) : ?>
						<div class="mb-4">
							<span class="text-base text-gray-500 font-body">Цена: </span>
							<span class="text-2xl font-bold text-[#860000] font-manrope"><?php echo wp_strip_all_tags( $price ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $short_desc ) : ?>
						<p class="text-[#272727] text-sm font-light leading-[1.2] mb-4">
							<?php echo wp_kses_post( $short_desc ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $attributes ) ) : ?>
						<div class="pt-3 mb-3">
							<?php foreach ( $attributes as $attribute ) :
								$name  = wc_attribute_label( $attribute->get_name() );
								$value = $attribute->is_taxonomy()
									? implode( ', ', wc_get_product_attribute_term_names( $attribute->get_id() ) )
									: implode( ', ', $attribute->get_options() );
								?>
								<div class="flex justify-between items-baseline py-[10px] border-b border-gray-200 last:border-0">
									<span class="text-[#272727] font-manrope text-sm"><?php echo esc_html( $name ); ?>:</span>
									<span class="text-[#272727] font-body !font-light text-sm"><?php echo esc_html( $value ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="flex items-center gap-1 mb-5">
						<svg class="w-8 h-8 text-red-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
						</svg>
						<p class="text-sm text-gray-600 font-body leading-relaxed">
							Данная форма памятника может быть изготовлена в любых размерах
						</p>
					</div>

					<a
						href="#callback"
						class="block w-full text-center bg-[#860000] max-w-[260px] hover:bg-red-700 text-white text-base font-normal rounded-[6px] py-4 transition-colors font-body"
					>
						Оставить заявку
					</a>
				</div>

			</div>
		</div>

		<!-- Services Section -->
		<?php
		$install_items     = get_field( 'product_svc_install_items', 'options' );
		$install_icon      = get_field( 'product_svc_install_icon', 'options' );
		$install_g_title   = get_field( 'product_svc_install_guarantee_title', 'options' ) ?: 'Гарантия на камень';
		$install_g_text    = get_field( 'product_svc_install_guarantee_text', 'options' ) ?: 'Сохраняем качество на долгие годы';
		$install_g_years   = get_field( 'product_svc_install_guarantee_years', 'options' ) ?: 50;
		$install_g_icon    = get_field( 'product_svc_install_guarantee_icon', 'options' );
		$art_items         = get_field( 'product_svc_art_items', 'options' );
		$art_icon          = get_field( 'product_svc_art_icon', 'options' );
		$art_note          = get_field( 'product_svc_art_note', 'options' ) ?: 'Все работы выполняются нашими специалистами с соблюдением технологий и использованием качественных материалов';
		$art_note_icon     = get_field( 'product_svc_art_note_icon', 'options' );
		$svc_bg_image      = get_field( 'product_svc_bg_image', 'options' );

		if ( ! empty( $install_items ) || ! empty( $art_items ) ) :
		?>
			<div class="relative py-8 lg:py-20" <?php if ( $svc_bg_image ) : ?>style="background-image: url('<?php echo esc_url( $svc_bg_image ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
				<?php if ( $svc_bg_image ) : ?>
					<div class="absolute inset-0"></div>
				<?php endif; ?>
				<div class="relative max-w-[1200px] mx-auto">
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

					<?php if ( ! empty( $install_items ) ) : ?>
						<!-- Installation Card -->
						<div class="bg-white rounded-[16px] p-6 sm:p-10 shadow-sm border border-gray-100 flex flex-col">
							<div class="flex items-center gap-3 mb-6">
								<?php if ( $install_icon ) : ?>
									<img src="<?php echo esc_url( $install_icon ); ?>" alt="" class="w-11 h-11">
								<?php endif; ?>
								<h3 class="font-playfair text-base sm:text-lg font-bold text-ink">Установка</h3>
							</div>

							<div class="flex-1">
								<?php foreach ( $install_items as $item ) :
									$svc_name  = $item['product_svc_install_name'] ?? '';
									$svc_price = $item['product_svc_install_price'] ?? '';
									if ( ! $svc_name ) continue;
								?>
									<div class="flex justify-between items-baseline py-[10px] border-b border-gray-100">
										<span class="text-[#272727] font-manrope text-sm"><?php echo esc_html( $svc_name ); ?></span>
										<span class="font-semibold text-ink font-body text-sm whitespace-nowrap ml-auto mr-1"><?php echo esc_html( $svc_price ); ?> </span><span class="text-sm text-[#272727] font-light mr-3">BYN</span>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mt-9 pt-4 border-t border-[#724145] flex items-center justify-between">
								<div>
									<p class="text-[#860000] font-semibold text-xs font-light"><?php echo esc_html( $install_g_title ); ?></p>
									<p class="text-[#272727] text-xs font-light mt-0.5"><?php echo esc_html( $install_g_text ); ?></p>
								</div>
								<div class="flex items-center gap-2">
									<?php if ( $install_g_icon ) : ?>
										<img src="<?php echo esc_url( $install_g_icon ); ?>" alt="" class="w-6 h-6">
									<?php else : ?>
										<svg class="w-6 h-6 text-[#860000]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
											<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
										</svg>
									<?php endif; ?>
									<span class="text-[#860000] font-bold text-sm font-body"><?php echo esc_html( $install_g_years ); ?> <span class="text-sm font-normal">лет</span></span>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $art_items ) ) : ?>
						<!-- Art Design Card -->
						<div class="bg-white rounded-[16px] p-6 sm:p-10 shadow-sm border border-gray-100 flex flex-col">
							<div class="flex items-center gap-3 mb-6">
								<?php if ( $art_icon ) : ?>
									<img src="<?php echo esc_url( $art_icon ); ?>" alt="" class="w-10 h-10">
								<?php endif; ?>
								<h3 class="font-playfair text-base sm:text-lg font-bold text-ink">Художественное оформление</h3>
							</div>

							<div class="flex-1">
								<?php foreach ( $art_items as $item ) :
									$svc_name  = $item['product_svc_art_name'] ?? '';
									$svc_price = $item['product_svc_art_price'] ?? '';
									if ( ! $svc_name ) continue;
								?>
									<div class="flex justify-between items-baseline py-3 border-b border-gray-100">
										<span class="text-[#272727] font-manrope text-sm max-w-[200px] leading-[1.2]"><?php echo esc_html( $svc_name ); ?></span>
										<span class="font-semibold text-ink font-body text-sm whitespace-nowrap ml-auto mr-1"><?php echo esc_html( $svc_price ); ?></span><span class="text-sm text-[#272727] font-light mr-3">BYN</span>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mt-6 pt-4 border-t border-gray-200">
								<div class="flex items-center gap-2">
									<?php if ( $art_note_icon ) : ?>
										<img src="<?php echo esc_url( $art_note_icon ); ?>" alt="" class="w-6 h-6 shrink-0 mt-0.5">
									<?php else : ?>
										<svg class="w-6 h-6 text-red-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
											<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
										</svg>
									<?php endif; ?>
									<p class="text-[#272727] text-sm font-light mt-0.5"><?php echo esc_html( $art_note ); ?></p>
								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- Granite Types Slider -->
		<?php
		$granite_title = get_field( 'product_svc_granite_title', 'options' ) ?: 'Виды гранита';
		$granite_icon  = get_field( 'product_svc_granite_icon', 'options' );

		$granite_args = array(
			'post_type'      => 'granite_type',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
		$granite_query = new WP_Query( $granite_args );

		if ( $granite_query->have_posts() ) :
		?>
			<div class="max-w-[1200px] mx-auto py-8 lg:py-20">
				<!-- Title -->
				<div class="text-center mb-11">
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php echo esc_html( $granite_title ); ?>
					</h2>
					<?php if ( $granite_icon ) : ?>
						<div class="flex items-center justify-center gap-3">
							<img src="<?php echo esc_url( $granite_icon ); ?>" alt="" class="">
						</div>
					<?php else : ?>
						<div class="flex items-center justify-center gap-3">
							<div class="w-12 h-0.5 bg-red-800"></div>
							<svg class="w-5 h-5 text-red-800" viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
							</svg>
							<div class="w-12 h-0.5 bg-red-800"></div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Slider -->
				<?php
				$all_granite   = array();
				while ( $granite_query->have_posts() ) : $granite_query->the_post();
					$all_granite[] = array(
						'id'    => get_the_ID(),
						'title' => get_the_title(),
						'image' => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
					);
				endwhile;
				wp_reset_postdata();

				$per_slide    = 6;
				$total        = count( $all_granite );
				$slide_count  = (int) ceil( $total / $per_slide );
				?>

				<div x-data="graniteSlider()" class="relative">
					<!-- Desktop: Grid slides -->
					<div class="hidden md:block overflow-hidden">
						<div class="flex transition-transform duration-500 ease-in-out"
							:style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
							<?php for ( $s = 0; $s < $slide_count; $s++ ) : ?>
								<div class="w-full flex-shrink-0">
									<div class="grid grid-cols-6 gap-5">
										<?php for ( $i = 0; $i < $per_slide; $i++ ) :
											$idx = ( $s * $per_slide ) + $i;
											if ( $idx >= $total ) break;
											$item = $all_granite[ $idx ];
										?>
											<div class="text-center">
												<div class="aspect-square overflow-hidden bg-gray-100 mb-1">
													<?php if ( $item['image'] ) : ?>
														<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="w-full h-full object-cover">
													<?php else : ?>
														<div class="w-full h-full bg-gray-200"></div>
													<?php endif; ?>
												</div>
												<p class="font-manrope text-sm text-[#272727] font-medium"><?php echo esc_html( $item['title'] ); ?></p>
											</div>
										<?php endfor; ?>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>

					<!-- Mobile: Swipe slider -->
					<div class="md:hidden">
						<div class="overflow-hidden">
							<div class="flex transition-transform duration-300 ease-out"
								:style="'transform: translateX(-' + (mobileIndex * 75) + '%)'"
								x-ref="mobileSlider"
								@touchstart="touchStart($event)"
								@touchmove="touchMove($event)"
								@touchend="touchEnd($event)">
								<?php foreach ( $all_granite as $index => $item ) : ?>
									<div class="w-[75%] flex-shrink-0 pr-4">
										<div class="text-center">
											<div class="aspect-square rounded-lg overflow-hidden bg-gray-100 mb-3">
												<?php if ( $item['image'] ) : ?>
													<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="w-full h-full object-cover">
												<?php else : ?>
													<div class="w-full h-full bg-gray-200"></div>
												<?php endif; ?>
											</div>
											<p class="font-body text-sm text-ink font-medium"><?php echo esc_html( $item['title'] ); ?></p>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Dots -->
					<?php if ( $slide_count > 1 ) : ?>
						<div class="flex justify-center gap-2 mt-10">
							<?php for ( $s = 0; $s < $slide_count; $s++ ) : ?>
								<button
									type="button"
									class="w-2 h-2 rounded-full transition-colors"
									:class="currentSlide === <?php echo esc_attr( $s ); ?> ? 'bg-red-800' : 'bg-gray-300'"
									@click="currentSlide = <?php echo esc_attr( $s ); ?>"
								></button>
							<?php endfor; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

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

		<!-- Related Products Slider -->
		<?php
		$related_title = get_field( 'product_related_title', 'options' ) ?: 'Похожие товары';
		$related_icon  = get_field( 'product_related_icon', 'options' );

		$current_id    = get_the_ID();
		$current_cats  = wp_get_post_terms( $current_id, 'product_cat', array( 'fields' => 'ids' ) );
		$current_tags  = wp_get_post_terms( $current_id, 'product_tag', array( 'fields' => 'ids' ) );

		$related_args = array(
			'post_type'      => 'product',
			'posts_per_page' => 12,
			'post__not_in'   => array( $current_id ),
			'post_status'    => 'publish',
		);

		if ( ! empty( $current_cats ) ) {
			$related_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $current_cats,
				),
			);
		}

		$related_query = new WP_Query( $related_args );

		if ( $related_query->have_posts() ) :
		?>
			<div class="max-w-[1200px] mx-auto py-8 lg:py-[84px]">
				<!-- Title -->
				<div class="text-center mb-8">
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-6">
						<?php echo esc_html( $related_title ); ?>
					</h2>
					<?php if ( $related_icon ) : ?>
						<div class="flex items-center justify-center gap-3">
							<img src="<?php echo esc_url( $related_icon ); ?>" alt="" class="">
						</div>
					<?php else : ?>
						<div class="flex items-center justify-center gap-3">
							<div class="w-12 h-0.5 bg-red-800"></div>
							<svg class="w-5 h-5 text-red-800" viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
							</svg>
							<div class="w-12 h-0.5 bg-red-800"></div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Slider -->
				<?php
				$all_products = array();
				while ( $related_query->have_posts() ) : $related_query->the_post();
					$product_id    = get_the_ID();
					$product_obj   = wc_get_product( $product_id );
					if ( ! $product_obj ) continue;

					$image_id  = $product_obj->get_image_id();
					$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_medium' ) : wc_placeholder_img_src();

					$all_products[] = array(
						'id'      => $product_id,
						'title'   => get_the_title(),
						'image'   => $image_url,
						'price'   => $product_obj->get_price_html(),
						'link'    => get_the_permalink(),
					);
				endwhile;
				wp_reset_postdata();

				$per_slide    = 4;
				$total        = count( $all_products );
				$slide_count  = (int) ceil( $total / $per_slide );
				?>

				<div x-data="relatedSlider()" class="relative">
					<!-- Desktop: 4 per slide -->
					<div class="hidden md:block overflow-hidden">
						<div class="flex transition-transform duration-500 ease-in-out"
							:style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
							<?php for ( $s = 0; $s < $slide_count; $s++ ) : ?>
								<div class="w-full flex-shrink-0">
									<div class="grid grid-cols-4 gap-5">
										<?php for ( $i = 0; $i < $per_slide; $i++ ) :
											$idx = ( $s * $per_slide ) + $i;
											if ( $idx >= $total ) break;
											$item = $all_products[ $idx ];
										?>
											<a href="<?php echo esc_url( $item['link'] ); ?>" class="group block bg-white rounded-[6px] border border-gray-100 overflow-hidden shadow-lg">
												<div class="overflow-hidden">
													<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
												</div>
												<div class="p-4 bg-[#F5F4F3]">
													<h3 class="font-manrope text-lg text-ink mb-3 leading-[1.2] min-h-[40px]"><?php echo esc_html( $item['title'] ); ?></h3>
													<div class="h-px bg-[#860000]/30 w-[46px] mb-3"></div>
													<p class="font-manrope text-base text-[#272727]">Цена: <span class="text-[#860000] font-manrope font-bold text-2xl"><?php echo wp_strip_all_tags( $item['price'] ); ?></span></p>
												</div>
											</a>
										<?php endfor; ?>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>

					<!-- Mobile: 2 per slide -->
					<div class="md:hidden overflow-hidden">
						<div class="flex transition-transform duration-500 ease-in-out"
							:style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
							<?php
							$mobile_per_slide = 2;
							$mobile_slide_count = (int) ceil( $total / $mobile_per_slide );
							for ( $s = 0; $s < $mobile_slide_count; $s++ ) : ?>
								<div class="w-full flex-shrink-0">
									<div class="grid grid-cols-2 gap-3">
										<?php for ( $i = 0; $i < $mobile_per_slide; $i++ ) :
											$idx = ( $s * $mobile_per_slide ) + $i;
											if ( $idx >= $total ) break;
											$item = $all_products[ $idx ];
										?>
											<a href="<?php echo esc_url( $item['link'] ); ?>" class="group block bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
												<div class="aspect-square overflow-hidden bg-gray-50 p-2">
													<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="w-full h-full object-contain">
												</div>
												<div class="p-3">
													<h3 class="font-body text-xs font-semibold text-ink mb-2 line-clamp-2 min-h-[32px]"><?php echo esc_html( $item['title'] ); ?></h3>
													<div class="w-full h-px bg-gray-200 mb-2"></div>
													<p class="font-body text-xs text-gray-500">Цена: <span class="text-red-800 font-bold text-sm"><?php echo wp_strip_all_tags( $item['price'] ); ?></span></p>
												</div>
											</a>
										<?php endfor; ?>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>

					<!-- Dots -->
					<?php if ( $slide_count > 1 ) : ?>
						<div class="flex justify-center gap-2 mt-10">
							<?php for ( $s = 0; $s < $slide_count; $s++ ) : ?>
								<button
									type="button"
									class="w-2 h-2 rounded-full transition-colors"
									:class="currentSlide === <?php echo esc_attr( $s ); ?> ? 'bg-red-800' : 'bg-gray-300'"
									@click="currentSlide = <?php echo esc_attr( $s ); ?>"
								></button>
							<?php endfor; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
