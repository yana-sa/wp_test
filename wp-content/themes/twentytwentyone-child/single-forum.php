<?php
/*
 * Template Name: Forum Page
 *
 */
global $post;
$query = new WP_Query([
    'post_type' => 'topic',
    'meta_key' => '_forum_id',
    'meta_value' => $post->ID
]);

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header alignwide">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php twenty_twenty_one_post_thumbnail(); ?>
    </header>

    <div class="entry-content">
        <ul class='forum-list'>
        <?php
        while ($query->have_posts()) {
            $query->the_post(); ?>
                <h4><li class='forum-list-item'>
                    <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                </li>
            <?php } ?>
        </ul>
        <form method="post" action="/?page_id=6230">
            <h4><input type="submit" value="Add new topic!">
                <input type="hidden" name="forum_id" value="<?php echo $post->ID ?>">
        </form>
      <?php
        wp_link_pages(
            array(
                'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone-child' ) . '">',
                'after'    => '</nav>',
                /* translators: %: page number. */
                'pagelink' => esc_html__( 'Page %', 'twentytwentyone-child' ),
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer default-max-width">
        <?php twenty_twenty_one_entry_meta_footer(); ?>
    </footer><!-- .entry-footer -->

    <?php if ( ! is_singular( 'attachment' ) ) : ?>
        <?php get_template_part( 'template-parts/post/author-bio' ); ?>
    <?php endif; ?>

</article><!-- #post-${ID} -->