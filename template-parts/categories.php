<?php
/**
 * Categories Section
 *
 * @package BelGranit
 */

$heading  = get_field( 'categories_heading' ) ?: 'Выберите что вам нужно';
$items    = get_field( 'categories_items' );

if ( empty( $items ) ) :
	return;
endif;
?>
<!-- Categories -->
<section id="catalog" class="mx-auto max-w-[1200px] sm:px-[10px] lg:px-0 py-16 lg:py-20">
  <div class="mb-11 flex flex-col items-center gap-5">
    <h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/divider.svg" alt="" class="h-[22px] w-[114px]" width="114" height="22">
  </div>
  <div class="grid grid-cols-2 items-end gap-2 xl:grid-cols-4 xl:gap-5">
    <?php foreach ( $items as $item ) :
      $image       = $item['category_image'] ?? '';
      $title       = $item['category_title'] ?? '';
      $description = $item['category_description'] ?? '';
      $link        = $item['category_link'] ?? '';
      $link_url    = $link['url'] ?? '#';
      $link_target = $link['target'] ?? '_self';
    ?>
    <article class="group relative overflow-hidden rounded-md shadow-[0_4px_10px_rgba(0,0,0,0.1)]">
      <div class="relative h-[374px] xl:h-[530px]">
        <?php if ( $image ) : ?>
          <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/60 to-white/90"></div>
        <div class="absolute bottom-5 left-5 max-w-[220px]">
          <?php if ( $title ) : ?>
            <h3 class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $title ); ?></h3>
          <?php endif; ?>
          <?php if ( $description ) : ?>
            <p class="mt-2 text-[12px] text-charcoal"><?php echo esc_html( $description ); ?></p>
          <?php endif; ?>
          <div class="mt-5 h-px w-12 bg-[#860000]"></div>
          <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="mt-5 inline-flex items-center gap-3 font-manrope text-sm font-bold text-ink hover:text-[#860000]">
            Смотреть
        <img src="<?php echo get_template_directory_uri() ?>/img/arr.svg" alt="arrow" />
          </a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>
