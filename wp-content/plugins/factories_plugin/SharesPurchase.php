<?php

class SharesPurchase
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function shares_exchange_purchase()
    {
        $offers_table = $this->wpdb->prefix . 'shares_exchange';
        $shares_table = $this->wpdb->prefix . 'company_shares';

        $offer_id = !empty($_POST['offer_id']) ? $_POST['offer_id'] : null;
        $buyer_id = get_current_user_id();
        $buyer_balance = get_user_meta($buyer_id, 'balance');
        $offered_price = $this->wpdb->get_var("SELECT price FROM $offers_table WHERE id = $offer_id;");

        $response = $this->validate_purchase($offer_id, $buyer_balance, $offered_price);
        if (empty($response)) {
            $seller_id = $this->wpdb->get_var("SELECT user_id FROM $offers_table WHERE id = $offer_id;");
            $offer_details = $this->wpdb->get_row("SELECT company_id, user_id, shares FROM $offers_table WHERE id = $offer_id;", ARRAY_A);

            $this->update_balances($seller_id, $buyer_id, $buyer_balance, $offered_price);
            $this->update_shares($shares_table, $offer_details, $buyer_id);
            $this->wpdb->delete($offers_table, ['id' => $offer_id], ['%d']);

            $response = [
                'status' => 'success',
                'message' => 'Shares purchased successfully!'
            ];
        }

        wp_send_json($response);
    }

    public function validate_purchase($offer_id, $buyer_balance, $offered_price)
    {
        $response = [];
        if (!$offer_id) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong!'
            ];
        }

        if ($buyer_balance[0] < $offered_price) {
            $response = [
                'status' => 'error',
                'message' => 'You have insufficient balance for this purchase!'
            ];
        }

        return $response;
    }

    public function update_balances($seller_id, $buyer_id, $buyer_balance, $offered_price)
    {
        $seller_balance = get_user_meta($seller_id, 'balance');
        $upd_seller_balance = $seller_balance[0] + $offered_price;
        $upd_buyer_balance = $buyer_balance[0] - $offered_price;

        update_user_meta($seller_id, 'balance', $upd_seller_balance);
        update_user_meta($buyer_id, 'balance', $upd_buyer_balance);
    }

    public function update_shares($shares_table, $offer_details, $buyer_id)
    {
        $company_id = $offer_details['company_id'];
        $seller_id = $offer_details['user_id'];
        $offered_shares = $offer_details['shares'];

        $buyer_shares = $this->wpdb->get_var("SELECT `sum` FROM $shares_table WHERE user_id = $buyer_id AND company_id = $company_id;");
        $upd_buyer_shares = $buyer_shares + $offered_shares;
        if (!$buyer_shares) {
            $this->wpdb->insert($shares_table, ['company_id' => $company_id, 'user_id' => $buyer_id, 'sum' => $offered_shares], ['%d']);
        } else {
            $this->wpdb->update($shares_table, ['sum' => $upd_buyer_shares], ['sum' => $buyer_shares, 'user_id' => $buyer_id, 'company_id' => $company_id], ['%d'], ['%d']);
        }

        $seller_shares = $this->wpdb->get_var("SELECT `sum` FROM $shares_table WHERE user_id = $seller_id AND company_id = $company_id;");
        $upd_seller_shares = $seller_shares - $offered_shares;
        if ($upd_seller_shares == 0) {
            $this->wpdb->delete($shares_table, ['user_id' => $seller_id, 'company_id' => $company_id], ['%d']);
        } else {
            $this->wpdb->update($shares_table, ['sum' => $upd_seller_shares], ['sum' => $seller_shares, 'user_id' => $seller_id, 'company_id' => $company_id], ['%d'], ['%d']);
        }
    }
}