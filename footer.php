<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BelGranit
 */

$contacts = belgranit_get_contacts();
?>

	<footer id="colophon" class="site-footer bg-[#2d2d2d]">
		<div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-10 sm:py-14 lg:py-16">
			<div class="flex flex-col lg:flex-row lg:items-start gap-10 lg:gap-8">

				<!-- Logo + Description -->
				<div class="lg:w-[280px] shrink-0">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2.5 mb-4" aria-label="<?php bloginfo( 'name' ); ?>">
						<?php belgranit_logo( 'h-8 sm:h-10 w-auto brightness-0 invert' ); ?>
					</a>
					<p class="font-body text-sm text-gray-400 leading-relaxed">
						Изготовление памятников и<br>
						благоустройство захоронений в<br>
						Могилёве
					</p>
				</div>

				<!-- Каталог -->
				<div class="lg:w-auto">
					<h3 class="font-body text-base font-bold text-white mb-4">Каталог</h3>
					<ul class="space-y-2.5 list-none m-0 p-0">
						<?php
						$catalog_items = array(
							array( 'title' => 'Памятники', 'url' => '#' ),
							array( 'title' => 'Благоустройство', 'url' => '#' ),
							array( 'title' => 'Ограды', 'url' => '#' ),
							array( 'title' => 'Оформление', 'url' => '#' ),
						);
						foreach ( $catalog_items as $item ) :
						?>
							<li>
								<a href="<?php echo esc_url( $item['url'] ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
									<?php echo esc_html( $item['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Информация -->
				<div class="lg:w-auto">
					<h3 class="font-body text-base font-bold text-white mb-4">Информация</h3>
					<ul class="space-y-2.5 list-none m-0 p-0">
						<?php
						$info_items = array(
							array( 'title' => 'Модели', 'url' => '#' ),
							array( 'title' => 'Примеры работ', 'url' => '#' ),
							array( 'title' => 'Контакты', 'url' => '#' ),
						);
						foreach ( $info_items as $item ) :
						?>
							<li>
								<a href="<?php echo esc_url( $item['url'] ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
									<?php echo esc_html( $item['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Контакты -->
				<div class="lg:w-auto lg:ml-auto">
					<h3 class="font-body text-base font-bold text-white mb-4">Контакты</h3>
					<ul class="space-y-2.5 list-none m-0 p-0">
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_1'] ) ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_1'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_2'] ) ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_2'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_3'] ) ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_3'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_4'] ) ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_4'] ); ?> (Производство)
							</a>
						</li>
						<li>
							<a href="mailto:<?php echo esc_attr( $contacts['email'] ); ?>" class="font-body text-sm text-gray-400 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['email'] ); ?>
							</a>
						</li>
					</ul>
				</div>

				<!-- Scroll to Top -->
				<div class="hidden lg:flex items-start">
					<button id="scroll-to-top" class="flex items-center justify-center w-10 h-10 bg-white rounded-[6px] hover:bg-gray-100 transition-colors" aria-label="Наверх">
						<svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
							<path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- Divider -->
			<div class="border-t border-gray-600 mt-10 pt-6">
				<div class="flex flex-col sm:flex-row items-center justify-between gap-4">
					<p class="font-body text-xs text-gray-400">
						&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. Все права защищены.
					</p>
					<div class="flex flex-wrap items-center gap-4 sm:gap-6">
						<a href="/cookie-policy" class="font-body text-xs text-gray-400 hover:text-white transition-colors">
							Политика обработки файлов cookie
						</a>
						<a href="/privacy-policy" class="font-body text-xs text-gray-400 hover:text-white transition-colors">
							Политика обработки персональных данных
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Mobile Scroll to Top -->
		<div class="lg:hidden fixed bottom-6 right-6 z-40">
			<button id="scroll-to-top-mobile" class="flex items-center justify-center w-10 h-10 bg-white rounded-[6px] shadow-lg hover:bg-gray-100 transition-colors" aria-label="Наверх">
				<svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
					<path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
				</svg>
			</button>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

	<script>
	(function() {
		function scrollToTop() {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		}

		var btnDesktop = document.getElementById('scroll-to-top');
		var btnMobile  = document.getElementById('scroll-to-top-mobile');

		if (btnDesktop) {
			btnDesktop.addEventListener('click', scrollToTop);
		}
		if (btnMobile) {
			btnMobile.addEventListener('click', scrollToTop);
		}
	})();
	</script>

	<script>
	function graniteSlider() {
		return {
			currentSlide: 0,
			mobileIndex: 0,
			touchStartX: 0,
			touchEndX: 0,

			touchStart(e) {
				this.touchStartX = e.changedTouches[0].screenX;
			},

			touchMove(e) {
				this.touchEndX = e.changedTouches[0].screenX;
			},

			touchEnd(e) {
				this.touchEndX = e.changedTouches[0].screenX;
				const diff = this.touchStartX - this.touchEndX;
				const maxIndex = this.$refs.mobileSlider.children.length - 1;

				if (Math.abs(diff) > 50) {
					if (diff > 0 && this.mobileIndex < maxIndex) {
						this.mobileIndex++;
					} else if (diff < 0 && this.mobileIndex > 0) {
						this.mobileIndex--;
					}
				}
			}
		};
	}
	</script>

<?php wp_footer(); ?>

</body>
</html>
