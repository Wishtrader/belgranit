<?php
/**
 * Template part for displaying products in the catalog grid
 *
 * @package BelGranit
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id    = $product->get_id();
$product_title = $product->get_title();
$image_id      = get_post_thumbnail_id( $product_id );
$price_html    = $product->get_price_html();
$permalink     = $product->get_permalink();
?>

<div class="product-card bg-white rounded-lg overflow-hidden border border-gray-100">
	<a href="<?php echo esc_url( $permalink ); ?>" class="block">
		<!-- Product Image -->
		<div class="aspect-square bg-gray-50 p-4 flex items-center justify-center">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array(
					'class' => 'w-full h-full object-contain',
					'alt'   => esc_attr( $product_title ),
				) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" class="w-full h-full object-contain">
			<?php endif; ?>
		</div>

		<!-- Product Info -->
		<div class="p-4">
			<h3 class="font-manrope text-[14px] font-bold text-ink mb-2 leading-tight line-clamp-2">
				<?php echo esc_html( $product_title ); ?>
			</h3>
			<?php if ( $price_html ) : ?>
				<div class="font-body text-[14px]">
					<span class="text-gray-400">Цена: </span>
					<span class="text-brand font-semibold"><?php echo wp_strip_all_tags( $price_html ); ?> <span class="font-normal text-gray-400">BYN</span></span>
				</div>
			<?php endif; ?>
		</div>
	</a>
</div>
