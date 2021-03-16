<?php
/*
 * Template Name: Forums Page
 *
 */
get_header();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="entry-content alignwide">
            <ol class="forum-list">
                <?php $forums = forum_list_data();
                foreach ($forums as $forum) {?>
                <h4><li class="forum-list-item">
                    <a href="<?php $forum['link']; ?>">
                        <?php echo $forum['title']; ?> </a>
                </li>
                <?php } ?>
            </ol>
        </div>

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