jQuery(document).ready( function() {
    jQuery(".book_post_evaluation").click( function(e) {
        e.preventDefault();
        post_id = jQuery(this).attr("data-post_id");
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "book_post_evaluation", post_id : post_id},
            success: function(response) {
                if(response.type === "success") {
                    alert("Your vote has affected the rating");
                }
                else {
                    alert("Something went wrong! Please note that you can vote only once.");
                }
            }
        });
    });
});