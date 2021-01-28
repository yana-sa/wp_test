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
        echo "<p id='rating_for_books'><h4>Rating: $rating_for_books</p>"; ?>
    </div>

	<div class="entry-content">
		<?php
		the_content();

        global $wpdb;
        $user_id = get_current_user_id();
        $sql = $wpdb->get_row( "SELECT action FROM `wp_book_evaluation` WHERE user_id = '$user_id'", ARRAY_A );
        echo $sql['action'];

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Post', 'twentytwentyone-child' ) . '">',
				'after'    => '</nav>',
				/* translators: %: page number. */
				'postlink' => esc_html__( 'Post %', 'twentytwentyone-child' ),
			)
		); ?>
        <div class="entry-content alignwide">
            <?php
            $link = admin_url('admin-ajax.php?action=book_post_evaluation&post_id='.$post->ID);

            echo '<a value="like" class="book_post_evaluation" style="font-size:50px" id="evaluation" data-post_id="' . $post->ID . '" href="' . $link . '">&#128077</a>  ';
            echo '<a value="dislike" class="book_post_evaluation" style="font-size:50px" id="evaluation" data-post_id="' . $post->ID . '" href="' . $link . '">&#128078</a>';
            ?>
        </div>
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
