# AGENTS.md

## WordPress Taxonomy Slugs

When comparing taxonomy slugs from URL parameters to `$category->slug`, always be aware that WordPress may URL-encode Cyrillic characters in slugs (e.g., `памятники` → `%d0%bf%d0%b0%d0%bc...`).

### Solutions:
1. Use `field => 'name'` in `tax_query` instead of `'slug'` — compare by human-readable name
2. Use `urldecode()` on both sides of comparison
3. Register taxonomy with ASCII `rewrite slug` to prevent encoding issues

### Example:
```php
// SAFE — compare by name
$args['tax_query'] = array(
    array(
        'taxonomy' => 'work_category',
        'field'    => 'name',  // not 'slug'
        'terms'    => $current_category,
    ),
);
```
