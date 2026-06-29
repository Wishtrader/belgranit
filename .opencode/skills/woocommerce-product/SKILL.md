---
name: woocommerce-product
description: Создание шаблона страницы товара WooCommerce в WordPress теме. Включает структуру шаблонов, работу с $product, галереей, атрибутами и ценой. Используй когда нужно сверстать страницу товара, добавить галерею, таблицу характеристик или кастомизировать вывод WooCommerce.
---

# WooCommerce Product Page

Создание кастомного шаблона страницы товара WooCommerce в WordPress теме с Tailwind CSS.

## Критические ошибки

### $product до цикла — НИКОГДА не делать так:

```php
// НЕПРАВИЛЬНО — вызовет Fatal Error
global $product;
$gallery_images = $product->get_gallery_image_ids(); // $product = null!

get_header();
while ( have_posts() ) : the_post();
    // ...
endwhile;
```

### Правильно — весь код внутри цикла:

```php
get_header();
while ( have_posts() ) : the_post();

    global $product;
    if ( ! $product ) {
        continue;
    }

    $gallery_images = $product->get_gallery_image_ids();
    // ... остальной код

endwhile;
get_footer();
```

**Причина:** `$product` инициализируется WooCommerce только после вызова `the_post()` внутри цикла.

## Структура шаблонов

### Файлы темы

```
theme/
├── single-product.php              # Корневой — НЕ использовать для WooCommerce
├── woocommerce/
│   ├── single-product.php          # Главный шаблон товара (override)
│   ├── archive-product.php         # Каталог товаров
│   └── content-product.php         # Карточка товара в каталоге
```

### Как WooCommerce загружает шаблон

1. WordPress ищет `single-product.php` для post type `product`
2. WooCommerce перехватывает через `template_loader` фильтр
3. Ищет `woocommerce/single-product.php` в теме
4. Если найден — использует его
5. Если нет — использует свой дефолтный из шаблонов плагина

**Важно:** Корневой `single-product.php` в теме — это WordPress шаблон, а НЕ WooCommerce override. Он может конфликтовать.

## Шаблон single-product.php

Минимальная структура:

```php
<?php
/**
 * @package ThemeName
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while ( have_posts() ) : the_post();

        global $product;
        if ( ! $product ) {
            continue;
        }

        // Все данные товара здесь
        $gallery_images = $product->get_gallery_image_ids();
        $main_image_id  = $product->get_image_id();
        $all_images     = array_merge( array( $main_image_id ), $gallery_images );
        $attributes     = $product->get_attributes();
        $short_desc     = $product->get_short_description();
        $price          = $product->get_price_html();
    ?>

        <h1><?php the_title(); ?></h1>
        <!-- Шаблон товара -->

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

## API WooCommerce Product

### Получение данных

```php
$product->get_name();                 // Название
$product->get_price();                // Цена (число)
$product->get_price_html();           // Цена с HTML (скидки, диапазон)
$product->get_short_description();    // Краткое описание
$product->get_description();          // Полное описание
$product->get_image_id();             // ID главного изображения
$product->get_gallery_image_ids();    // массив ID галереи
$product->get_sku();                  // Артикул
$product->get_stock_status();         // Статус наличия
$product->is_on_sale();               // На скидке?
$product->is_in_stock();              // В наличии?
```

### Атрибуты товара

```php
$attributes = $product->get_attributes();

foreach ( $attributes as $attribute ) {
    $name = wc_attribute_label( $attribute->get_name() );

    // Таксономия (цвет, материал и т.д.)
    if ( $attribute->is_taxonomy() ) {
        $values = wc_get_product_attribute_term_names( $attribute->get_id() );
        $value  = implode( ', ', $values );
    }
    // Пользовательский атрибут
    else {
        $value = implode( ', ', $attribute->get_options() );
    }
}
```

### Изображения

```php
// Главное изображение
$image_url = wp_get_attachment_image_url( $product->get_image_id(), 'large' );

// Галерея
foreach ( $product->get_gallery_image_ids() as $image_id ) {
    $url = wp_get_attachment_image_url( $image_id, 'large' );
    $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}
```

### Хлебные крошки

```php
$terms = get_the_terms( get_the_ID(), 'product_cat' );
$term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;

if ( $term ) {
    echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
}

// Родительская категория
$parent_id   = $term ? $term->parent : 0;
$parent_term = $parent_id ? get_term( $parent_id ) : null;
```

## Галерея с Alpine.js

```html
<div x-data="{ active: 0 }">
    <!-- Главное изображение -->
    <div class="relative aspect-square">
        <?php foreach ( $all_images as $index => $image_id ) : ?>
            <img
                src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>"
                class="absolute inset-0 w-full h-full object-contain"
                x-show="active === <?php echo esc_attr( $index ); ?>"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
        <?php endforeach; ?>
    </div>

    <!-- Миниатюры -->
    <div class="grid grid-cols-4 gap-3">
        <?php foreach ( $all_images as $index => $image_id ) : ?>
            <button
                @click="active = <?php echo esc_attr( $index ); ?>"
                :class="active === <?php echo esc_attr( $index ); ?> ? 'border-red-800' : 'border-transparent'"
                class="border-2 rounded-lg"
            >
                <img src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>">
            </button>
        <?php endforeach; ?>
    </div>
</div>
```

## Таблица атрибутов

```php
<?php if ( ! empty( $attributes ) ) : ?>
    <div class="border-t border-gray-100 pt-4">
        <?php foreach ( $attributes as $attribute ) :
            $name  = wc_attribute_label( $attribute->get_name() );
            $value = $attribute->is_taxonomy()
                ? implode( ', ', wc_get_product_attribute_term_names( $attribute->get_id() ) )
                : implode( ', ', $attribute->get_options() );
        ?>
            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="text-gray-500"><?php echo esc_html( $name ); ?>:</span>
                <span class="font-semibold"><?php echo esc_html( $value ); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
```

## Подключение Alpine.js

Добавить в `header.php` после подключения Tailwind:

```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

## Диагностика

### Шаблон не загружается

1. Проверить наличие файла `woocommerce/single-product.php` в теме
2. Удалить корневой `single-product.php` (может конфликтовать)
3. Сбросить кэш хостинга
4. Проверить исходный код страницы (Cmd+U) на наличие HTML комментариев

### $product = null

- Убедиться что `global $product` и обращение к методам находятся **внутри** `while ( have_posts() ) : the_post()`
- Проверить что WooCommerce активен
- Проверить что товар опубликован

### PHP Fatal Error

- Включить `WP_DEBUG` в `wp-config.php`: `define( 'WP_DEBUG', true );`
- Проверить `wp-content/debug.log`
