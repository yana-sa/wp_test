jQuery(document).ready( function() {
    jQuery("#like, #dislike").click( function(e) {
        e.preventDefault();
        post_id = jQuery(this).attr('data-post-id');
        evaluation = jQuery(this).attr('data-action');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action:"book_evaluation_data", post_id:post_id, evaluation:evaluation},
            success: function(response) {
                if(response.status === "success") {
                    jQuery("#rating_for_books").html("Rating: "+ response.rating_for_books);
                    if (evaluation === 'like') {
                        jQuery("#like").prop('disabled', true);
                    }
                    if (evaluation === 'dislike') {
                        jQuery("#dislike").prop('disabled', true);
                    }
                }
                else {
                    alert(response.message);
                }
            }
        });
    });
});