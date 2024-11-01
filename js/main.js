( function($){
    /* fire when document ready */
    $(document).ready(function() {
        // sortable init and save to localstorage
        hide_options_for_free();

        var buy_premium_timer = setTimeout(show_buy_premium_version_message, 10000);
        $(document).on("click", ".wcsop_orders_statuses_table", show_buy_premium_version_message);

        var el = document.getElementById('woocommerce_columns');
        var sortable;
        if(typeof Sortable !== "undefined") {
            sortable = Sortable.create(el, {
                group: "localStorage-example",
                store: {
                    /**
                     * Get the order of elements. Called once during initialization.
                     * @param   {Sortable}  sortable
                     * @returns {Array}
                     */
                    get: function (sortable) {
                        var order = localStorage.getItem(sortable.options.group.name);
                        return order ? order.split('|') : [];
                    },

                    /**
                     * Save the order of elements. Called onEnd (when the item is dropped).
                     * @param {Sortable}  sortable
                     */
                    set: function (sortable) {
                        var order = sortable.toArray();
                        push_to_input(order);
                        localStorage.setItem(sortable.options.group.name, order.join('|'));
                    }
                }
            }) 
        }   
        
        function show_buy_premium_version_message(e) {
            $(document).off("click", ".wcsop_orders_statuses_table", show_buy_premium_version_message);
            if(buy_premium_timer)
                clearTimeout(buy_premium_timer);
    
            var $dashboard_wrapper = $(".wcsop_orders_statuses_table");
            $dashboard_wrapper.addClass("blurred");
            $dashboard_wrapper.after("<span class='wcsop_buy_premium_version_message'><p>To get this functionality</p><a href='https://codecanyon.net/item/woocommerce-smart-orders-page-for-woocommerce-30/19743352'>buy Premium Version</a></span>");
        }     
    });
    /* standalone jquery functions */
    // columns to input
    function push_to_input(order) {
        $('#reorder_column_import').val(order);
    }

    function hide_options_for_free() {
        if(window.location.search.indexOf("page=woocommerce_smart_orders_page&tab=general") !== -1) {
            $rows = $(".form-table tr");
            for(i = 4; i < $rows.length; i++) {
                
                var $row = $($rows[i]);

                $row.children("th").css({
                    "color": "#999",
                    "padding-left": "20px"
                });
                $row.css({
                    "cursor": "no-drop",
                    "border-left": "4px #e04e4e solid"
                });
                $row.find("input").attr("disabled", "true");
                $row.find("input").css({
                    "cursor": "no-drop"
                });
                $row.find("p").css({
                    "color": "#aaa"
                });

                if(i == $rows.length - 1) {
                    $row.find("ul#woocommerce_columns").css({
                        "color": "#aaa",
                        "cursor": "no-drop",
                        "filter": "blur(1px)"
                    });
                }
                if(i == 4) {
                    $row.append("<p id='wcsop-msg'><strong>These options are available in the <a href='https://codecanyon.net/item/woocommerce-smart-orders-page-for-woocommerce-30/19743352'>Premium Version</a></strong></p>");
                    $row.find("#wcsop-msg").css({
                        "position": "absolute",
                        "left": "0",
                        "font-size": "30px",
                        "line-height": "40px",
                        "margin-top": "200px",
                        "width": "100%",
                        "text-align": "center",
                        "z-index": "5"
                    });
                    $row.find("strong").css({
                        "background-color": "white",
                        "padding": "45px",
                        "box-shadow": "2px 2px 2px 0px grey",
                    });
                }
            }
        }
    }
    /* */
}(jQuery))