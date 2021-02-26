<?php
/*
 * Template Name: Add new topic Page
 *
 */
get_header();
$forum_id = $_GET['forum_id'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header alignwide">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php twenty_twenty_one_post_thumbnail(); ?>
    </header>

    <div class="entry-content">
          <form method="post">
              <h4>Add new topic to the forum</h4><br>

              <h5>Title:<br>
                <label for="title">
                  <input type="text" name="title" id="title">
                </label><br>
              <h5>Content:
                  <label for="content">
                      <textarea name="content" id="content"></textarea>
                  </label><br><br>
                  <input type="hidden" name="forum_id" id="forum_id" value="<?php echo $forum_id ?>">
                  <input type="submit" data-submit="new-topic" value="Add!">
          </form>
    </div><!-- .entry-content -->

    <footer class="entry-footer default-max-width">
        <?php
        handle_add_new_topic();
        twenty_twenty_one_entry_meta_footer(); ?>
    </footer><!-- .entry-footer -->

    <?php if ( ! is_singular( 'attachment' ) ) : ?>
        <?php get_template_part( 'template-parts/post/author-bio' ); ?>
    <?php endif; ?>

</article><!-- #post-${ID} -->