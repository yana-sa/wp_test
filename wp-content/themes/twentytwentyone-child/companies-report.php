<?php
/*
 * Template Name: Report Page
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
            <?php $terms = get_terms([
                    'taxonomy' => 'company_factories',
                    'hide_empty' => false,]);
            foreach ( $terms as $term) {
            wp_reset_query();
                    $args = ['post_type' => 'factories',
                    'company_factories' => $term->slug,];
                $query = new WP_Query($args);
                if($query->have_posts()) {
                    $profit = [];
                    foreach ($query->posts as $factories) {
                        $profit[] = esc_attr(get_post_meta($factories->ID, '_monthly_profit', true));
                    }?>
            <details>
                <summary><b><?php echo $term->name; ?></b><br>
                    Total monthly profit: <?php echo array_sum($profit)?>$</summary><?php
                    while($query->have_posts()) {
                        $query->the_post();
                        $monthly_profit = esc_attr(get_post_meta(get_the_ID(), '_monthly_profit', true)); ?>
                        <ul>
                            <li><a href="<?php get_permalink() ?>"><?php echo get_the_title() ?></a><br>
                                Monthly profit: <?php echo $monthly_profit; ?>$
                            </li>
                        </ul><?php
                    }?></details><?php
                }
            }?>
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