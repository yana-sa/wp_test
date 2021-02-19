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

    jQuery('[data-form="exchange-offer"]').click(function (e) {
        e.preventDefault();
        var company = jQuery(this).attr('data-select="company_shares"');
        var shares = jQuery(this).attr('');
        var price = jQuery(this).attr('');
        var $this = jQuery(this)
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: myAjax.ajaxurl,
            data: {action: "shares_exchange_offer", company: company, shares: shares, price:price},
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