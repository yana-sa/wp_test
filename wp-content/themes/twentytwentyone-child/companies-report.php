<?php
/*
 * Template Name: Report Page
 *
 */
get_header();
$report_data = monthly_profit_report_data();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="entry-content alignwide">
            <?php
            foreach ($report_data as $company) {?>
            <details>
                <summary><b><?php echo $company['title']; ?></b><br>
                    Total monthly profit: <?php echo $company['sum_profit']?>$</summary><?php
                foreach ($company['factories'] as $factory) {?>
                        <ul>
                            <li><a href="<?php $factory['link'] ?>"><?php echo $factory['title'] ?></a><br>
                                Monthly profit: <?php echo $factory['monthly_profit'] ?>$
                            </li>
                        </ul><?php
                }?>
            </details><?php
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