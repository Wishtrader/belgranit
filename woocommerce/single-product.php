<?php
/**
 * The template for displaying the product page
 *
 * @package BelGranit
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-8 mt-[72px] lg:mt-0">
				<h1 class="font-playfair text-[28px] sm:text-[32px] lg:text-[36px] text-ink uppercase">
					<?php the_title(); ?>
				</h1>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
