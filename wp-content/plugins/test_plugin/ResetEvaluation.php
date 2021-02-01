<?php

class ResetEvaluation
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function reset_book_evaluation()
    {
        $user_id = get_current_user_id();
        $post_id = $_POST['post_id'];

        $error_message = self::validate_reset_book_evaluation($user_id, $post_id);
        $rating = get_post_meta($post_id, '_rating_for_books', true);

        if (!empty($error_message)) {
            self::book_evaluation_response('error', $error_message, $rating);
        }

        $sql = $this->wpdb->get_row("SELECT 'action' FROM $this->wpdb->wp_book_evaluation WHERE user_id = '$user_id' AND post_id = '$post_id'", ARRAY_A);
        $evaluation = $sql['action'];
        if ($evaluation == 'like') {
            $new_rating = $rating - 1;
        } else {
            $new_rating = $rating + 1;
        }

        $rating_update = update_post_meta($post_id, '_rating_for_books', $new_rating);
        $this->wpdb->delete('wp_book_evaluation', ['user_id' => $user_id, 'post_id' => $post_id], ['%d', '%s']);

        if ($rating_update !== false) {
            $rating = $new_rating;
        }

        self::book_evaluation_response('success', '', $rating);
    }

    private function validate_reset_book_evaluation($user_id, $post_id)
    {
        if (!empty($user_id)) {
            $is_eval = $this->wpdb->get_col("SELECT 1 FROM $this->wpdb->wp_book_evaluation WHERE `user_id` = '$user_id' AND `post_id` = '$post_id'", ARRAY_A);
            if (empty($is_eval)) {
                return 'You have not evaluated this book yet!';
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
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}