<?php
/**
 * Process Section
 *
 * @package BelGranit
 */

$heading = get_field( 'process_heading' ) ?: 'Берем все этапы на себя';
$steps   = get_field( 'process_steps' );

if ( empty( $steps ) ) :
	return;
endif;
?>
<!-- Process -->
<section class="relative overflow-hidden bg-muted py-16 lg:py-20">
	<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/bg-texture.png" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
	<div class="relative mx-auto max-w-[1200px] px-4 xl:px-0">
		<div class="mb-11 flex flex-col items-center gap-5">
			<h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		</div>
		<ol class="grid md:grid-cols-2 xl:grid-cols-4 lg:gap-10">
			<?php
			$step_count = count( $steps );
			foreach ( $steps as $index => $item ) :
				$number      = $item['process_step_number'] ?? '';
				$icon        = $item['process_step_icon'] ?? '';
				$title       = $item['process_step_title'] ?? '';
				$description = $item['process_step_description'] ?? '';
			?>
			<li class="relative text-center flex flex-col lg:pl-8">
				<?php if ( $index < $step_count - 1 ) : ?>
				<div class="absolute xl:border xl:border-[1px] xl:border-[#650D10]/20 w-full left-40 top-20"></div>
				<?php endif; ?>

				<?php if ( $number ) : ?>
					<span class="absolute left-6 lg:-left-4 font-manrope text-[64px] font-bold leading-[76px] text-[#650D10]/20"><?php echo esc_html( $number ); ?></span>
				<?php endif; ?>
				<div class="relative mx-auto mt-2 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-md lg:mt-8">
						<img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-10 w-10">
				</div>
				<img src="<?php echo get_template_directory_uri(); ?>/img/bullet.svg" alt="bullet" class="h-2.5 w-2.5 mx-auto mt-4" />
				<?php if ( $title ) : ?>
					<h3 class="mt-4 font-manrope text-lg lg:text-2xl font-bold leading-[1.2] text-[#272727]"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>
				<?php if ( $description ) : ?>
					<p class="mt-2 text-sm leading-[1.4] text-charcoal"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
