<?php
/*
 * Template Name: Topic Page
 *
 */
get_header();

global $post;
if ( get_query_var( 'paged' ) ) { $page = get_query_var( 'paged' ); }
elseif ( get_query_var( 'page' ) ) { $page = get_query_var( 'page' ); }
else { $page = 1; }

$query = new WP_Query([
    'post_type' => 'topic_post',
    'meta_key' => '_topic_id',
    'meta_value' => $post->ID,
    'posts_per_page' => 3,
    'paged' => $page,
    ]);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header alignwide">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php twenty_twenty_one_post_thumbnail(); ?>
    </header>

    <div class="entry-content">
        <ul class='topic-list' data-list="topic-list">
            <?php
            while ($query->have_posts()) {
            $query->the_post(); ?>
            <h4><li class='topic-list-item'>
                    <div class="topic-list-div"><div class="topic-list-post-div">
                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                    <br /><span><h5><?php echo get_the_content();?></span>
                    </div><div class="topic-list-author-div">
                        <?php echo get_avatar(get_the_author_meta('ID')); ?>
                        <h5><?php echo get_the_author_meta('display_name'); ?></h5>
                        <h5><?php echo ucfirst(get_the_author_meta('roles')[0]);?></h5>
                    </div></div>
                </li>
                <?php }
                wp_reset_query();?>
        </ul>
        <div class="topic-posts-pagination" data-pagination="topic-posts">
            <?php $pages = $query->max_num_pages;
            if ($pages > 1) { ?>
                <h5>Pages: &nbsp;</h5>
                <?php for ($i = 1; $i <= $pages; $i++) {?>
                <input type="button" formmethod="post" value="<?php echo $i ?>" data-page="<?php echo $i ?>" data-topic_id="<?php echo $post->ID ?>" data-button="pagination">
                <?php } ?>
            <?php } ?>
        </div>
        <div>
        </div>
    </div><!-- .entry-content -->

    <footer class="entry-footer default-max-width">
        <?php if (is_user_logged_in() == true) {?>
        <form method="post">
            <h4>Add new post to the topic</h4><br>
            <h5>Title:<br>
                <label for="post_title">
                    <input type="text" name="post_title" id="post_title">
                </label><br>
                <h5>Content:
                    <label for="post_content">
                        <textarea name="post_content" id="post_content"></textarea>
                    </label><br><br>
                    <input type="hidden" name="topic_id" id="topic_id" value="<?php echo $post->ID ?>">
                    <input type="submit" value="Add!">
        </form>
        <?php
        handle_add_new_topic_post();
        }
        twenty_twenty_one_entry_meta_footer(); ?>
    </footer><!-- .entry-footer -->

    <?php if ( ! is_singular( 'attachment' ) ) : ?>
        <?php get_template_part( 'template-parts/post/author-bio' ); ?>
    <?php endif; ?>

</article><!-- #post-${ID} -->