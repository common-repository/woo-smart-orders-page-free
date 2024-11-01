<?php
/*
 * Plugin Name: WooCommerce Smart Orders Page FREE
 * Plugin URI: http://festplugins.com/woocommerce-smart-orders-page-for-woocommerce-30/
 * Description: User-friendly interface for store administrator. Has so much functions available in the <a href="https://codecanyon.net/item/woocommerce-smart-orders-page-for-woocommerce-30/19743352">PREMIUM VERSION</a>.
 * Version: 1.1.4
 * Author: FEST Agency
 * Author URI: http://festplugins.com/
 * Developer: Dmitry Yakimchuk & Ruslan Askarov & Ildar Axmetov
 * Developer URI: http://festplugins.com/
 * Text Domain: woocommerce-smart-orders-page
 *
 * Requires at least: 3.8
 * Tested up to: 4.7
 *
 * Copyright: © 2009-2017 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * WC requires at least: 3.0
 * WC tested up to: 3.0.9
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !defined( "WCSOP_CSS" ) )
	define( "WCSOP_CSS", plugin_dir_path( __FILE__ ) . "css/" );

/**
 * WC Detection
 *
 * @return boolean
 */

if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ;
	}
}

/**
 * WooCommerce inactive notice. 
 *
 */
if ( ! function_exists( 'wcsop_woocommerce_inactive_notice' ) ) {
	function wcsop_woocommerce_inactive_notice() {
		if ( current_user_can( 'activate_plugins' && is_admin() ) ) {
			echo '<div id="message" class="error"><p>';
			printf( __( '%1$sWooCommerce Smart Orders Page is inactive%2$s. %3$sWooCommerce plugin %4$s must be active for Smart Orders Page to work. Please %5$sinstall and activate WooCommerce &raquo;%6$s', 'woocommerce-smart-orders-page'), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce' ) ) . '">', '</a>' );
			echo '</p></div>';
		}
	}
}

// load plugin core

include 'include/class-wcsop.php';
include 'include/statistics.class.php';
include 'include/order-status.class.php';
include 'include/settings-wcsop.php';
include 'include/lookup-order-widget.class.php';
include 'include/vendor/donatj/phpuseragentparser/Source/UserAgentParser.php';

add_action( "plugins_loaded", function() {
	if( is_woocommerce_active() ) {
		global $wcsop;
		$wcsop = new WCsop;
	}				
	else
		add_action( "admin_notices", 'wcsop_woocommerce_inactive_notice' );
});

add_action( 'admin_enqueue_scripts', 'wsop_vendors_js' );
add_action( 'wp_enqueue_scripts', 'wcsop_front_js' );

add_action("widgets_init", function () {
    register_widget("WCSOP_Lookup_Widget");
});

add_action( "wp_ajax_get_total_sales", array( "WCSOP_Statistics", "get_total_sales_by_period" ) );
add_action( "wp_ajax_get_geography_and_volume", array( "WCSOP_Statistics", "get_geography_and_volume" ) );
add_action( "wp_ajax_get_top_10_customers", array( "WCSOP_Statistics", "get_top_10_customers" ) );
add_action( "wp_ajax_get_top_10_products", array( "WCSOP_Statistics", "get_top_10_products" ) );


function wsop_admin_style() {
  wp_enqueue_style( 'wsop-admin-styles', plugin_dir_url( __FILE__ ) . 'css/main.css');
}

add_action('admin_enqueue_scripts', 'wsop_admin_style');

if ( ! function_exists( 'wsop_vendors_js' ) ) {
	function wsop_vendors_js() {
		$current_screen = get_current_screen();
		wp_register_style( 'order_statuses', plugin_dir_url( __FILE__ ) . 'css/order-statuses.css', array(), uniqid() );
		wp_enqueue_style( 'order_statuses', plugin_dir_url( __FILE__ ) . 'css/order-statuses.css');

		if($current_screen->base == 'settings_page_woocommerce_smart_orders_page') {

			wp_register_style( 'order_statuses', plugin_dir_url( __FILE__ ) . 'css/order-statuses.css', array(), uniqid() );
			wp_enqueue_style( 'order_statuses', plugin_dir_url( __FILE__ ) . 'css/order-statuses.css');
			wp_enqueue_script( 'action_wsop', plugin_dir_url( __FILE__ ) . 'js/action.js', array('jquery')); 

			if( ( isset( $_GET["tab"] ) && $_GET["tab"] == "general" ) )
				wp_enqueue_script( 'main_wsop', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery', 'sortable_js', 'wp-color-picker' ));
			else if( ( isset( $_GET["tab"] ) && $_GET["tab"] == "statuses" ) )
				wp_enqueue_script( 'main_wsop', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery', 'wp-color-picker' ) );
			else if( isset( $_GET["tab"] ) && $_GET["tab"] == "dashboard" || !isset( $_GET["tab"] ) ) {
				wp_enqueue_script( 'main_wsop', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery', 'wp-color-picker' ) );
				wp_enqueue_script( 'google_chart', plugin_dir_url( __FILE__ ) . 'js/loader.js' );
     
				wp_enqueue_script( 'wcsop_statistics', plugin_dir_url( __FILE__ ) . 'js/statistics.js', array( 'jquery', 'google_chart' ) );
			}
		}
		if($current_screen->base == 'settings_page_woocommerce_smart_orders_page' && ( ( isset( $_GET["tab"] ) && $_GET["tab"] == "general" ) ) )
			wp_enqueue_script( 'sortable_js', plugin_dir_url( __FILE__ ) . 'node_modules/sortablejs/sortable.js');

		if($current_screen->base == 'edit' && stristr( $current_screen->post_type, "order" ) !== false )
			wp_enqueue_script( 'action_wsop', plugin_dir_url( __FILE__ ) . 'js/action.js', array('jquery')); 
		
	}
}

if( !function_exists( "wcsop_front_js" ) ) {
	
	function wcsop_front_js() {
		wp_enqueue_script( 'wscop_front', plugin_dir_url( __FILE__ ) . 'js/front.js', array('jquery', 'jquery-blockui') );
		wp_localize_script( 
			'wscop_front', 
			'wcsop',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ) 
			) 
		);
	}

}

if( !function_exists( "wcsop_order_details_table" ) ) {

	/**
	 * Displays order details in a table.
	 *
	 * @param mixed $order_id
	 * @subpackage	Orders
	 */
	function wcsop_order_details_table( $order_id, $show_customer ) {
		if ( ! $order_id ) return;

		wc_get_template( 'order/order-details.php', array(
			'order_id' => $order_id,
			'show_customer' => $show_customer,
		) );
	}

}
add_action( 'wcsop_view_order', 'wcsop_order_details_table', 10, 2 );

function wcsop_add_docs_link( $links ) {
    $settings_link = '<a href="http://festplugins.com/wp-content/plugins/woocommerce-smart-orders-page/documentation/" target="_blank">' . __( 'Documentation' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wcsop_add_docs_link' );
