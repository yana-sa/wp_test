<?php
/*
 * Template Name: Transfers Page
 *
 */

get_header();
$logs = money_transfer_logs();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="tablediv">
            <table>
                <tr>
                    <th>Transferor</th>
                    <th>Transferee</th>
                    <th>Sum</th>
                    <th>Date and time</th>
                </tr>
                <?php foreach ($logs as $log) { ?>
                <tr>
                    <td><?php echo $log['transferor']; ?></td>
                    <td><?php echo $log['transferee']; ?></td>
                    <td><?php echo $log['sum']; ?></td>
                    <td><?php echo $log['date']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <footer class="entry-footer default-max-width">
            <?php twenty_twenty_one_entry_meta_footer(); ?>
        </footer><!-- .entry-footer -->

        <?php if (!is_singular('attachment')) : ?>
            <?php get_template_part('template-parts/post/author-bio'); ?>
        <?php endif; ?>

    </article><!-- #post-${ID} -->
<?php
get_footer();
?>