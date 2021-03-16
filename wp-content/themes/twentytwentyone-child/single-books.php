<?php
/*
 * Template Name: Book Page
 *
 */
get_header();

global $post;
$rating_for_books = esc_attr(get_post_meta($post->ID, '_rating_for_books', true));
$action = get_book_evaluation_action();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="entry-content alignwide">
            <div data-div="rating_block">
                <h4><p id='rating_for_books' data-p="book-rating">Rating: <?php echo $rating_for_books; ?></p></h4>
                <p><input type='button' formmethod='post' value='&#10133' id='like' data-button="evaluation" data-post-id='<?php echo $post->ID; ?>' data-action='like' <?php if ($action == 'like') { echo 'style="background-color:#8c8c8c" data-chosen="true"'; } ?>>
                <input type='button' formmethod='post' value='&#10134' id='dislike' data-button="evaluation" data-post-id='<?php echo $post->ID; ?>' data-action='dislike' <?php if ($action == 'dislike') { echo 'style="background-color:#8c8c8c" data-chosen="true"'; } ?>></p>
            </div>
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