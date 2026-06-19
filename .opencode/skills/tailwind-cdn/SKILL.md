---
name: tailwind-cdn
description: Подключение Tailwind CSS через CDN к WordPress теме. Включает настройку кастомных классов, подключение шрифтов и normalize.css. Используй когда нужно подключить Tailwind без npm, добавить кастомные стили или сбросить стандартные CSS стили.
---

# Tailwind CSS CDN

Подключение Tailwind CSS через CDN к WordPress теме без использования npm зависимостей.

## Порядок подключения

### 1. Подключение normalize.css и Tailwind в header.php

Добавить перед `<?php wp_head(); ?>`:

```php
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#1a1a1a',
                secondary: '#c8a97e',
                accent: '#f5f0e8',
            },
            fontFamily: {
                body: ['Inter', 'sans-serif'],
                heading: ['Playfair Display SC', 'serif'],
            },
        },
    },
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display+SC&display=swap');
</style>
```

### 2. Очистка style.css

Оставить только обязательный заголовок темы:

```css
/*!
Theme Name: ThemeName
Theme URI: http://underscores.me/
Author: Underscores.me
Author URI: http://underscores.me/
Description: Description
Version: 1.0.0
Tested up to: 5.4
Requires PHP: 5.6
License: GNU General Public License v2 or later
License URI: LICENSE
Text Domain: themename
Tags: custom-background, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready
*/
```

## Использование кастомных классов

После подключения Tailwind с конфигурацией, доступны классы:

### Цвета
- `bg-primary` — фон основного цвета
- `text-primary` — текст основного цвета
- `bg-secondary` — фон вторичного цвета
- `text-secondary` — текст вторичного цвета
- `bg-accent` — фон акцентного цвета
- `border-primary` — рамка основного цвета

### Шрифты
- `font-body` — шрифт Inter для текста
- `font-heading` — шрифт Playfair Display SC для заголовков

### Примеры
```html
<div class="bg-primary text-white p-4">Тёмный блок</div>
<h1 class="font-heading text-secondary text-4xl">Заголовок</h1>
<p class="font-body text-gray-700">Основной текст</p>
```

## Добавление новых кастомных классов

Расширять `tailwind.config` в `header.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                // новые цвета
                brand: '#ff6600',
                surface: '#f8f9fa',
            },
            spacing: {
                // новые отступы
                '128': '32rem',
            },
            fontSize: {
                // новые размеры шрифтов
                'display': ['4rem', { lineHeight: '1.1' }],
            },
        },
    },
}
```

## Порядок загрузки

1. `normalize.css` — сброс стилей браузеров
2. `tailwind.config` — кастомные классы
3. `tailwindcss` — утилитарные классы
4. Google Fonts — шрифты

## Важно

- CDN версия не оптимизирована для продакшена (нет purge CSS)
- Все классы генерируются на клиенте при загрузке страницы
- Подходит для разработки и прототипирования
