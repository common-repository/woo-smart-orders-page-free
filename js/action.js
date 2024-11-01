( function($){
    /* fire when document ready */
    $(document).ready(function() {
        // add merge for orders
        $('<option>').val('merge_orders').text('Combine orders (Premium version)')
                .appendTo("select[name='action'], select[name='action2']");

    });
}(jQuery))