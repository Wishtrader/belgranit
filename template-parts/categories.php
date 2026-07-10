<?php
/**
 * Categories Section
 *
 * @package BelGranit
 */

$heading  = get_field( 'categories_heading' ) ?: 'Выберите что вам нужно';
$items    = get_field( 'categories_items' );

$link_map = array(
	'Памятники'      => array( 'type' => 'product_cat', 'slug' => 'pamyatniki' ),
	'Благоустройство' => array( 'type' => 'page', 'slug' => 'improvement' ),
	'Ограды'         => array( 'type' => 'product_cat', 'slug' => 'ogradi' ),
	'Оформление'     => array( 'type' => 'product_cat', 'slug' => 'oformlenie' ),
);

if ( empty( $items ) ) :
	return;
endif;
?>
<!-- Categories -->
<section id="catalog" class="mx-auto max-w-[1200px] px-[10px] lg:px-0 py-16 lg:py-20">
  <div class="mb-11 flex flex-col items-center gap-5">
    <h2 class="font-playfair font-bold text-center text-[26px] text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/divider.svg" alt="" class="h-[22px] w-[114px]" width="114" height="22">
  </div>
  <div class="grid grid-cols-2 items-end gap-2 xl:grid-cols-4 xl:gap-5">
    <?php foreach ( $items as $item ) :
      $image       = $item['category_image'] ?? '';
      $title       = $item['category_title'] ?? '';
      $description = $item['category_description'] ?? '';

      $link_url = '#';
      if ( $title && isset( $link_map[ $title ] ) ) {
        $entry = $link_map[ $title ];
        if ( 'page' === $entry['type'] ) {
          $page = get_page_by_path( $entry['slug'] );
          if ( $page ) {
            $link_url = get_permalink( $page );
          }
        } elseif ( 'product_cat' === $entry['type'] ) {
          $term = get_term_by( 'slug', $entry['slug'], 'product_cat' );
          if ( $term && ! is_wp_error( $term ) ) {
            $link_url = get_term_link( $term );
            if ( is_wp_error( $link_url ) ) {
              $link_url = '#';
            }
          }
        }
      }
    ?>
    <article class="group relative overflow-hidden rounded-md shadow-[0_4px_10px_rgba(0,0,0,0.1)]">
      <div class="relative h-[374px] xl:h-[530px]">
        <?php if ( $image ) : ?>
          <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <?php endif; ?>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-b from-transparent to-white/90"></div>
        <div class="absolute bottom-0 left-0 right-0 p-5">
          <div class="max-w-[220px]">
            <?php if ( $title ) : ?>
              <h3 class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $title ); ?></h3>
            <?php endif; ?>
            <div class="mt-2 h-[32px]">
              <?php if ( $description ) : ?>
                <p class="text-[12px] text-charcoal line-clamp-2"><?php echo esc_html( $description ); ?></p>
              <?php endif; ?>
            </div>
            <div class="mt-5 h-px w-12 bg-[#860000]"></div>
            <a href="<?php echo esc_url( $link_url ); ?>" class="mt-5 inline-flex items-center gap-3 font-manrope text-sm font-bold text-ink hover:text-[#860000]">
              Смотреть
              <img src="<?php echo get_template_directory_uri() ?>/img/arr.svg" alt="arrow" />
            </a>
          </div>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>
