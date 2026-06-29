<?php
/**
 * Popular Products Section
 *
 * @package BelGranit
 */

$heading = get_field( 'popular_heading' ) ?: 'Популярные решения';
?>
<!-- Popular products -->
<section class="mx-auto max-w-[1200px] px-4 py-16 lg:py-20 xl:px-0">
	<div class="mb-11 flex flex-col items-center gap-5">
		<h2 class="font-playfair text-center text-3xl text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
	</div>
</section>
