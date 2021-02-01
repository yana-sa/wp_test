<?php

class Evaluation
{
    private $wpdb;
    public function book_evaluation_data()
    {
        $user_id = get_current_user_id();
        $post_id = $_POST['post_id'];
        $evaluation = $_POST['evaluation'];

        $rating = get_post_meta($post_id, '_rating_for_books', true);
        $error_message = self::validate_book_evaluation($user_id, $post_id, $evaluation);

        if (!empty($error_message)) {
            self::book_evaluation_response('error', $error_message, $rating);
        }

        if ($evaluation == 'like') {
            $new_rating = $rating + 1;
        } else {
            $new_rating = $rating - 1;
        }

        $rating_update = update_post_meta($post_id, '_rating_for_books', $new_rating);
        $this->wpdb->insert('wp_book_evaluation', ['user_id' => $user_id, 'post_id' => $post_id, 'action' => $evaluation], ['%s']);

        if ($rating_update !== false) {
            $rating = $new_rating;
        }

        self::book_evaluation_response('success', '', $rating);
    }

    private function validate_book_evaluation($user_id, $post_id, $evaluation)
    {
        if (!in_array($evaluation, ['like', 'dislike'])) {
            return 'Evaluation type is not valid';
        }

        if (!empty($user_id)) {
            $is_eval = $this->wpdb->get_col("SELECT 1 FROM $this->wpdb->wp_book_evaluation WHERE user_id = '$user_id' AND post_id = '$post_id'", ARRAY_A);
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