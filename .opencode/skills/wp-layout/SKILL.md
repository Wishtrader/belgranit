---
name: wp-layout
description: Верстка страниц в WordPress теме с Tailwind CSS CDN. Включает структуру шаблонов, header/footer, адаптивную верстку, кастомные цвета и шрифты. Используй когда нужно сверстать новую страницу, секцию или компонент в WordPress теме.
---

# WordPress Page Layout с Tailwind CSS CDN

Верстка страниц в WordPress теме используя Tailwind CSS через CDN.

## Структура проекта

```
theme/
├── header.php          # Шапка сайта (head + header)
├── footer.php          # Подвал сайта (footer + закрытие тегов)
├── functions.php       # Функции темы (регистрация меню, стилей, скриптов)
├── style.css           # Только заголовок темы (без стилей!)
├── front-page.php      # Главная страница
├── page.php            # Шаблон страницы
├── single.php          # Шаблон записи
├── archive.php         # Шаблон архива
├── search.php          # Шаблон поиска
├── 404.php             # Страница 404
├── template-parts/     # Переиспользуемые части шаблонов
│   └── content.php
├── inc/                # PHP-include файлы
│   ├── custom-header.php
│   ├── template-tags.php
│   └── template-functions.php
└── js/
    └── navigation.js   # Скрипт навигации
```

## Tailwind Config (в header.php)

Перед использованием скилла **настроить цвета и шрифты** под конкретный проект:

```php
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                // Основной цвет бренда (палитра 50-950)
                primary: {
                    50: '#...',
                    100: '#...',
                    200: '#...',
                    300: '#...',
                    400: '#...',
                    500: '#...',  // базовый
                    600: '#...',
                    700: '#...',  // кнопки, акцент
                    800: '#...',  // тёмный фон
                    900: '#...',  // логотип, заголовки
                    950: '#...',
                },
                // Вторичный цвет (опционально)
                secondary: '#...',
                // Акцентный фон секций
                accent: '#...',
                // Цвет основного текста
                body: '#...',
                // Цвет заголовков
                heading: '#...',
            },
            fontFamily: {
                body: ['ИмяШрифта', 'sans-serif'],
                heading: ['ИмяШрифта', 'serif'],
            },
        },
    },
}
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=ШрифтТекста:wght@300;400;500;600;700;800;900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=ШрифтЗаголовков:wght@400;700;900&display=swap');
</style>
```

### Минимальный конфиг (заменить под свой проект)

```php
colors: {
    primary: { /* палитра */ },
    accent: '#f5f0e8',
    body: '#333333',
    heading: '#1a1a1a',
},
fontFamily: {
    body: ['Inter', 'sans-serif'],
    heading: ['Playfair Display SC', 'serif'],
},
```

### Именование в шаблонах

Во всех шаблонах использовать **абстрактные имена**:

| Класс | Назначение | Замена на |
|-------|------------|-----------|
| `primary-*` | Основной цвет бренда | Свой палитра |
| `bg-primary-700` | Фон кнопок, акцент | `bg-primary-700` |
| `bg-primary-800` | Тёмный фон (top bar) | `bg-primary-800` |
| `text-primary-900` | Логотип, заголовки | `text-primary-900` |
| `text-body` | Основной текст | `text-body` |
| `text-heading` | Заголовки | `text-heading` |
| `bg-accent` | Фон секций через один | `bg-accent` |
| `font-body` | Шрифт текста | Свой шрифт |
| `font-heading` | Шрифт заголовков | Свой шрифт |

**НИКОГДА** не использовать конкретные имена цветов (burgundy, blue, green) — только `primary-*`, `accent`, `body`, `heading`.

## Порядок подключения в header.php

```php
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <!-- Tailwind + конфиг -->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <!-- Header -->
    <?php get_header(); ?>
    <!-- Content -->
    <main id="primary" class="site-main">
        <!-- Ваш контент -->
    </main>
    <!-- Footer -->
    <?php get_footer(); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
```

## Шаблон страницы (page.php)

```php
<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="relative h-[300px] sm:h-[400px] lg:h-[500px] overflow-hidden">
                    <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl text-white text-center px-4">
                            <?php the_title(); ?>
                        </h1>
                    </div>
                </div>
            <?php endif; ?>

            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-10 sm:py-14 lg:py-20">
                <?php if ( ! has_post_thumbnail() ) : ?>
                    <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl text-heading mb-8">
                        <?php the_title(); ?>
                    </h1>
                <?php endif; ?>

                <div class="prose prose-lg max-w-none font-body text-body">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

## Секции (создавать в template-parts/)

### Секция с заголовком и текстом

```php
<!-- section-title-text.php -->
<section class="py-10 sm:py-14 lg:py-20 bg-white">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
        <h2 class="font-heading text-2xl sm:text-3xl lg:text-4xl text-heading mb-6">
            <?php the_sub_field( 'section_title' ); ?>
        </h2>
        <div class="font-body text-body text-base sm:text-lg leading-relaxed max-w-3xl">
            <?php the_sub_field( 'section_text' ); ?>
        </div>
    </div>
