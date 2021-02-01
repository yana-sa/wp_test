<?php

class Evaluation
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function book_evaluation_data()
    {
        $user_id = get_current_user_id();
        $post_id = $_POST['post_id'];
        $evaluation = $_POST['evaluation'];
        $table_name = $this->wpdb->prefix . 'book_evaluation';

        $rating = get_post_meta($post_id, '_rating_for_books', true);
        $error_message = $this->validate_book_evaluation($user_id, $post_id, $evaluation);

        if (!empty($error_message)) {
            $this->book_evaluation_response('error', $error_message, $rating);
        }

        if ($evaluation == 'like') {
            $new_rating = $rating + 1;
        } else {
            $new_rating = $rating - 1;
        }

        $rating_update = update_post_meta($post_id, '_rating_for_books', $new_rating);
        $this->wpdb->insert($table_name, ['user_id' => $user_id, 'post_id' => $post_id, 'action' => $evaluation], ['%s']);

        if ($rating_update !== false) {
            $rating = $new_rating;
        }

        $this->book_evaluation_response('success', '', $rating);
    }

    private function validate_book_evaluation($user_id, $post_id, $evaluation)
    {
        $table_name = $this->wpdb->prefix . 'book_evaluation';
        if (!in_array($evaluation, ['like', 'dislike'])) {
            return 'Evaluation type is not valid';
        }

        if (!empty($user_id)) {
            $is_eval = $this->wpdb->get_col("SELECT 1 FROM $table_name WHERE user_id = '$user_id' AND post_id = '$post_id'", ARRAY_A);
            if (!empty($is_eval)) {
                return 'You have already rated this post';
            }
        } else {
            return 'You are not logged in!';
        }
    }

    private function book_evaluation_response($status, $message, $rating)
    {
        if (!empty($rating)) {
            $result['rating_for_books'] = $rating;
        }
        $result['status'] = $status;
        $result['message'] = $message;

        wp_send_json($result);
    }
}