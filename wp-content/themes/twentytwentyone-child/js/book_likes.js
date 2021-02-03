jQuery(document).ready( function() {
    jQuery(".like, .dislike").click( function(e) {
        e.preventDefault();
        let id = jQuery(this.id);
        let split_id = id.split("_");

        let value = split_id[0];
        let post_id = split_id[1];
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl + "?book_evaluation_data",
            data : {action:"book_evaluation_data", post_id:post_id, value:value},
            success: function(response) {
                if(response.status === "success") {
                    jQuery("#rating_for_books").html(response.rating);
                    if (value === 'like') {
                        jQuery("#like").css("background_color", "#a0a0a0")
                    }
                    if (value === 'dislike') {
                        jQuery("#dislike").css("background_color", "#a0a0a0")
                    }
                    alert(response.message);
                }
                else {
                    alert(response.message);
                }
            }
        });
    });
});