</section>
```

### Секция с карточками

```php
<!-- section-cards.php -->
<section class="py-10 sm:py-14 lg:py-20 bg-accent">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
        <h2 class="font-heading text-2xl sm:text-3xl lg:text-4xl text-heading text-center mb-10">
            Заголовок секции
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php foreach ( $cards as $card ) : ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="h-[200px] sm:h-[240px] overflow-hidden">
                        <img src="<?php echo esc_url( $card['image'] ); ?>"
                             alt="<?php echo esc_attr( $card['title'] ); ?>"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-5 sm:p-6">
                        <h3 class="font-heading text-lg sm:text-xl text-heading mb-2">
                            <?php echo esc_html( $card['title'] ); ?>
                        </h3>
                        <p class="font-body text-sm sm:text-base text-body">
                            <?php echo esc_html( $card['description'] ); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

### Секция с фоновым изображением

```php
<!-- section-hero.php -->
<section class="relative py-20 sm:py-28 lg:py-36 overflow-hidden">
    <div class="absolute inset-0">
        <img src="<?php echo esc_url( $bg_image ); ?>"
             alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 text-center">
        <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl text-white mb-6">
            Заголовок
        </h2>
        <p class="font-body text-base sm:text-lg text-white/90 max-w-2xl mx-auto mb-8">
            Описание
        </p>
        <a href="#" class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-600 text-white font-semibold px-8 py-3 rounded transition-colors">
            Кнопка
        </a>
    </div>
</section>
```

## Адаптивные брейкпоинты

Tailwind использует стандартные брейкпоинты:

| Класс | Ширина | Назначение |
|-------|--------|------------|
| `sm:` | 640px | Маленькие планшеты |
| `md:` | 768px | Планшеты |
| `lg:` | 1024px | Десктоп |
| `xl:` | 1280px | Большой десктоп |
| `2xl:` | 1536px | Очень большой |

### Паттерны адаптивности

```html
<!-- Текст: мобильный → десктоп -->
<h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl">Заголовок</h1>
<p class="text-sm sm:text-base lg:text-lg">Текст</p>

<!-- Отступы: мобильный → десктоп -->
<div class="px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-14">Блок</div>
<section class="py-10 sm:py-14 lg:py-20">Секция</section>

<!-- Сетка: 1 колонка → 2 → 3 -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
    <div>Карточка</div>
</div>

<!-- Flex: колонка → ряд -->
<div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
    <div class="lg:w-1/2">Левая колонка</div>
    <div class="lg:w-1/2">Правая колонка</div>
</div>

<!-- Скрытие/показ -->
<div class="hidden lg:block">Только десктоп</div>
<div class="lg:hidden">Только мобильный</div>
```

## Цветовая палитра

### Семантические имена (конфиг)

| Класс | Назначение | Пример из макета |
|-------|------------|------------------|
| `primary-700` | Кнопки, основной акцент | Кнопка "Заказать звонок" |
| `primary-800` | Тёмный фон (top bar, header кнопки) | Top bar |
| `primary-900` | Логотип, заголовки | Текст логотипа |
| `body` | Основной текст | Параграфы |
| `heading` | Заголовки | H1-H6 |
| `accent` | Акцентный фон секций | Секции через один |
| `secondary` | Вторичный цвет (опционально) | Доп. акценты |

### Стандартные Tailwind для фона

| Класс | Цвет | Назначение |
|-------|------|------------|
| `bg-white` | #fff | Основной фон |
| `bg-gray-50` | #f9fafb | Секции через один |
| `bg-gray-100` | #f3f4f6 | Разделители |
| `bg-accent` | из конфига | Акцентный фон секций |

## Типографика

### Шрифты

- **font-heading** — шрифт заголовков и логотипа (из конфига)
- **font-body** — шрифт основного текста (из конфига)

### Размеры шрифтов

| Элемент | Мобильный | Десктоп | Класс |
|---------|-----------|---------|-------|
| H1 | text-2xl (24px) | text-5xl (48px) | `text-2xl sm:text-3xl lg:text-4xl xl:text-5xl` |
| H2 | text-xl (20px) | text-4xl (36px) | `text-xl sm:text-2xl lg:text-3xl xl:text-4xl` |
| H3 | text-lg (18px) | text-2xl (24px) | `text-lg sm:text-xl lg:text-2xl` |
| Body | text-base (16px) | text-lg (18px) | `text-base sm:text-lg` |
| Small | text-sm (14px) | text-base (16px) | `text-sm sm:text-base` |

