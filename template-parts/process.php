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
			<h2 class="font-playfair text-center text-3xl text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		</div>
		<ol class="grid gap-10 md:grid-cols-2 xl:grid-cols-4">
			<?php foreach ( $steps as $item ) :
				$number      = $item['process_step_number'] ?? '';
				$icon        = $item['process_step_icon'] ?? '';
				$title       = $item['process_step_title'] ?? '';
				$description = $item['process_step_description'] ?? '';
			?>
			<li class="relative text-center">
				<?php if ( $number ) : ?>
					<span class="font-manrope text-[64px] font-bold leading-[76px] text-brand-dark/20"><?php echo esc_html( $number ); ?></span>
				<?php endif; ?>
				<div class="mx-auto mt-2 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-md">
					<?php if ( $icon ) : ?>
						<img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-10 w-10">
					<?php else : ?>
						<svg class="h-10 w-10 text-brand" viewBox="0 0 40 40" fill="none"><path d="M10 28V14l10-5 10 5v14" stroke="currentColor" stroke-width="2"/><circle cx="20" cy="18" r="3" stroke="currentColor" stroke-width="2"/></svg>
					<?php endif; ?>
				</div>
				<?php if ( $title ) : ?>
					<h3 class="mt-4 font-manrope text-xl font-bold"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>
				<?php if ( $description ) : ?>
					<p class="mt-2 text-sm leading-relaxed text-charcoal"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
