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
			<h2 class="font-heading text-[26px] font-bold text-ink lg:text-4xl">
				<?php echo wp_kses_post( $heading ); ?>
			</h2>
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/divider.svg" alt="" class="my-5 h-[22px] w-[114px]">
			<p class="max-w-md text-base font-light text-ink"><?php echo esc_html( $description ); ?></p>
			<?php if ( ! empty( $features ) ) : ?>
			<ul class="mt-10 space-y-7">
				<?php foreach ( $features as $item ) :
					$icon        = $item['consultation_feature_icon'] ?? '';
					$title       = $item['consultation_feature_title'] ?? '';
					$description = $item['consultation_feature_description'] ?? '';
				?>
				<li class="flex gap-3 flex items-center">
					<span class="flex h-18 w-18 shrink-0 items-center justify-center rounded-full bg-brand text-white">
						<?php if ( $icon ) : ?>
							<img src="<?php echo esc_url( $icon ); ?>" alt="icon" class="h-18 w-18">
						<?php else : ?>
							<svg class="h-8 w-8" viewBox="0 0 32 32" fill="none"><path d="M8 24c4-8 12-8 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="16" cy="12" r="4" stroke="currentColor" stroke-width="2"/></svg>
						<?php endif; ?>
					</span>
					<div class="max-w-[373px]" >
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
		<div class="rounded-2xl bg-white/80 p-8 shadow-lg lg:p-10 lg:ml-8">
			<h3 class="font-manrope text-lg font-bold text-ink"><?php echo esc_html( $form_heading ); ?></h3>
			<form class="mt-8 space-y-5" action="#" method="post">
				<label class="block">
					<span class="sr-only">Имя</span>
					<input type="text" name="name" placeholder="Имя" required class="h-[54px] w-full rounded-md border border-black/20 px-4 text-sm outline-none transition focus:border-brand">
				</label>
				<label class="block">
					<span class="sr-only">Телефон</span>
					<input type="tel" id="consultation-phone" name="phone" placeholder="+375 (__) ___-__-__" required class="h-[54px] w-full rounded-md border border-black/20 px-4 text-sm outline-none transition focus:border-brand">
				</label>
				<label class="block">
					<span class="sr-only">Комментарий</span>
					<textarea name="message" rows="4" placeholder="Комментарий" class="w-full rounded-md border border-black/20 px-4 py-4 text-sm outline-none transition focus:border-brand"></textarea>
				</label>
				<label class="flex items-start gap-3 text-sm text-[#6f6f6f]">
					<input type="checkbox" required checked class="checkbox-custom mt-1 h-5 w-5 cursor-pointer appearance-none rounded border border-[#860000] bg-white focus:ring-2 focus:ring-[#860000] focus:ring-offset-0">
					<span class="text-base">Соглашаюсь с <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" target="_blank" class="underline hover:text-[#860000] transition-colors">политикой обработки персональных данных</a></span>
				</label>
				<button type="submit" class="flex h-[54px] w-full items-center justify-center gap-2 rounded-[6px] bg-[#860000] text-base text-white transition hover:bg-[#650D10]">
					<?php echo esc_html( $form_submit ); ?> →
				</button>
			</form>
			<?php if ( ! empty( $benefits ) ) : ?>
			<ul class="mt-8 flex flex-wrap gap-8 border-t border-[#724145] pt-5 text-xs">
				<?php foreach ( $benefits as $item ) :
					$icon      = $item['consultation_benefit_icon'] ?? '';
					$highlight = $item['consultation_benefit_highlight'] ?? '';
					$text      = $item['consultation_benefit_text'] ?? '';
				?>
				<li class="flex items-start gap-2">
					<?php if ( $icon ) : ?>
						<img src="<?php echo esc_url( $icon ); ?>" alt="" class="mt-0.5 h-8 w-8 shrink-0">
					<?php endif; ?>
					<div>
						<?php if ( $highlight ) : ?>
							<div class="text-[#860000]"><?php echo esc_html( $highlight ); ?></div>
						<?php endif; ?>
						<?php if ( $text ) : ?>
							<div class="text-charcoal"><?php echo esc_html( $text ); ?></div>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
</section>

<style>
.checkbox-custom:checked {
	background-image: url("/wp-content/themes/belgranit/img/check.svg");
	background-size: 14px;
	background-position: center;
	background-repeat: no-repeat;
}
</style>
<script>
(function() {
	var phoneInput = document.getElementById('consultation-phone');
	if (!phoneInput) return;

	var prefix = '+375 ';

	function formatPhone(val) {
		var digits = val.replace(/\D/g, '');
		if (digits.startsWith('375')) {
			digits = digits.substring(3);
		}
		digits = digits.substring(0, 9);

		var formatted = prefix;
		if (digits.length > 0) formatted += '(' + digits.substring(0, 2);
		if (digits.length >= 2) formatted += ') ';
		if (digits.length > 2) formatted += digits.substring(2, 5);
		if (digits.length >= 5) formatted += '-';
		if (digits.length > 5) formatted += digits.substring(5, 7);
		if (digits.length >= 7) formatted += '-';
		if (digits.length > 7) formatted += digits.substring(7, 9);

		return formatted;
	}

	phoneInput.addEventListener('input', function(e) {
		var pos = this.selectionStart;
		var oldLen = this.value.length;
		this.value = formatPhone(this.value);
		var newLen = this.value.length;
		this.setSelectionRange(pos + (newLen - oldLen), pos + (newLen - oldLen));
	});

	phoneInput.addEventListener('focus', function() {
		if (!this.value) {
			this.value = prefix;
		}
	});

	phoneInput.addEventListener('blur', function() {
		if (this.value === prefix) {
			this.value = '';
		}
	});

	phoneInput.addEventListener('keydown', function(e) {
		if (e.key === 'Backspace' && this.value === prefix) {
			e.preventDefault();
		}
	});
})();
</script>
