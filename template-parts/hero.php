<?php
/**
 * Template part: Hero section
 *
 * @package BelGranit
 */

// Read hero fields with fallbacks
$hero_bg_image    = get_field( 'hero_bg_image' );
$hero_heading     = get_field( 'hero_heading' ) ?: "Изготовление памятников<br>под ключ в Могилёве";
$hero_subtitle    = get_field( 'hero_subtitle' ) ?: 'Спокойно и без лишних хлопот: от подбора до установки';
$hero_description = get_field( 'hero_description' ) ?: 'с согласованием на каждом этапе';
$hero_features    = get_field( 'hero_features' );

$default_bg = get_template_directory_uri() . '/img/hero-bg.jpg';
?>
<section class="w-full lg:h-[800px] lg:max-h-screen overflow-hidden bg-[url('<?php echo esc_url( $hero_bg_image ); ?>')] bg-cover bg-bottom lg:bg-center pb-10">

	<!-- Content -->
	<div class="relative max-w-[1200px] md:mx-auto px-2 mx-[10px] mt-[160px] lg:mt-[220px] text-center flex flex-col items-center justify-start border-[1px] border-white pt-10 pb-8 md:pb-0">

		<!-- Heading -->
		<h1 class="font-heading text-[36px] lg:text-[56px] font-normal text-white !leading-[120%] mb-6 lg:mb-11">
			<?php echo wp_kses_post( $hero_heading ); ?>
		</h1>

		<!-- Subtitle -->
		<p class="font-heading text-lg md:text-[26px] font-normal !leading-[1.2] text-[#f0f0f0] tracking-normal mx-auto mb-6 lg:mb-10">
			<?php echo esc_html( $hero_subtitle ); ?><br class="hidden sm:block">
			<?php echo esc_html( $hero_description ); ?>
		</p>

		<!-- Buttons -->
		<div class="relative w-full sm:w-auto z-10 flex flex-col sm:flex-row items-center justify-center gap-4 lg:gap-5 lg:mb-[-27px] lg:overflow-hidden lg:px-5 lg:backdrop-blur-sm lg:rounded-[24px]">
			<a href="#calculator" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#860000] hover:bg-red-600 text-white text-base font-semibold uppercase tracking-wide px-8 py-4 rounded-[6px] transition-colors">
				Рассчитать стоимость
			</a>
			<a href="#catalog" class="w-full sm:w-auto inline-flex items-center justify-center bg-white/80 hover:bg-gray-100 text-gray-900 text-base font-semibold uppercase tracking-wide px-8 py-4 rounded-[6px] transition-colors">
				Перейти в каталог
			</a>
		</div>
	</div>

	<!-- Features bar -->
	<?php if ( $hero_features && count( $hero_features ) ) : ?>
	<div class="mt-10 sm:mt-14 lg:mt-[83px] px-[10px] lg:px-0">
		<div class="bg-white/60 rounded-[8px] w-full lg:max-w-[996px] lg:h-[86px] mx-auto">
			<div class="flex flex-col lg:flex-row items-stretch h-full">
				<?php foreach ( $hero_features as $key => $feature ) : ?>
				<?php if ( $key > 0 ) : ?>
				<span class="lg:block w-px bg-white/20 self-center mx-2 h-[1px] w-full sm:h-[34px]"></span>
				<?php endif; ?>
				<div class="flex items-center gap-3 px-4 sm:px-6 py-4 lg:py-0 lg:justify-center lg:flex-1">
					<?php if ( $feature['hero_feature_icon'] ) : ?>
						<img src="<?php echo esc_url( $feature['hero_feature_icon'] ); ?>" alt="" class="w-[32px] h-[32px] shrink-0">
					<?php else : ?>
						<svg class="w-8 h-8 text-[#8B0000] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
							<path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
						</svg>
					<?php endif; ?>
					<span class="font-body text-sm sm:text-base font-semibold text-gray-900 uppercase tracking-wide">
						<?php echo nl2br( esc_html( $feature['hero_feature_title'] ) ); ?>
					</span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</section>
