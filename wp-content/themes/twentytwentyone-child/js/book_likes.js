jQuery(document).ready(function () {
    jQuery('[data-button="evaluation"]').click(function (e) {
        e.preventDefault();
        var post_id = jQuery(this).attr('data-post-id');
        var evaluation = jQuery(this).attr('data-action');
        var $this = jQuery(this)
        if (jQuery(this).attr('data-chosen') === undefined) {
            sendEvaluationRequest(post_id, evaluation, $this);
        } else {
            sendResetEvaluationRequest(post_id, evaluation, $this);
        }
    });
});

function sendEvaluationRequest(post_id, evaluation, $this) {
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: myAjax.ajaxurl,
        data: {action: "book_evaluation_data", post_id: post_id, evaluation: evaluation},
        success: function (response) {
            if (response.status === "success") {
                $this.closest('[data_parent="parent"]').find('[data-p="book-rating"]').html("Rating: " + response.rating_for_books);
                if (evaluation === 'like') {
                    $this.attr("style", "background-color:#8c8c8c").attr("data-chosen", "true");
                }
                if (evaluation === 'dislike') {
                    $this.attr("style", "background-color:#8c8c8c").attr("data-chosen", "true");
                }
            } else {
                alert(response.message);
            }
        }
    });
}

function sendResetEvaluationRequest(post_id, evaluation, $this) {
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: myAjax.ajaxurl,
        data: {action: "reset_book_evaluation", post_id: post_id},
        success: function (response) {
            if (response.status === "success") {
                $this.closest('[data_parent="parent"]').find('[data-p="book-rating"]').html("Rating: " + response.rating_for_books);
                if (evaluation === 'like') {
                    $this.attr("style", "background-color:#efefef").removeAttr("data-chosen");
                }
                if (evaluation === 'dislike') {
                    $this.attr("style", "background-color:#efefef").removeAttr("data-chosen");
                }
            } else {
                alert(response.message);
            }
        }
    });
}