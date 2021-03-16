jQuery(document).ready(function () {
    jQuery('[data-page="1"]').attr("class", "active");
    jQuery('[data-button="pagination"]').click(function (e) {
        e.preventDefault();
        var page = jQuery(this).attr('data-page');
        var topic_id = jQuery(this).attr('data-topic_id');
        var $this = jQuery(this);

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: myAjax.ajaxurl,
            data: {action:"topic_posts_pagination", page:page, topic_id:topic_id},
            success: function (response) {
                var posts_data = '';
                for(var i = 0; i <= response.length; i++) {
                    var data = response[i];
                    posts_data += '<h4><li class="topic-list-item"><div class="topic-list-div">';
                    posts_data += '<div class="topic-list-post-div">';
                    posts_data += '<a href="' + data.link + '">' + data.title + '</a>';
                    posts_data += '<br/><span><h5>' + data.content + '</span>';
                    posts_data += '</div><div class="topic-list-author-div">' + data.author_pic;
                    posts_data += '<h5>' + data.author_name + '</h5>';
                    posts_data += '<h5>' + data.author_role + '</h5>';
                    posts_data += '</div></div></li>';

                    jQuery('[data-list="topic-list"]').html(posts_data);
                    jQuery('[data-button="pagination"]').attr("class", " ");
                    jQuery($this).attr("class", "active");
                }
            }
        });
    });
})