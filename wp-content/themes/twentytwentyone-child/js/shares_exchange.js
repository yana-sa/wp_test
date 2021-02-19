jQuery(document).ready(function () {

    jQuery('[data-select="company_shares"]').change(function () {
        var shares = "You own ";
        jQuery("select option:selected").each(function() {
            shares += jQuery(this).attr('data-sum') + " shares";
            jQuery(this).closest('[data-form="exchange-offer"]').find('[data-input="number_of_shares"]').attr("max", jQuery(this).attr('data-sum'));
        });
        jQuery('[data-company="sum"]').text(shares);
    })
        .trigger( "change" );

    jQuery('[data-form="exchange-offer"]').submit(function (e) {
        e.preventDefault();
        var company_id = jQuery(this).find('select[data-select="company_shares"]').val()
        var shares = jQuery(this).find('input[data-input="number_of_shares"]').val()
        var price = jQuery(this).find('input[data-input="price"]').val()
        var $this = jQuery(this)
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: myAjax.ajaxurl,
            data: {action: "shares_exchange_offer",
                company_id:company_id,
                shares:shares,
                price:price},
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    });
});