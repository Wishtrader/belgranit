<?php
/**
 * 3D Mockup Section
 *
 * @package BelGranit
 */

$heading        = get_field( 'section_3d_heading' ) ?: '3D-макет';
$subtitle       = get_field( 'section_3d_subtitle' ) ?: 'Мы подготовим реалистичную 3D-визуализацию памятника бесплатно';
$image          = get_field( 'section_3d_image' );
$features_title = get_field( 'section_3d_features_title' ) ?: 'Почему вам нужен 3D-макет:';
$features       = get_field( 'section_3d_features' );
$cta_text       = get_field( 'section_3d_cta_text' ) ?: 'Получить 3D-макет бесплатно';
$cta_link       = get_field( 'section_3d_cta_link' );
$cta_url        = $cta_link['url'] ?? '#consultation';
$cta_target     = $cta_link['target'] ?? '_self';
?>
<!-- 3D mockup -->
<section id="models" class="relative overflow-hidden bg-[#f5f4f3] py-16 lg:py-20">
  <div class="relative mx-auto max-w-[1200px] px-[10px] xl:px-0">
    <div class="mb-11 flex flex-col items-center gap-5 text-center">
      <h2 class="font-heading text-[26px] font-bold text-ink lg:text-4xl"><?php echo esc_html( $heading ); ?></h2>
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/divider.svg" alt="" class="h-[22px] w-[114px]">
      <p class="max-w-2xl text-base text-ink"><?php echo esc_html( $subtitle ); ?></p>
    </div>
    <div class="flex flex-col md:flex-row justify-between">
      <div class="overflow-hidden rounded-md bg-white shadow-sm">
        <?php if ( $image ) : ?>
          <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $heading ); ?>" class="h-full w-full object-contain w-full lg:h-[453px]">
        <?php endif; ?>
      </div>
      <div class="border border-r-[#724246]/20 h-[453px] mx-20"></div>
      <div>
        <h3 class="font-manrope text-2xl font-bold text-charcoal"><?php echo esc_html( $features_title ); ?></h3>
        <?php if ( ! empty( $features ) ) : ?>
          <ul class="mt-10 space-y-8">
            <?php foreach ( $features as $item ) :
              $icon        = $item['feature_icon'] ?? '';
              $title       = $item['feature_title'] ?? '';
              $description = $item['feature_description'] ?? '';
            ?>
            <li class="flex gap-4">
                <?php if ( $icon ) : ?>
                  <img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-[64px] w-[64px]">
                <?php else : ?>
                  <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none"><path d="M6 24l8-10 5 6 7-10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php endif; ?>
              <div>
                <?php if ( $title ) : ?>
                  <p class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $title ); ?></p>
                <?php endif; ?>
                <?php if ( $description ) : ?>
                  <p class="mt-2 text-sm leading-relaxed text-ink/80"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
    <div class="mt-12 flex justify-center">
      <a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo esc_attr( $cta_target ); ?>" class="inline-flex font-light items-center gap-2 rounded-md bg-[#860000] px-8 py-4 text-base text-white transition hover:bg-[#650d10]">
        <?php echo esc_html( $cta_text ); ?>
  <img src="<?php echo get_template_directory_uri();  ?>/img/arr2.svg" alt="arrow" />
      </a>
    </div>
  </div>
</section>
