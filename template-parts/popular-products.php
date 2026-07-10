<?php

/**
 * Popular Products Section
 *
 * @package BelGranit
 */

$heading = get_field('popular_heading') ?: 'Популярные решения';
$icon = get_field('popular_icon');
$product_ids = get_field('popular_products');
?>
<!-- Popular products -->
<section class="mx-auto max-w-[1200px] px-4 py-16 lg:py-20 xl:px-0">
	<!-- Title -->
	<div class="mb-11 flex flex-col items-center gap-5">
		<h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo
    		esc_html($heading)
		; ?></h2>
		<?php if ($icon): ?>
			<img src="<?php echo esc_url($icon); ?>" alt="" class="h-[22px] w-[114px]">
		<?php else: ?>
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		<?php endif; ?>
	</div>

	<?php

	if (!empty($product_ids) && is_array($product_ids)):
    	$all_products = array();
    	foreach ($product_ids as $pid):
        	$product_obj = wc_get_product($pid);
        	if (!$product_obj)
            	continue;

        	$image_id = $product_obj->get_image_id();
        	$image_url = $image_id
            	? wp_get_attachment_image_url($image_id, 'woocommerce_medium')
            	: wc_placeholder_img_src();

        	$all_products[] = array(
            	'id' => $pid,
            	'title' => get_the_title($pid),
            	'image' => $image_url,
            	'price' => $product_obj->get_price_html(),
            	'link' => get_the_permalink($pid),
        	);
    	endforeach;
    	?>

		<!-- Desktop grid: 4 columns -->
		<div class="hidden md:grid grid-cols-4 gap-5">
			<?php foreach ($all_products as $item): ?>
				<a href="<?php echo
    				esc_url($item['link'])
				; ?>" class="group block bg-white rounded-[6px] border border-gray-100 overflow-hidden shadow-lg">
					<div class="overflow-hidden">
						<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo
    						esc_attr($item['title'])
						; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
					</div>
					<div class="p-4 bg-[#F5F4F3]">
						<h3 class="font-manrope text-lg text-ink mb-3 leading-[1.2] min-h-[40px]"><?php echo
    						esc_html($item['title'])
						; ?></h3>
						<div class="h-px bg-[#860000]/30 w-[46px] mb-3"></div>
						<p class="font-manrope text-base text-[#272727]">Цена: <span class="text-[#860000] font-manrope font-bold text-2xl"><?php echo
    						wp_strip_all_tags($item['price'])
						; ?></span></p>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- Mobile grid: 2 columns -->
		<div class="md:hidden grid grid-cols-2 gap-3">
			<?php foreach ($all_products as $item): ?>
				<a href="<?php echo
    				esc_url($item['link'])
				; ?>" class="group block bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
					<div class="aspect-square overflow-hidden bg-gray-50 p-2">
						<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo
    						esc_attr($item['title'])
						; ?>" class="w-full h-full object-contain">
					</div>
					<div class="p-3">
						<h3 class="font-body text-xs font-semibold text-ink mb-2 line-clamp-2 min-h-[32px]"><?php echo
    						esc_html($item['title'])
						; ?></h3>
						<div class="w-[46px] h-px bg-gray-200 mb-2"></div>
						<p class="font-body text-xs text-gray-500">Цена: <span class="text-red-800 font-bold text-sm"><?php echo
    						wp_strip_all_tags($item['price'])
						; ?></span></p>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- Button -->
		<div class="mt-10 text-center">
			<a href="<?php echo
    			esc_url(wc_get_page_permalink('shop'))
			; ?>" class="inline-flex items-center justify-center gap-2 border-2 border-[#860000] text-[#860000] hover:bg-[#860000] hover:text-white text-base font-semibold uppercase tracking-wide px-8 py-4 rounded-[6px] transition-colors">
				Смотреть все решения
					<svg width="17" height="12" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[17px] h-3 transition-colors"><path d="M16.5657 6.4568C16.8781 6.14438 16.8781 5.63785 16.5657 5.32543L11.4745 0.234259C11.1621 -0.0781603 10.6556 -0.0781603 10.3431 0.234259C10.0307 0.546679 10.0307 1.05321 10.3431 1.36563L14.8686 5.89111L10.3431 10.4166C10.0307 10.729 10.0307 11.2355 10.3431 11.548C10.6556 11.8604 11.1621 11.8604 11.4745 11.548L16.5657 6.4568ZM0 5.89111V6.69111H16V5.89111V5.09111H0V5.89111Z" fill="currentColor"/></svg>
			</a>
		</div>

	<?php endif; ?>
</section>
