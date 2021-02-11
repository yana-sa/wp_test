<?php
/*
 * Template Name: Company Page
 *
 */
global $post;
get_header();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title( '<h1 class="entry-title">', '</h1>' );
                $company_data = company_post_data();?>
            <h4>Monthly profit is <?php echo (($company_data['profit'] !== null) ? $company_data['profit'] . '$' : 'unknown.' ) ?></h4>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="entry-content">
            <?php the_content();
            $factories = $company_data['factories'];
            if ($factories !== null) { ?>
                <h5>Company owns:</h5>
                <?php foreach ($factories as $factory) {?>
                <li><a href="<?php $factory['link'] ?>"><?php echo $factory['title'] ?></a><br></li><?php
                }
            } else {?>
                <h5>Company owns no factories.</h5><?php
            }
            wp_reset_query();
            $companies_data = company_money_transfer_data(); ?>
            <details>
                <summary><b>Transfer money</b></summary>
                <ul>
                Company's current balance: <b><?php echo $companies_data['current']['balance'] ?>$</b>
                <form name="transfer_data" method="post">
                    The sum to be transferred: <input type="number" id="sum" name="sum">$<br>
                    <label for="companies">Choose a company to transfer money to:</label>
                    <select name="companies" id="companies"><?php
                        foreach ($companies_data['companies'] as $company) { ?>
                            <option value="<?php echo $company['id'] ?>"><?php echo $company['title'] ?></option>
                        <?php } ?>
                    </select><br>
                    <button name="transfer" value="transfer">Transfer!</button>
                </form>
                </ul>
            </details>
            <?php
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