<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WCsop {
    public function __construct() {
        add_action( 'admin_menu', 'wcsop_add_admin_menu' );
        add_action( 'admin_init', 'wcsop_settings_init' );
        add_filter( 'woocommerce_locate_template', array( $this, 'override_wc_template' ), 1, 3 );

        /*custome filtering*/
        add_action( 'restrict_manage_posts', array( $this, 'add_product_filter_to_orders_page' ) );
        
        add_action( 'wp_ajax_wcsop_get_order', array( $this, 'get_order') );
        add_action( 'wp_ajax_nopriv_wcsop_get_order', array( $this, 'get_order') );
        
        if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) )
            $this->subscriptions_enabled = true;
        else 
            $this->subscriptions_enabled = false;
        
        if( is_admin() ) {
            add_filter( 'manage_edit-shop_order_columns', array( $this, 'custom_shop_order_column'), PHP_INT_MAX);
            add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'custom_shop_order_row'), 10, 2 );
        }  

        add_shortcode( "lookup_order_page", array( $this, "show_lookup_order_page" ) );      
    }

    /**
    *   Override the standard WC orders page templates.
    *
    *   @param  string $template 
    *           string $template_name
    *           string $template_path
    *   @return string - Template file name
    */
    public function override_wc_template( $template, $template_name, $template_path ) {
        $options = $this->get_options();
        if( $options["wcsop_checkbox_field_7"] !== "1" )
            return $template;

        #run this only for myaccount/orders
        if($template_name === "myaccount/orders.php" || $template_name === "order/order-details.php"  || $template_name === "myaccount/view-order.php") {
            global $woocommerce;

            $_template = $template;
            if ( ! $template_path ) 
                $template_path = $woocommerce->template_url;
        
            $plugin_path  = untrailingslashit( dirname( plugin_dir_path( __FILE__ ) ) )  . "/templates/";
        
            #depending on priority first look to plugin or to theme
            if( true ) {
                $template = $plugin_path . $template_name;  
                
                if( ! $template && file_exists( $template_path . $template_name ) ) {
                    $template = locate_template(
                    array(
                        $template_path . $template_name,
                        $template_name
                        )
                    );
                }
            } else {
                $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                    )
                );
            
                if( ! $template && file_exists( $plugin_path . $template_name ) ) {
                    $template = $plugin_path . $template_name;
                }
            }
            
            if ( ! $template )
                $template = $_template;
        }

        return $template;
    }    

    /**
     * Add columns in WooCommerce order page
     *
     * @param [array] $columns
     * @return void
     */
    public function custom_shop_order_column($columns) {
        $ordered_columns = $this->get_ordered_columns();
        $result = array_merge($columns, $ordered_columns);
        return $result;        
    }

    public function get_ordered_columns() {
        $options = $this->get_options();
        $string_columns = $options['wcsop_reorder_column'];
        $explode_columns = explode(',', $string_columns);
        $result_array = array();
        foreach ($explode_columns as $key) {
            $result_array[$key] = '';
        }

        $result_array['woocommerce-order-items'] = __( 'Order Summary','woocommerce-smart-orders-page');
        $result_array['customer_ip_address'] = __( 'Customer IP','woocommerce-smart-orders-page');
        $result_array['billing_address_index'] = __( 'Customer OS and Browser','woocommerce-smart-orders-page');

        if(array_key_exists('', $result_array)) {
            unset($result_array['']);
        }

        return $result_array;
    }

    /**
     * Add content to custom columns
     *
     * @param [type] $column
     * 
     */
    public function custom_shop_order_row( $column, $postid ) {
        $options = $this->get_options();
        global $woocommerce, $the_order;
        if(empty($the_order))
            $order = wc_get_order( $postid );
        else 
            $order = $the_order;
        $order_id = $order->get_order_number();

        switch ( $column )
        {
            case 'woocommerce-order-items' :  
                echo $this->get_order_summary($order_id);
                break;
            case 'customer_ip_address' :
                echo $this->get_ip_address($order_id);
                break;
            case 'billing_address_index' :
                echo $this->get_browser_info($order_id);                
                break;
        }
    }

    public function get_browser_info($order_id) {
        $user_agent = get_post_meta( $order_id, '_customer_user_agent', true);
        if(!$user_agent)
            return false;
        
        $ua_info = parse_user_agent($user_agent);
        foreach($ua_info as $key => $property) {
            if($property == '' && $key != 'version') {
                $ua_info[$key] = 'undefined';
            }
        }
        $browser_ver = explode('.', $ua_info['version']);
        $result = 'OS: ' . $ua_info['platform'] . '<br>';
        $result .= 'Browser: ' . $ua_info['browser'] . ' ';
        $result .= $browser_ver[0];
        return $result;
    }

    public function get_ip_address($order_id) {
        return get_post_meta( $order_id, '_customer_ip_address', true );
    }

    /**
     * HTML order items
     *
     * @param [number] $order_id
     * @return HTML
     */
    public function get_order_summary($order_id) {
            global $the_order;

            if(empty($order_id))
                return ' - ';
            $options = $this->get_options();
            if ( empty( $the_order  ) ) {
                $order = wc_get_order( $order_id );
            } else {
                $order = $the_order;
            }
            
            if(method_exists($order, 'get_items')) {
                $items = $order->get_items();
            }
            else {
                return ' - ';
            }
            
            $count = count($items);
            $result = '';
            $meta = '';
            $count_items = $count . ' ' . _n('item', 'items', $count, 'woocommerce-smart-orders-page') . ': ';

            $result .= '<div class="order_data">';
            foreach ( $items as $item ) {                
                $result .= '<a href="' . get_permalink($item['product_id']) . '">';
                $result .= $item['name'];
                $result .= '</a>';
                $result .= ' x ' . $item['quantity'];
                $result .= '<hr>';
            }
            $result .= "</div>";
        return $result;
    }

    /**
     * Get plugin options
     *
     * @return array
     */
    public function get_options( $option_name = "wcsop_settings" ) {
        $default = $this->get_default_options();
        $options = get_option( $option_name, $default);
        foreach ($default as $key => $value ) {
            if(!array_key_exists($key, $options)) {
                $options[$key] = '';
            }
        }

        return $options;
    }

    /**
     * Default options
     *
     * @see get_options()
     */
    public function get_default_options( $key = false ) {
        $result = array(
            'wcsop_checkbox_field_0' => 1,
            'wcsop_checkbox_field_1' => 1,
            'wcsop_checkbox_field_2' => 1,
            'wcsop_checkbox_field_3' => false,
            'wcsop_checkbox_field_4' => false,
            'wcsop_checkbox_field_4_filter_radio' => "begins_with",
            'wcsop_checkbox_field_4_filter_string' => "",
            'wcsop_checkbox_field_5' => false,
            'wcsop_checkbox_field_6' => false,
            'wcsop_checkbox_field_7' => false,
            'custom_marker_name' => 'Custom marker',
            'wcsop_reorder_column' => ''
        );
        if($key) {
            return $result[$key];
        }

        return $result;
    }

    /**
     * Get the order for the registered user
     *
     * @return void
     */
    public function get_order() {
        $number = intval( wc_clean( $_POST["wcsop_number"] ) );
        $email = wc_clean( $_POST["wcsop_email"] );
        $user = wp_get_current_user();

        $return;

        $order = wc_get_order( $number );

        # is there such order
        if( is_null( $order ) || empty( $order ) || !$order ) {
            $return = array( 
                "error" => array(
                    "code" => "number", 
                    "message" => '<div class="woocommerce-error">' . __( 'Invalid order number.', 'woocommerce' ) . '</div>' 
                ) 
            );
            echo json_encode( $return );
            wp_die();
        }

        if( $user->ID > 0 ) {
            #if user registered than search the order and check if it belongs to a current user
            
            if( $order->get_customer_id() == $user->ID ) {
                $return = array( 
                    "logged" => $order->get_view_order_url() 
                );
            } else {
                $return = array( 
                    "error" => array(
                        "code" => "wrong_email", 
                        "message" => '<div class="woocommerce-error">' . __( 'You are unable to lookup this order.', 'woocommerce' ) . '</div>' 
                    ) 
                );
            }
        } else {
            if( $order->get_billing_email() == $email && !empty( $email ) ) {
                $return = array( 
                    "not_logged" => sprintf( "%s?order_id=%s&email=%s", get_permalink( get_option( "wcsop_lookup_order_page_id" ) ), $order->get_order_number(), $email )
                 );
            } else {
                $return = array( 
                    "error" => array(
                        "code" => "not_logged_and_wrong_email", 
                        "message" => '<div class="woocommerce-error">' . __( 'You are unable to lookup this order.', 'woocommerce' ) . '</div>' 
                    ) 
                );
            }
        }

        echo json_encode( $return );
        wp_die();
    }

    /**
     * Show a custom page with a passed order info for unregistered users.
     *
     * @param [type] $atts
     * @return void
     */
    public function show_lookup_order_page( $atts ) {
        $order_id = isset( $_POST["order_id"] ) ? wc_clean( $_POST["order_id"] ) : 0;
        $email = isset( $_POST["email"] ) ? wc_clean( $_POST["email"] ) : 0;

        $order   = wc_get_order( $order_id );

        if( !$order ) {
            echo '<div class="woocommerce-error">' . __( 'Invalid order.', 'woocommerce' ) . ' <a href="' . wc_get_page_permalink( 'shop' ) . '" class="wc-forward">' . __( 'Back to Shop', 'woocommerce' ) . '</a>' . '</div>';
            return;
        }
            

        if( $order->get_billing_email() == $email ) {

            // Backwards compatibility
            $status       = new stdClass();
            $status->name = wc_get_order_status_name( $order->get_status() );

            wc_get_template( 'myaccount/view-order.php', array(
                'status'    => $status, // @deprecated 2.2
                'order'     => wc_get_order( $order_id ),
                'order_id'  => $order_id,
                'show_customer' => true
            ) );

        } else {
            echo '<div class="woocommerce-error">' . __( 'Invalid order.', 'woocommerce' ) . ' <a href="' . wc_get_page_permalink( 'shop' ) . '" class="wc-forward">' . __( 'Back to Shop', 'woocommerce' ) . '</a>' . '</div>';
        }
    }
    
    /**
     * Filters for orders page.
     */
    public function add_product_filter_to_orders_page() {
        ?>
        <select class="wc-product-search" name="_product_id" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-allow_clear="true">
            <option value="<?php _e( 'This filter is available in the Premium Version' ); ?>" selected="selected"><?php _e( 'This filter is available in the Premium Version' ); ?><option>
        </select>
        <?php
    }
    
}

