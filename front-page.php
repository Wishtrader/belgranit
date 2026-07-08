<?php
/**
 * Template Name: Front-page
 *
 * @package BelGranit
 */

get_header(); ?>

<?php get_template_part( 'template-parts/hero' ); ?>
<?php get_template_part( 'template-parts/categories' ); ?>
<?php get_template_part( 'template-parts/3d' ); ?>
<?php get_template_part( 'template-parts/process' ); ?>
<?php get_template_part( 'template-parts/popular-products' ); ?>
<?php get_template_part( 'template-parts/consultation' ); ?>
<?php get_template_part( 'template-parts/portfolio' ); ?>
<?php get_template_part( 'template-parts/reviews' ); ?>
<?php get_template_part( 'template-parts/stats' ); ?>


<?php get_footer(); ?>
