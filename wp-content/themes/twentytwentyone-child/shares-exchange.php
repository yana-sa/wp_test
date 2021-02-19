<?php
/*
 * Template Name: Shares Exchange Page
 *
 */
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
get_header();
shares_exchange_offer();
$shares_exchange_data = shares_exchange_offers_data();
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header alignwide">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            <?php twenty_twenty_one_post_thumbnail(); ?>
        </header>

        <div class="tablediv">
            <table>
                <tr>
                    <th>Company</th>
                    <th>Seller</th>
                    <th>Shares</th>
                    <th>Price</th>
                    <th>Purchase</th>
                </tr>
                <?php foreach ($shares_exchange_data['offers'] as $offer) { ?>
                <tr>
                    <td><?php echo $offer['company']; ?></td>
                    <td><?php echo $offer['user']; ?></td>
                    <td><?php echo $offer['shares']; ?></td>
                    <td><?php echo $offer['price']; ?>$</td>
                    <td><input type="submit" data-offer="<?php echo $offer['offer_id']; ?>" value="Purchase!"></td>
                </tr>
                <?php } ?>
            </table>

            <form method="post" data-form="exchange-offer">
                <label for="company_shares">Company:</label><br>
                <select name="company_shares" id="company_shares" data-select="company_shares"><?php
                    foreach ($shares_exchange_data['user_data'] as $user_shares) { ?>
                    <option value="<?php echo $user_shares['user_company'] ?>" data-sum="<?php echo $user_shares['user_sum'] ?>"><?php echo $user_shares['your_company'] ?></option>
                    <?php } ?>
                </select><br><div data-company="sum"></div>
                <label for="shares">The number of shares to sell:</label><br>
                <input type="number" data-input="number_of_shares" min="1" max="" id="shares" name="shares"><br>
                <label for="price">Price:</label><br>
                <input type="number" id="price" name="price">$<br><br>
                <input type="submit" value="Submit">
            </form>
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