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
		<div class="max-w-[1200px] mx-auto px-4 lg:px-0 sm:px-6 py-10 sm:py-14 lg:py-12 relative">
			<div class="flex flex-col lg:flex-row lg:items-start gap-10 lg:gap-8">

				<!-- Logo + Description -->
				<div class="lg:w-[264px] shrink-0">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2.5 mb-4 max-w-[203px]" aria-label="<?php bloginfo( 'name' ); ?>">
						<?php belgranit_logo( 'w-auto brightness-0 invert' ); ?>
					</a>
					<p class="font-body text-sm text-white/70 leading-relaxed">
						Изготовление памятников и<br>
						благоустройство захоронений в<br>
						Могилёве
					</p>
				</div>

				<!-- Каталог -->
				<div class="lg:w-[264px]">
					<h3 class="font-body text-base font-bold text-white mb-4">Каталог</h3>
					<ul class="space-y-2.5 list-none m-0 p-0">
						<?php
						$catalog_slugs = array( 'pamyatniki', 'blagoustrojstvo', 'ogradi', 'oformlenie' );
						$catalog_titles = array( 'Памятники', 'Благоустройство', 'Ограды', 'Оформление' );
						foreach ( $catalog_slugs as $idx => $slug ) :
							$term = get_term_by( 'slug', $slug, 'product_cat' );
							$url = $term ? get_term_link( $term ) : '#';
							if ( is_wp_error( $url ) ) $url = '#';
						?>
							<li>
								<a href="<?php echo esc_url( $url ); ?>" class="font-body text-sm text-white/70 hover:text-white transition-colors">
									<?php echo esc_html( $catalog_titles[ $idx ] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Информация -->
				<div class="lg:w-[264px]">
					<h3 class="font-body text-base font-bold text-white mb-4">Информация</h3>
					<ul class="space-y-2.5 list-none m-0 p-0">
					<?php
					$info_items = array(
						array( 'title' => 'Модели', 'url' => get_permalink( get_page_by_path( 'models' ) ) ?: '#' ),
						array( 'title' => 'Примеры работ', 'url' => get_permalink( get_page_by_path( 'examples' ) ) ?: '#' ),
						array( 'title' => 'Контакты', 'url' => get_permalink( get_page_by_path( 'contacts' ) ) ?: '#' ),
					);
					foreach ( $info_items as $item ) :
						?>
							<li>
								<a href="<?php echo esc_url( $item['url'] ); ?>" class="font-body text-sm text-white/70 hover:text-white transition-colors">
									<?php echo esc_html( $item['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Контакты -->
				<div class="lg:w-[291px]">
					<h3 class="font-body text-base font-bold text-white mb-4">Контакты</h3>
					<ul class="space-y-[1px] list-none m-0 p-0">
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_1'] ) ); ?>" class="font-body text-base text-white/70 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_1'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_2'] ) ); ?>" class="font-body text-base text-white/70 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_2'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_3'] ) ); ?>" class="font-body text-base text-white/70 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_3'] ); ?>
							</a>
						</li>
						<li>
							<a href="tel:<?php echo esc_attr( belgranit_phone_link( $contacts['phone_4'] ) ); ?>" class="font-body text-base text-white/70 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['phone_4'] ); ?> (Производство)
							</a>
						</li>
						<li>
							<a href="mailto:<?php echo esc_attr( $contacts['email'] ); ?>" class="font-body text-sm text-white/70 hover:text-white transition-colors">
								<?php echo esc_html( $contacts['email'] ); ?>
							</a>
						</li>
						<li>
							<a href="https://yandex.ru/maps/?text=<?php echo urlencode( 'Могилёв, ул. Ямницкая 83в' ); ?>" target="_blank" rel="noopener noreferrer" class="font-body text-sm text-white/70 hover:text-white transition-colors">
								<b>Производство:</b><br /> г. Могилёв, ул. Ямницкая 83в
							</a>
						</li>
						<li>
							<a href="https://yandex.ru/maps/?text=<?php echo urlencode( 'г. Могилев, Пушкинский проспект 18' ); ?>" target="_blank" rel="noopener noreferrer" class="font-body text-sm text-white/70 hover:text-white transition-colors">
								<b>Магазин:</b><br />
								г. Могилев, Пушкинский проспект 18
							</a>
						</li>


					</ul>
				</div>

				<!-- Scroll to Top -->
				<div class="hidden lg:flex items-start absolute right-0 top-22">
					<button id="scroll-to-top" class="flex items-center justify-center w-11 h-11 bg-white rounded-[6px] hover:bg-gray-100 transition-colors" aria-label="Наверх">
						<img src="<?php echo get_template_directory_uri(); ?>/img/arrow-top.svg" alt="arrow top" />
					</button>
				</div>
			</div>

			<!-- Divider -->
			<div class="border-t border-gray-600 mt-10 pt-4">
				<div class="flex flex-col sm:flex-row items-center justify-between gap-5">
					<p class="font-body text-sm text-white/70 !font-light">
						&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. Все права защищены.
					</p>
					<div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
						<a href="/cookie-policy" class="font-body !font-light text-center text-sm text-white/70 hover:text-white transition-colors">
							Политика обработки файлов cookie
						</a>
						<a href="/privacy-policy" class="font-body !font-light text-center text-sm text-white/70 hover:text-white transition-colors">
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

<!-- Callback Popup -->
<div id="callback-popup" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/40 p-4">
	<div class="relative bg-white rounded-[6px] shadow-2xl w-full max-w-[478px] max-h-[640px] overflow-y-auto">
		<button id="callback-close" class="absolute top-5 right-5 text-gray-300 hover:text-gray-500 transition-colors" aria-label="Закрыть">
			<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
				<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
			</svg>
		</button>
		<div class="p-5">
			<h2 id="popup-title" class="font-playfair text-[26px] sm:text-[36px] font-bold text-ink leading-tight mb-3">Заказать звонок</h2>
			<p id="popup-desc" class="font-body font-light text-base text-[#182028] leading-[1.4] mb-3 pr-4">
				Оставьте ваши контакты. Мы свяжемся с вами в ближайшее время и ответим на все интересующие вас вопросы.
			</p>
			<form id="callback-form" class="space-y-5" method="post">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'belgranit_form_nonce' ); ?>">
				<input type="hidden" name="form_type" value="callback">
				<input type="text" name="name" placeholder="Имя" class="w-full p-4 border border-[#6f6f6f]/30 rounded-[6px] font-body text-[14px] text-ink placeholder:text-gray-400 focus:outline-none focus:border-[#860000] transition-colors">
				<input type="tel" id="callback-phone" name="phone" placeholder="+375 (__) ___-__-__" class="w-full p-4 border border-[#6f6f6f]/30 rounded-[6px] font-body text-[14px] text-ink placeholder:text-gray-400 focus:outline-none focus:border-[#860000] transition-colors">
				<textarea name="comment" rows="4" placeholder="Комментарий" class="w-full p-4 border border-[#6f6f6f]/30 rounded-[6px] font-body text-[14px] text-ink placeholder:text-gray-400 focus:outline-none focus:border-[#860000] transition-colors"></textarea>
				<label class="flex items-start gap-3 cursor-pointer">
					<input type="checkbox" name="consent" checked class="mt-1 w-5 h-5 accent-[#860000] rounded border-gray-300">
					<span class="font-body text-base text-[#6f6f6f] leading-[1.4]">Соглашаюсь с <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" target="_blank" class="underline hover:text-[#860000] transition-colors">политикой обработки персональных данных</a></span>
				</label>
				<button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#860000] hover:bg-red-700 text-white text-base font-body rounded-[6px] px-8 py-4 transition-colors">
					Заказать звонок
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
					</svg>
				</button>
			</form>
		</div>
	</div>
</div>

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

	<script>
	function relatedSlider() {
		return {
			currentSlide: 0,

			next() {
				const maxSlide = Math.ceil(this.$refs.slider.children.length / 4) - 1;
				if (this.currentSlide < maxSlide) {
					this.currentSlide++;
				}
			},

			prev() {
				if (this.currentSlide > 0) {
					this.currentSlide--;
				}
			}
		};
	}
	</script>



	<script>
	(function() {
		var popup    = document.getElementById('callback-popup');
		var closeBtn = document.getElementById('callback-close');
		var titleEl  = document.getElementById('popup-title');
		var descEl   = document.getElementById('popup-desc');

		var defaultTitle = 'Заказать звонок';
		var defaultDesc  = 'Оставьте ваши контакты. Мы свяжемся с вами в ближайшее время и ответим на все интересующие вас вопросы.';

		var calculateTitle = 'Рассчитать стоимость';
		var calculateDesc  = 'Оставьте ваши контакты. Рассчитаем стоимость с учётом вашего участка и пожеланий.';

		var consultTitle = 'Получить консультацию';
		var consultDesc  = 'Оставьте ваши контакты. Мы свяжемся с вами в ближайшее время и ответим на все интересующие вас вопросы.';

		function openPopup(e) {
			if (e) e.preventDefault();
			var btn = e.currentTarget;
			if (btn.dataset.popup === 'calculate') {
				titleEl.textContent = calculateTitle;
				descEl.textContent  = calculateDesc;
			} else if (btn.dataset.popup === 'consult') {
				titleEl.textContent = consultTitle;
				descEl.textContent  = consultDesc;
			} else {
				titleEl.textContent = defaultTitle;
				descEl.textContent  = defaultDesc;
			}
			popup.classList.remove('hidden');
			popup.classList.add('flex');
			document.body.style.overflow = 'hidden';
		}

		function closePopup() {
			popup.classList.add('hidden');
			popup.classList.remove('flex');
			document.body.style.overflow = '';
		}

		document.querySelectorAll('a[href="#callback"]').forEach(function(btn) {
			btn.addEventListener('click', openPopup);
		});

		if (closeBtn) {
			closeBtn.addEventListener('click', closePopup);
		}

		popup.addEventListener('click', function(e) {
			if (e.target === popup) closePopup();
		});

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
				closePopup();
			}
		});
	})();
	</script>

	<script>
	(function() {
		var phoneInput = document.getElementById('callback-phone');
		if (!phoneInput) return;

		var prefix = '+375 ';
		var digitsOnly = '';

		function formatPhone(val) {
			var digits = val.replace(/\D/g, '');
			if (digits.startsWith('375')) {
				digits = digits.substring(3);
			}
			digits = digits.substring(0, 9);
			digitsOnly = digits;

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

<!-- Cookie Consent Banner -->
<div id="cookie-consent" class="fixed bottom-0 left-0 right-0 z-[190] hidden">
	<div class="w-full bg-[#ffffff] backdrop-blur-sm border-t border-[724246]/20">
		<div class="max-w-[1200px] mx-auto px-4 py-4 sm:py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
			<p class="font-body text-sm sm:text-[14px] text-[#724246] leading-[1.5] text-left !font-light">
				Для удобства пользователей сайта используются файлы cookie. Продолжая просмотр этого сайта, вы соглашаетесь
				на обработку файлов cookie в соответствии с
				<a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>" class="underline hover:text-white transition-colors">Политикой обработки файлов cookie</a>
			</p>
			<div class="flex items-center gap-3 shrink-0">
				<button id="cookie-accept" class="w-full px-8 py-3 bg-[#860000] hover:bg-red-700 text-white font-body text-sm font-bold uppercase tracking-wide rounded-[4px] transition-colors">
					Принять
				</button>
				<button id="cookie-decline" class="px-8 py-3 border border-[#860000] text-[#860000] hover:bg-[#860000] hover:text-white font-body text-sm font-bold uppercase tracking-wide rounded-[4px] transition-colors bg-transparent">
					Отклонить
				</button>
			</div>
		</div>
	</div>
</div>

<script>
(function() {
	var COOKIE_KEY = 'cookie_consent';
	var banner = document.getElementById('cookie-consent');
	var acceptBtn = document.getElementById('cookie-accept');
	var declineBtn = document.getElementById('cookie-decline');

	if (!banner || !acceptBtn || !declineBtn) return;

	var consent = localStorage.getItem(COOKIE_KEY);

	if (consent === null) {
		banner.classList.remove('hidden');
	}

	function hideBanner() {
		banner.classList.add('hidden');
	}

	acceptBtn.addEventListener('click', function() {
		localStorage.setItem(COOKIE_KEY, 'accepted');
		hideBanner();
	});

	declineBtn.addEventListener('click', function() {
		localStorage.setItem(COOKIE_KEY, 'declined');
		hideBanner();
	});
})();
</script>

<script>
(function() {
	var thankYouUrl = '<?php echo esc_url( home_url( '/thank-you/' ) ); ?>';
	var ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	var nonce = '<?php echo wp_create_nonce( 'belgranit_form_nonce' ); ?>';

	function sendForm(form) {
		var formData = new FormData(form);
		formData.append('action', 'belgranit_form_submit');
		formData.append('nonce', nonce);

		var btn = form.querySelector('button[type="submit"]');
		var originalText = btn.innerHTML;
		btn.innerHTML = 'Отправка...';
		btn.disabled = true;

		fetch(ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(function(r) { return r.json(); })
		.then(function(res) {
			if (res.success) {
				window.location.href = thankYouUrl;
			} else {
				alert(res.data.message || 'Ошибка отправки');
				btn.innerHTML = originalText;
				btn.disabled = false;
			}
		})
		.catch(function() {
			alert('Ошибка сети. Попробуйте позже.');
			btn.innerHTML = originalText;
			btn.disabled = false;
		});
	}

	// Callback form
	var callbackForm = document.getElementById('callback-form');
	if (callbackForm) {
		callbackForm.addEventListener('submit', function(e) {
			e.preventDefault();
			sendForm(callbackForm);
		});
	}

	// Consultation forms
	document.querySelectorAll('form[action="#"]').forEach(function(form) {
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			sendForm(form);
		});
	});
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
