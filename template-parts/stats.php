<?php
/**
 * Stats Section
 *
 * @package BelGranit
 */

$bg_image = get_field( 'stats_bg_image' );
$items    = get_field( 'stats_items' );

if ( empty( $items ) ) :
	return;
endif;
?>
<!-- Stats -->
<section class="relative overflow-hidden bg-muted py-16 lg:py-20">
	<?php if ( $bg_image ) : ?>
		<img src="<?php echo esc_url( $bg_image ); ?>" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
	<?php endif; ?>
	<div class="relative mx-auto grid max-w-[1200px] gap-10 px-4 md:grid-cols-3 xl:px-0">
		<?php foreach ( $items as $item ) :
			$icon        = $item['stats_icon'] ?? '';
			$value       = $item['stats_value'] ?? '';
			$label       = $item['stats_label'] ?? '';
			$description = $item['stats_description'] ?? '';
		?>
		<div class="text-center">
			<div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-brand text-white">
				<?php if ( $icon ) : ?>
					<img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-10 w-10">
				<?php else : ?>
					<svg class="h-10 w-10" viewBox="0 0 40 40" fill="none"><path d="M8 30h24M12 30V14l8-4 8 4v16" stroke="currentColor" stroke-width="2"/></svg>
				<?php endif; ?>
			</div>
			<?php if ( $value ) : ?>
				<p class="mt-4 font-playfair text-5xl font-bold text-charcoal"><?php echo esc_html( $value ); ?></p>
			<?php endif; ?>
			<?php if ( $label ) : ?>
				<p class="mt-2 text-base text-[#2b2b2b]"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<?php if ( $description ) : ?>
				<p class="mx-auto mt-2 max-w-xs text-sm text-charcoal"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
	</div>
</section>
