<?php
/**
 * Portfolio Section
 *
 * @package BelGranit
 */

$heading     = get_field( 'portfolio_heading' ) ?: 'Наши работы';
$description = get_field( 'portfolio_description' ) ?: 'Каждое изделие — это уважение к памяти и внимание к деталям';
$images      = get_field( 'portfolio_images' );
$cta_text    = get_field( 'portfolio_cta_text' ) ?: 'Смотреть все работы';
$cta_link    = get_field( 'portfolio_cta_link' );
$cta_url     = $cta_link['url'] ?? '#works';
$cta_target  = $cta_link['target'] ?? '_self';
?>
<!-- Portfolio -->
<section id="works" class="mx-auto max-w-[1200px] px-4 py-16 lg:py-20 xl:px-0">
	<div class="mb-11 flex flex-col items-center gap-5 text-center">
		<h2 class="font-playfair text-3xl text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="h-[22px] w-[114px]">
		<p class="text-base text-charcoal"><?php echo esc_html( $description ); ?></p>
	</div>
	<?php if ( ! empty( $images ) ) : ?>
	<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
		<?php foreach ( $images as $item ) :
			$image = $item['portfolio_image'] ?? '';
			$alt   = $item['portfolio_alt'] ?? 'Пример работы';
		?>
			<?php if ( $image ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="aspect-square rounded-md object-cover">
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div class="mt-11 flex justify-center">
		<a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo esc_attr( $cta_target ); ?>" class="inline-flex items-center gap-3 rounded-md border border-brand px-8 py-4 text-base font-semibold uppercase text-brand transition hover:bg-brand hover:text-white">
			<?php echo esc_html( $cta_text ); ?> →
		</a>
	</div>
</section>
