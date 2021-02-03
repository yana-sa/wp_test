<?php
/*
 * Template Name: Book Page
 *
 */
get_header();

global $post;
$rating_for_books = esc_attr(get_post_meta($post->ID, '_rating_for_books', true));
global $wpdb;
$user_id = get_current_user_id();
$sql = $wpdb->get_row("SELECT action FROM wp_book_evaluation WHERE user_id = '$user_id' AND post_id = '$post->ID'", ARRAY_A);
$evaluation = $sql['action'];
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="entry-content alignwide">
            <h4><p id='rating_for_books'>Rating: <?php echo $rating_for_books; ?></p></h4>
            <p><input type='button' formmethod='post' value='&#10133' id='like' data-post-id='<?php echo $post->ID; ?>' data-action='like' <?php if ($evaluation == 'like') { echo "disabled"; } ?>>
                <input type='button' formmethod='post' value='&#10134' id='dislike' data-post-id='<?php echo $post->ID; ?>' data-action='dislike' <?php if ($evaluation == 'dislike') { echo "disabled"; } ?>></p>
        </div>

        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Post', 'twentytwentyone-child' ) . '">',
                    'after'    => '</nav>',
                    /* translators: %: page number. */
                    'postlink' => esc_html__( 'Post %', 'twentytwentyone-child' ),
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