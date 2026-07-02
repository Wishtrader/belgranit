<?php
/**
 * Template Name: Improvement 
 * The template for improvement page.
 *
 * @package BelGranit
 */

$page = get_page_by_path( 'improvement' );
$page_id = $page ? $page->ID : 0;

$hero_bg        = $page_id ? get_field( 'improvement_hero_bg', $page_id ) : '';
$hero_title     = $page_id ? ( get_field( 'improvement_hero_title', $page_id ) ?: 'Благоустройство' ) : 'Благоустройство';
$hero_subtitle  = $page_id ? get_field( 'improvement_hero_subtitle', $page_id ) : '';

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Banner -->
	<section class="relative h-[200px] sm:h-[240px] lg:h-[377px] overflow-hidden mt-[72px] lg:mt-0 bg-cover bg-center bg-no-repeat" <?php if ( $hero_bg ) : ?>style="background-image: url('<?php echo esc_url( $hero_bg ); ?>');"<?php endif; ?>>
		<div class="relative z-10 h-full max-w-[1200px] mx-auto flex flex-col justify-center">
			<nav class="font-body text-[12px] text-[#606060] mb-10 flex flex-wrap items-center gap-x-1 mt-[60px] lg:mt-[136px]">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-black transition-colors">Главная</a>
				<span class="mx-1">/</span>
				<span class="text-[#606060]"><?php echo esc_html( $hero_title ); ?></span>
			</nav>
			<h1 class="font-playfair text-[28px] sm:text-[36px] font-bold text-[#272727] uppercase leading-tight mb-2 max-w-[680px]">
				<?php echo esc_html( $hero_title ); ?>
			</h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="font-manrope font-normal text-lg text-[#4c4c4c]">
					<?php echo esc_html( $hero_subtitle ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Consultation Section -->
	<?php
	$consult_bg       = get_field( 'product_consult_bg', 'options' );
	$consult_title    = get_field( 'product_consult_title', 'options' ) ?: 'Остались вопросы?';
	$consult_icon     = get_field( 'product_consult_icon', 'options' );
	$consult_text     = get_field( 'product_consult_text', 'options' ) ?: 'Оставьте заявку. Менеджер перезвонит в ближайшее время.';
	$consult_btn_text = get_field( 'product_consult_btn_text', 'options' ) ?: 'Получить консультацию';
	$consult_btn_link = get_field( 'product_consult_btn_link', 'options' ) ?: '#callback';
	$consult_features = get_field( 'product_consult_features', 'options' );
	?>

	<div class="relative py-12 lg:py-20" <?php if ( $consult_bg ) : ?>style="background-image: url('<?php echo esc_url( $consult_bg ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>

		<div class="relative max-w-[1200px] mx-auto">
			<div class="flex flex-col md:flex-row justify-between">

				<!-- Left: CTA -->
				<div>
					<h2 class="font-playfair text-[24px] sm:text-[28px] lg:text-[36px] font-bold text-ink uppercase leading-[1.2] mb-4">
						<?php echo esc_html( $consult_title ); ?>
					</h2>

					<?php if ( $consult_icon ) : ?>
						<div class="flex items-center gap-3 mb-6">
							<img src="<?php echo esc_url( $consult_icon ); ?>" alt="" class="">
						</div>
					<?php endif; ?>

					<p class="text-gray-600 font-body leading-[1.4] mb-10 max-w-md">
						<?php echo esc_html( $consult_text ); ?>
					</p>

					<a
						href="<?php echo esc_url( $consult_btn_link ); ?>"
						class="inline-flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 lg:w-[344px] text-white text-base rounded-[6px] px-8 py-4 transition-colors font-body"
					>
						<?php echo esc_html( $consult_btn_text ); ?>
					<img src="<?php echo get_template_directory_uri(); ?>/img/arr2.svg" alt="arrow" class="" />
					</a>
				</div>

				<!-- Right: Features -->
				<?php if ( ! empty( $consult_features ) ) : ?>
					<div class="space-y-10">
						<?php foreach ( $consult_features as $feature ) :
							$feat_icon  = $feature['product_consult_feat_icon'] ?? '';
							$feat_title = $feature['product_consult_feat_title'] ?? '';
							$feat_desc  = $feature['product_consult_feat_desc'] ?? '';
							if ( ! $feat_title ) continue;
						?>
							<div class="flex items-center gap-4 lg:w-[390px]">
								<div class="w-16 h-16 rounded-full border border-[#860000] flex items-center justify-center shrink-0">
										<img src="<?php echo esc_url( $feat_icon ); ?>" alt="" class="w-8 h-8">
								</div>
								<div>
									<h3 class="font-manrope font-bold text-[#182028] text-lg mb-1"><?php echo esc_html( $feat_title ); ?></h3>
									<p class="text-gray-500 font-body text-sm leading-[1.4] max-w-[250px]"><?php echo esc_html( $feat_desc ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

</main>

<?php get_footer(); ?>



