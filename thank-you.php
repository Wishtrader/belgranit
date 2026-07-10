<?php

/**
 * Template Name: Thank you
 * The template for thank you page.
 *
 * @package BelGranit
 */

get_header();

$bg_desktop = get_field( 'thank_you_bg_desktop' ) ?: get_template_directory_uri() . '/img/thank-you-bg.png';
$bg_mobile  = get_field( 'thank_you_bg_mobile' ) ?: get_template_directory_uri() . '/img/thank-you-bg-mobile.png';
$heading    = get_field( 'thank_you_heading' ) ?: 'Спасибо, мы получили вашу заявку!';
$divider    = get_field( 'thank_you_divider' ) ?: get_template_directory_uri() . '/img/divider.svg';
$text       = get_field( 'thank_you_text' ) ?: 'Мы свяжемся с вами в ближайшее время.';
?>

<main id="primary" class="site-main">
	<section class="relative h-[728px] lg:h-[800px] overflow-hidden">
		<!-- Desktop Background -->
		<img src="<?php echo esc_url( $bg_desktop ); ?>"
		     alt="" class="pointer-events-none absolute inset-0 hidden lg:block h-full w-full object-cover">

		<!-- Mobile Background -->
		<img src="<?php echo esc_url( $bg_mobile ); ?>"
		     alt="" class="pointer-events-none absolute inset-0 lg:hidden h-full w-full object-cover">

		<div class="relative mx-auto flex h-full max-w-[1200px] flex-col items-start justify-center px-4 text-left xl:px-0">
			<h1 class="font-playfair text-[28px] font-bold uppercase leading-[1.2] text-[#272727] sm:text-[36px] lg:text-[48px]">
				<?php echo esc_html( $heading ); ?>
			</h1>
			<img src="<?php echo esc_url( $divider ); ?>" alt="divider" class="w-[114px] h-[22px] mt-6 mb-2" />
			<p class="mt-4 max-w-md text-base text-[#182028] leading-[1.6]">
				<?php echo esc_html( $text ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-8 inline-flex w-full h-[52px] md:max-w-[244px] items-center justify-center gap-2 rounded-[6px] bg-[#860000] font-light text-base text-white transition hover:bg-[#650D10]">
				На главную
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/arr2.svg" alt="arrow" class="h-4 w-4">
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
