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

<a href="<?php echo esc_url( $permalink ); ?>" class="group block bg-white rounded-[6px] border border-gray-100 overflow-hidden shadow-lg">
	<!-- Product Image -->
	<div class="overflow-hidden">
		<?php if ( $image_id ) : ?>
			<?php echo wp_get_attachment_image( $image_id, 'woocommerce_medium', false, array(
				'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
				'alt'   => esc_attr( $product_title ),
			) ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
		<?php endif; ?>
	</div>

	<!-- Product Info -->
	<div class="p-4 bg-[#F5F4F3]">
		<h3 class="font-manrope text-lg text-ink mb-3 leading-[1.2] min-h-[40px]">
			<?php echo esc_html( $product_title ); ?>
		</h3>
		<div class="h-px bg-[#860000]/30 w-[46px] mb-3"></div>
		<?php if ( $price_html ) : ?>
			<p class="font-manrope text-base text-[#272727]">Цена: <span class="text-[#860000] font-manrope font-bold text-2xl"><?php echo wp_strip_all_tags( $price_html ); ?></span></p>
		<?php endif; ?>
	</div>
</a>
