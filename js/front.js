/**
 * WCSOP actions on the front.
 */

(function($) {

    /**
     * Lookup order.
     */
    function lookupOrder(e) {
        e.preventDefault();

        $lookup_btn = $(this);
        $lookup_form = $(this).closest("form");

        // for the my account
        $lookup_number_elem = $("input#lookup_order_number");
        lookup_number = $("input#lookup_order_number").val();
        $lookup_email_elem = $("input#lookup_order_number");
        lookup_email = "";        

        $lookup_form.children(".woocommerce-error").remove();
        $lookup_form.children("p").children(".woocommerce-error").remove();

        // if the widget was used
        if($lookup_form.attr("id") == "wcsop_lookup_widget") {
            $lookup_number_elem = $("input#wcsop_lookup_order_widget__number");
            lookup_number = $("input#wcsop_lookup_order_widget__number").val();
            $lookup_email_elem = $("input#wcsop_lookup_order_widget__email");
            lookup_email = $("input#wcsop_lookup_order_widget__email").val();            
        }

        $lookup_form.block({
            message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
        });

        var data = {
            action: "wcsop_get_order",
            wcsop_number: lookup_number,
            wcsop_email: lookup_email,
        };

        $.post(wcsop.ajaxurl, data, function(response) {
            response = JSON.parse(response);
            if(response.error) {
                //throw error
                $(response.error.message).insertBefore($lookup_btn);
            } else if(response.logged) {
                $lookup_form.attr("action", response.logged);
                $lookup_form.submit();
            } else if(response.not_logged) {
                $lookup_form.submit();
            }
            $lookup_form.unblock();
        });
    }

    /**
     * Remove all the errors in current form
     */
    function removeWCErrors(e) {
        $("#wcsop_lookup_widget").children(".woocommerce-error").remove();
        $(".lookup-order").children("p").children(".woocommerce-error").remove();
    }

    $(document).on("click", "#wcsop_lookup_order", lookupOrder);
    $(document).on("click", "#wcsop_lookup_order_widget__button", lookupOrder);

    $(document).on("change click", "input#lookup_order_number, input#wcsop_lookup_order_widget__number, input#wcsop_lookup_order_widget__email", removeWCErrors);

})(jQuery);