## Header паттерн

### Десктопный header

```html
<header>
    <!-- Top bar (скрыт на мобильном) -->
    <div class="bg-primary-800 text-white hidden lg:block">
        <div class="max-w-[1400px] mx-auto px-6 py-3 flex items-center justify-between gap-8">
            <!-- Адрес, телефоны, CTA -->
        </div>
    </div>

    <!-- Main header -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 flex items-center justify-between h-16 sm:h-20 lg:h-24">
            <!-- Логотип -->
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <!-- SVG иконка + текст -->
            </a>

            <!-- Навигация (скрыта на мобильном) -->
            <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                <?php wp_nav_menu( array( 'depth' => 1, ... ) ); ?>
            </nav>

            <!-- Мобильные кнопки -->
            <div class="flex items-center gap-2 lg:hidden">
                <a href="tel:..." class="w-10 h-10 bg-primary-800 rounded"><!-- Телефон --></a>
                <button id="menu-toggle" class="w-10 h-10 bg-primary-800 rounded"><!-- Бургер --></button>
            </div>
        </div>
    </div>
</header>
```

### Мобильное меню

```html
<!-- Фиксированный оверлей -->
<div id="mobile-menu" class="fixed inset-0 z-50 lg:hidden hidden-menu bg-white overflow-y-auto">
    <!-- Шапка меню (логотип + кнопки) -->
    <div class="flex items-center justify-between px-4 sm:px-6 h-16 sm:h-20 bg-white border-b border-gray-100">
        <!-- Логотип -->
        <!-- Кнопки: телефон + закрыть -->
    </div>

    <!-- Навигация -->
    <nav class="px-4 sm:px-6 py-6">
        <!-- С подменю -->
        <div class="mb-1">
            <button class="mobile-submenu-toggle w-full flex items-center justify-between py-3 text-base font-bold uppercase">
                <span>Пункт меню</span>
                <svg class="submenu-arrow w-5 h-5"><!-- Стрелка вниз --></svg>
            </button>
            <div class="mobile-submenu pl-4 max-h-0 overflow-hidden">
                <a href="#" class="block py-2 text-sm text-body">Подпункт</a>
            </div>
        </div>

        <!-- Без подменю -->
        <a href="#" class="block py-3 text-base font-bold uppercase">Пункт меню</a>
    </nav>
</div>
```

### JavaScript для мобильного меню

```javascript
(function() {
    var menuToggle    = document.getElementById('menu-toggle');
    var menuClose     = document.getElementById('menu-close');
    var mobileMenu    = document.getElementById('mobile-menu');
    var iconHamburger = document.getElementById('icon-hamburger');
    var iconClose     = document.getElementById('icon-close');

    function openMenu() {
        mobileMenu.classList.remove('hidden-menu');
        mobileMenu.classList.add('visible-menu');
        iconHamburger.classList.add('hidden');
        iconClose.classList.remove('hidden');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        mobileMenu.classList.remove('visible-menu');
        mobileMenu.classList.add('hidden-menu');
        iconHamburger.classList.remove('hidden');
        iconClose.classList.add('hidden');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            var isOpen = mobileMenu.classList.contains('visible-menu');
            isOpen ? closeMenu() : openMenu();
        });
    }

    if (menuClose) {
        menuClose.addEventListener('click', closeMenu);
    }

    // Подменю
    document.querySelectorAll('.mobile-submenu-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var submenuId = this.getAttribute('data-submenu');
            var submenu   = document.getElementById(submenuId);
            var arrow     = this.querySelector('.submenu-arrow');
            if (submenu) submenu.classList.toggle('open');
            if (arrow) arrow.classList.toggle('rotated');
        });
    });

    // Закрытие при ресайзе на десктоп
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) closeMenu();
    });
})();
```

### CSS для анимаций

```css
/* Мобильное меню */
#mobile-menu {
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
#mobile-menu.hidden-menu {
    transform: translateX(100%);
    opacity: 0;
    pointer-events: none;
}
#mobile-menu.visible-menu {
    transform: translateX(0);
    opacity: 1;
    pointer-events: auto;
}

/* Подменю */
.mobile-submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}
.mobile-submenu.open {
    max-height: 500px;
}

/* Стрелка */
.submenu-arrow {
    transition: transform 0.2s ease;
}
.submenu-arrow.rotated {
    transform: rotate(180deg);
}
```

## Footer паттерн

