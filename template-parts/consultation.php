<?php
/**
 * Consultation Section
 *
 * @package BelGranit
 */

$heading        = get_field( 'consultation_heading' ) ?: 'Не знаете,<br>какой памятник выбрать?';
$description    = get_field( 'consultation_description' ) ?: 'Мы поможем подобрать вариант с учетом бюджета, пожеланий и особенностей участка';
$bg_image       = get_field( 'consultation_bg' );
$features       = get_field( 'consultation_features' );
$form_heading   = get_field( 'consultation_form_heading' ) ?: 'Получите бесплатную консультацию';
$form_policy    = get_field( 'consultation_form_policy' ) ?: 'Соглашаюсь с политикой обработки персональных данных';
$form_submit    = get_field( 'consultation_form_submit' ) ?: 'Получить консультацию';
$benefits       = get_field( 'consultation_benefits' );
?>
<!-- Consultation -->
<section id="consultation" class="relative overflow-hidden bg-muted py-16 lg:py-20" <?php if ( $bg_image ) : ?>style="background-image: url('<?php echo esc_url( $bg_image ); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
	<?php if ( ! $bg_image ) : ?>
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/bg-texture.png" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
	<?php endif; ?>
	<div class="relative mx-auto grid max-w-[1200px] items-start gap-12 px-4 lg:grid-cols-2 xl:px-0">
		<div>
			<h2 class="font-playfair text-3xl leading-tight text-ink lg:text-4xl">
				<?php echo wp_kses_post( $heading ); ?>
			</h2>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="my-5 h-[22px] w-[114px]">
			<p class="max-w-md text-base text-ink"><?php echo esc_html( $description ); ?></p>
			<?php if ( ! empty( $features ) ) : ?>
			<ul class="mt-10 space-y-8">
				<?php foreach ( $features as $item ) :
					$icon        = $item['consultation_feature_icon'] ?? '';
					$title       = $item['consultation_feature_title'] ?? '';
					$description = $item['consultation_feature_description'] ?? '';
				?>
				<li class="flex gap-4">
					<span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-brand text-white">
						<?php if ( $icon ) : ?>
							<img src="<?php echo esc_url( $icon ); ?>" alt="" class="h-8 w-8">
						<?php else : ?>
							<svg class="h-8 w-8" viewBox="0 0 32 32" fill="none"><path d="M8 24c4-8 12-8 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="16" cy="12" r="4" stroke="currentColor" stroke-width="2"/></svg>
						<?php endif; ?>
					</span>
					<div>
						<?php if ( $title ) : ?>
							<p class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $title ); ?></p>
						<?php endif; ?>
						<?php if ( $description ) : ?>
							<p class="mt-2 text-sm text-ink/80"><?php echo esc_html( $description ); ?></p>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<div class="rounded-2xl bg-white/80 p-8 shadow-lg backdrop-blur-sm lg:p-10">
			<h3 class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $form_heading ); ?></h3>
			<form class="mt-8 space-y-5" action="#" method="post">
				<label class="block">
					<span class="sr-only">Имя</span>
					<input type="text" name="name" placeholder="Имя" required class="h-[54px] w-full rounded-md border border-black/20 px-4 text-sm outline-none transition focus:border-brand">
				</label>
				<label class="block">
					<span class="sr-only">Телефон</span>
					<input type="tel" name="phone" placeholder="Телефон" required class="h-[54px] w-full rounded-md border border-black/20 px-4 text-sm outline-none transition focus:border-brand">
				</label>
				<label class="block">
					<span class="sr-only">Комментарий</span>
					<textarea name="message" rows="4" placeholder="Комментарий" class="w-full rounded-md border border-black/20 px-4 py-4 text-sm outline-none transition focus:border-brand"></textarea>
				</label>
				<label class="flex items-start gap-3 text-sm text-[#6f6f6f]">
					<input type="checkbox" required class="mt-1 h-5 w-5 rounded border-[#d1d0d0] text-brand focus:ring-brand">
					<span><?php echo esc_html( $form_policy ); ?></span>
				</label>
				<button type="submit" class="flex h-[54px] w-full items-center justify-center gap-2 rounded-md bg-brand text-base text-white transition hover:bg-brand-dark">
					<?php echo esc_html( $form_submit ); ?> →
				</button>
			</form>
			<?php if ( ! empty( $benefits ) ) : ?>
			<ul class="mt-8 flex flex-wrap gap-8 border-t border-[#724145] pt-5 text-xs">
				<?php foreach ( $benefits as $item ) :
					$highlight = $item['consultation_benefit_highlight'] ?? '';
					$text      = $item['consultation_benefit_text'] ?? '';
				?>
				<li>
					<?php if ( $highlight ) : ?>
						<span class="text-brand"><?php echo esc_html( $highlight ); ?></span><br>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<span class="text-charcoal"><?php echo esc_html( $text ); ?></span>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
