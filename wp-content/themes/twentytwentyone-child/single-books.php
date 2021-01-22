<?php
/*
 * Template Name: Book Page
 *
 */
get_header();

global $post;
$rating_for_books = esc_attr(get_post_meta($post->ID, '_rating_for_books', true));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header alignwide">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php twenty_twenty_one_post_thumbnail(); ?>
	</header>

    <div class="entry-content alignwide"><?php
        echo "<h4>Rating: $rating_for_books"; ?>
    </div>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-${ID} -->
<?php
get_footer();
?>
