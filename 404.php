<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package BelGranit
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="relative h-[728px] lg:h-[800px] overflow-hidden">
		<!-- Desktop Background -->
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/404bg.png"
		     alt="" class="pointer-events-none absolute inset-0 hidden lg:block h-full w-full object-cover">

		<!-- Mobile Background -->
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/404bgm.png"
		     alt="" class="pointer-events-none absolute inset-0 lg:hidden h-full w-full object-cover">

		<div class="relative mx-auto flex h-full max-w-[1200px] flex-col items-start justify-center px-4 text-left xl:px-0">
			<span class="font-playfair text-[120px] font-bold leading-none text-[#650D10] sm:text-[160px] lg:text-[200px]">
				404
			</span>
			<h1 class="font-playfair mt-10 text-2xl font-bold text-[#272727] lg:text-4xl">
				Страница не найдена
			</h1>
			<img src="<?php echo get_template_directory_uri(); ?>/img/divider.svg" alt="devider" class="w-[114px] h-[22px] mt-6 mb-2" />
			<p class="mt-4 max-w-md text-base text-[#182028] leading-[1.6]">
				Здесь могла быть страница, но она не найдена.<br />
				Давайте вернёмся к тому, что действительно важно.
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-8 inline-flex w-full  h-[52px] md:max-w-[244px] items-center justify-center gap-2 rounded-[6px] bg-[#860000] font-light text-base text-white transition hover:bg-[#650D10]">
				На главную
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/arr2.svg" alt="arrow" class="h-4 w-4">
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