```html
<footer id="colophon" class="site-footer bg-[#2d2d2d]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-10 sm:py-14 lg:py-16">
        <div class="flex flex-col lg:flex-row lg:items-start gap-10 lg:gap-8">

            <!-- Logo + Description -->
            <div class="lg:w-[280px] shrink-0">
                <a href="/" class="flex items-center gap-2.5 mb-4">
                    <?php
                    $logo_id = get_theme_mod( 'custom_logo' );
                    if ( $logo_id ) :
                        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                    ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="..." class="h-8 sm:h-10 w-auto brightness-0 invert">
                    <?php else : ?>
                        <span class="font-heading text-xl sm:text-2xl font-bold text-white uppercase tracking-wide">
                            <?php bloginfo( 'name' ); ?>
                        </span>
                    <?php endif; ?>
                </a>
                <p class="font-body text-sm text-gray-400 leading-relaxed">Описание компании</p>
            </div>

            <!-- Колонка 1 -->
            <div class="lg:w-auto">
                <h3 class="font-body text-base font-bold text-white mb-4">Заголовок</h3>
                <ul class="space-y-2.5 list-none m-0 p-0">
                    <li><a href="#" class="font-body text-sm text-gray-400 hover:text-white transition-colors">Пункт</a></li>
                </ul>
            </div>

            <!-- Колонка 2 -->
            <div class="lg:w-auto">
                <h3 class="font-body text-base font-bold text-white mb-4">Заголовок</h3>
                <ul class="space-y-2.5 list-none m-0 p-0">
                    <li><a href="#" class="font-body text-sm text-gray-400 hover:text-white transition-colors">Пункт</a></li>
                </ul>
            </div>

            <!-- Контакты -->
            <div class="lg:w-auto lg:ml-auto">
                <h3 class="font-body text-base font-bold text-white mb-4">Контакты</h3>
                <ul class="space-y-2.5 list-none m-0 p-0">
                    <li><a href="tel:+375296405377" class="font-body text-sm text-gray-400 hover:text-white transition-colors">+375 (29) 640-53-77</a></li>
                </ul>
            </div>

            <!-- Scroll to Top -->
            <div class="hidden lg:flex items-start">
                <button id="scroll-to-top" class="flex items-center justify-center w-10 h-10 bg-white rounded hover:bg-gray-100 transition-colors" aria-label="Наверх">
                    <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Divider + Bottom -->
        <div class="border-t border-gray-600 mt-10 pt-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="font-body text-xs text-gray-400">&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. Все права защищены.</p>
                <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <a href="/cookie-policy" class="font-body text-xs text-gray-400 hover:text-white transition-colors">Политика обработки файлов cookie</a>
                    <a href="/privacy-policy" class="font-body text-xs text-gray-400 hover:text-white transition-colors">Политика обработки персональных данных</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Scroll to Top -->
    <div class="lg:hidden fixed bottom-6 right-6 z-40">
        <button id="scroll-to-top-mobile" class="flex items-center justify-center w-10 h-10 bg-white rounded shadow-lg hover:bg-gray-100 transition-colors" aria-label="Наверх">
            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>
</footer>
```

### JavaScript для Scroll to Top

```javascript
(function() {
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    var btnDesktop = document.getElementById('scroll-to-top');
    var btnMobile  = document.getElementById('scroll-to-top-mobile');

    if (btnDesktop) btnDesktop.addEventListener('click', scrollToTop);
    if (btnMobile) btnMobile.addEventListener('click', scrollToTop);
})();
```

## functions.php — минимум для новой темы

```php
<?php
// Регистрация меню
register_nav_menus( array(
    'menu-1' => esc_html__( 'Primary', 'belgranit' ),
) );

// Подключение стилей и скриптов
function theme_scripts() {
    wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_script( 'theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// Поддержка миниатюр
add_theme_support( 'post-thumbnails' );

// Поддержка кастомного логотипа
add_theme_support( 'custom-logo', array(
    'height'      => 250,
    'width'       => 250,
    'flex-width'  => true,
    'flex-height' => true,
) );
```

## Чеклист перед сдачей

- [ ] Цвета настроены в `tailwind.config` (primary, accent, body, heading)
- [ ] Шрифты подключены через Google Fonts
- [ ] Адаптивность: `sm:`, `lg:` для всех элементов
- [ ] Мобильное меню работает (открытие/закрытие/подменю)
- [ ] Top bar скрыт на мобильном (`hidden lg:block`)
- [ ] Логотип и кнопки видны на всех экранах
- [ ] Кликабельные телефоны (`href="tel:..."`)
- [ ] Нет горизонтального скролла
- [ ] `wp_head()` и `wp_footer()` на месте
- [ ] `body_class()` и `wp_body_open()` используются
