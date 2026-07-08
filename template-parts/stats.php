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
<section class="relative overflow-hidden py-16 lg:py-20">
	<?php if ( $bg_image ) : ?>
		<img src="<?php echo esc_url( $bg_image ); ?>" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover" aria-hidden="true">
	<?php endif; ?>
	<div class="relative mx-auto flex items-center justify-center flex-col lg:flex-row max-w-[1200px] gap-10">
		<?php
		$stats_count = count( $items );
		foreach ( $items as $index => $item ) :
			$icon        = $item['stats_icon'] ?? '';
			$value       = $item['stats_value'] ?? '';
			$label       = $item['stats_label'] ?? '';
			$description = $item['stats_description'] ?? '';
		?>
		<div class="text-center max-w-[234px]">
			<div class="mx-auto flex h-[76px] w-[76px] items-center justify-center rounded-full border-[1px] border-[#860000] text-white">
					<img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-10 w-10">
			</div>
				<p class="mt-4 font-playfair text-5xl font-bold text-charcoal"><?php echo esc_html( $value ); ?></p>
				<p class="mt-2 text-base text-[#2b2b2b]"><?php echo esc_html( $label ); ?></p>
				<p class="mx-auto mt-2 max-w-xs text-sm text-charcoal font-light"><?php echo esc_html( $description ); ?></p>
		</div>
		<?php if ( $index < $stats_count - 1 ) : ?>
		<div class="lg:border-[#724246] lg:border-l-[1px] lg:h-[233px] lg:mx-6"></div>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
