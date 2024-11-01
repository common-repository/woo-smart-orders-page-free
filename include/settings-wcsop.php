<?php
if ( ! function_exists( 'wcsop_add_admin_menu' ) ) {
	function wcsop_add_admin_menu(  ) { 

		add_options_page( 'WooCommerce Smart Orders Page', 'WooCommerce Smart Orders Page', 'manage_options', 'woocommerce_smart_orders_page', 'wcsop_options_page' );

	}
}

if ( ! function_exists( 'wcsop_settings_init' ) ) {
	function wcsop_settings_init(  ) { 

		register_setting( 'pluginPage', 'wcsop_settings' );

		add_settings_section(
			'wcsop_pluginPage_section', 
			__( 'Use the settings to turn off and reorder the columns on the WooCommerce orders page', 'woocommerce-smart-orders-page' ), 
			'wcsop_settings_section_callback', 
			'pluginPage'
		);

		add_settings_field( 
			'wcsop_checkbox_field_0', 
			__( 'Show order summary', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_0_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_1', 
			__( 'Show user ip', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_1_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_2', 
			__( 'Show OS and Browser', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_2_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);
		
		add_settings_field( 
			'wcsop_checkbox_field_7', 
			__( 'Lookup Order form in the My Account', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_7_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_4', 
			__( 'Show orders metadata', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_4_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_4_filter_radio', 
			__( 'Metadata to exclude', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_4_filter_radio_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_4_filter_string', 
			"", 
			'wcsop_checkbox_field_4_filter_string_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_checkbox_field_5', 
			__( 'Orders Compact Mode', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_5_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);
		add_settings_field( 
			'wcsop_checkbox_field_6', 
			__( 'Manage order notes from the Orders table', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_6_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);
		add_settings_field( 
			'wcsop_checkbox_field_3', 
			__( 'Enable custom marker', 'woocommerce-smart-orders-page' ), 
			'wcsop_checkbox_field_3_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'custom_marker_name', 
			__( 'Custom marker name', 'woocommerce-smart-orders-page' ), 
			'custom_marker_name_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);

		add_settings_field( 
			'wcsop_reorder_column', 
			__( 'Reorder Woocommerce columns', 'woocommerce-smart-orders-page' ), 
			'wcsop_reorder_column_render', 
			'pluginPage', 
			'wcsop_pluginPage_section' 
		);
	}
}

/**
 * Order statuses list in the admin.
 */
if( !function_exists( "wcsop_settings_statuses_list" ) ) {

	function wcsop_settings_statuses_list() {
		?>
		<table class="form-table widefat striped wcsop_orders_statuses_table">
			<thead>
				<tr>
					<th class="wcsop_enabled_column">Enabled</th>
					<th>Order Status</th>
					<th>Order Icon</th>
					<th>Icon Color</th>
					<th>Icon Type</th>
				</tr>
			</thead>
			<?php 
				WCSOP_Order_Status::build_row();
			?>
		</table>
		<?php
	}

}

/**
 * New order status page in the admin.
 */
if( !function_exists( "wcsop_settings_new_status" ) ) {

	function wcsop_settings_new_status() {
	?>
		<table class="form-table widefat custom-status">
			<?php WCSOP_Order_Status::get_order_status_fields(); ?>
		</table>

	<?php
	}

}

/**
 * New order status page in the admin.
 */
if( !function_exists( "wcsop_settings_get_status" ) ) {

	function wcsop_settings_get_status( $title ) {
	?>
		<table class="form-table widefat custom-status">
			<?php WCSOP_Order_Status::get_order_status_fields( $title ); ?>
		</table>

	<?php
	}

}

if ( ! function_exists( 'custom_marker_name_render' ) ) {
	function custom_marker_name_render() {
		global $wcsop;
		$options = $wcsop->get_options();

		?>
			<input type="text" value="<?= $options['custom_marker_name'] ?>" id="custom_marker_name" name="wcsop_settings[custom_marker_name]">
		<?php
	}
}

if ( ! function_exists( 'wcsop_reorder_column_render' ) ) {
	function wcsop_reorder_column_render() {
		global $wcsop;
		$options = $wcsop->get_options();
		?>
			<ul id="woocommerce_columns">
				<?= wcsop_woocommerce_columns() ?>
			</ul>
			<input type="hidden" value="<?= $options['wcsop_reorder_column'] ?>" id="reorder_column_import" name="wcsop_settings[wcsop_reorder_column]">
		<?php
	}
}

if ( ! function_exists( 'wcsop_woocommerce_columns' ) ) {
	function wcsop_woocommerce_columns() {
		global $wcsop;

		$columns                     = array();
		$columns['cb']     =  __( 'Checkbox','woocommerce-smart-orders-page');
		$columns['order_status']     = '<span class="status_head tips" data-tip="' . esc_attr__( 'Status', 'woocommerce' ) . '">' . esc_attr__( 'Status', 'woocommerce' ) . '</span>';
		$columns['order_title']      = __( 'Order', 'woocommerce' );
		$columns['billing_address']  = __( 'Billing', 'woocommerce' );
		$columns['shipping_address'] = __( 'Ship to', 'woocommerce' );
		$columns['customer_message'] = '<span class="notes_head tips" data-tip="' . esc_attr__( 'Customer message', 'woocommerce' ) . '">' . esc_attr__( 'Customer message', 'woocommerce' ) . '</span>';
		$columns['order_notes']      = '<span class="order-notes_head tips" data-tip="' . esc_attr__( 'Order notes', 'woocommerce' ) . '">' . esc_attr__( 'Order notes', 'woocommerce' ) . '</span>';
		$columns['add_order_note']   = '<span class="add-order-notes_head tips" data-tip="' . esc_attr__( 'Add Order notes', 'woocommerce' ) . '">' . esc_attr__( 'Add Order notes', 'woocommerce' ) . '</span>';
		$columns['order_date']       = __( 'Date', 'woocommerce' );
		$columns['order_total']      = __( 'Total', 'woocommerce' );
		$columns['order_actions']    = __( 'Actions', 'woocommerce' );
		$columns['woocommerce-order-items'] = __( 'Order Summary','woocommerce-smart-orders-page');
		$columns['customer_ip_address'] = __( 'Customer IP','woocommerce-smart-orders-page');
		$columns['billing_address_index'] = __( 'Customer OS and Browser','woocommerce-smart-orders-page');
		$columns['order_marker'] = __( 'Custom marker','woocommerce-smart-orders-page');

		// is subscriptions addon is active
		if( $wcsop->subscriptions_enabled )
			$columns['subscription_relationship'] = __( 'Subscription Relationship','woocommerce-smart-orders-page');

		$result = '';
		foreach ($columns as $key => $column) {
			$result .= '<li data-id="'. $key .'">';
			$result .= $column;
			$result .= '</li>';
		}
		return $result;
	}
}

if ( ! function_exists( 'wcsop_checkbox_field_0_render' ) ) {
	function wcsop_checkbox_field_0_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_0]' <?php checked( $options['wcsop_checkbox_field_0'], 1 ); ?> value='1'>
		<?php
	}
}

if ( ! function_exists( 'wcsop_checkbox_field_1_render' ) ) {
	function wcsop_checkbox_field_1_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_1]' <?php checked( $options['wcsop_checkbox_field_1'], 1 ); ?> value='1'>
		<?php
	}
}

if ( ! function_exists( 'wcsop_checkbox_field_2_render' ) ) {
	function wcsop_checkbox_field_2_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_2]' <?php checked( $options['wcsop_checkbox_field_2'], 1 ); ?> value='1'>
		<?php
	}
}

if ( ! function_exists( 'wcsop_checkbox_field_3_render' ) ) {
	function wcsop_checkbox_field_3_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_3]' <?php checked( $options['wcsop_checkbox_field_3'], 1 ); ?> value='1'>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_4_render' ) ) {
	function wcsop_checkbox_field_4_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_4]' <?php checked( $options['wcsop_checkbox_field_4'], 1 ); ?> value='1'>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_4_filter_radio_render' ) ) {
	function wcsop_checkbox_field_4_filter_radio_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<section class="metadata_to_exclude">
			<input name="metadata_to_exclude" type="radio" id="begins_with" value="begins_with" checked >
			<label for="begins_with"><?php _e( "Begins with", "woocommerce-smart-orders-page" ); ?></label>
			
			<input name="metadata_to_exclude" type="radio" id="contains" value="contains" <?php if( $options['wcsop_checkbox_field_4_filter_radio'] == "contains" ) echo "checked"; ?> >
			<label for="contains"><?php _e( "Contains", "woocommerce-smart-orders-page" ); ?></label>

			<input name="metadata_to_exclude" type="radio" id="ends_with" value="ends_with" <?php if( $options['wcsop_checkbox_field_4_filter_radio'] == "ends_with" ) echo "checked"; ?> >
			<label for="ends_with"><?php _e( "Ends with", "woocommerce-smart-orders-page" ); ?></label>

			<input type='hidden' id="metadata_to_exclude__hidden_field" name='wcsop_settings[wcsop_checkbox_field_4_filter_radio]' value="<?php echo $options['wcsop_checkbox_field_4_filter_radio']; ?>" >
		</section>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_4_filter_string_render' ) ) {
	function wcsop_checkbox_field_4_filter_string_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='text' name='wcsop_settings[wcsop_checkbox_field_4_filter_string]' value="<?php echo trim( $options['wcsop_checkbox_field_4_filter_string'] ); ?>" >
		<p class="description">
			E.g., if you've excluded metadata that begins with "_joo" the metafield with the key "_joomla" will be excluded but the key "_wordpress" will be on its place.<br>
			Also, here might be only one symbol, e.g., "_", don't be afraid.
		</p>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_5_render' ) ) {
	function wcsop_checkbox_field_5_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_5]' <?php checked( $options['wcsop_checkbox_field_5'], 1 ); ?> value='1'>
		<p class="description">
			(Click on counter link and Order Summary will expand to full view - <a href="<?= plugin_dir_url( __FILE__ ) . '../documentation/lessons/sop_compact_mode.gif' ?>" target="_blank">more info</a>)
		</p>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_6_render' ) ) {
	function wcsop_checkbox_field_6_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_6]' <?php checked( $options['wcsop_checkbox_field_6'], 1 ); ?> value='1'>
		<?php
	}
}
if ( ! function_exists( 'wcsop_checkbox_field_7_render' ) ) {
	function wcsop_checkbox_field_7_render(  ) { 
		global $wcsop;
		$options = $wcsop->get_options();
		?>
		<input type='checkbox' name='wcsop_settings[wcsop_checkbox_field_7]' <?php checked( $options['wcsop_checkbox_field_7'], 1 ); ?> value='1'>
		<?php
	}
}

if ( ! function_exists( 'wcsop_settings_section_callback' ) ) {
	function wcsop_settings_section_callback(  ) { 
		echo __( 'Available columns:', 'woocommerce-smart-orders-page' );
	}
}

/**
 * General - options page.
 */
if ( ! function_exists( 'wcsop_options_page' ) ) {
	function wcsop_options_page() { 
		$active_tab = isset( $_GET['tab'] ) ? wc_clean( $_GET['tab'] ) : 'dashboard';
		$section = isset( $_GET['section'] ) ? wc_clean( $_GET['section'] ) : false;
	?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=woocommerce_smart_orders_page&tab=dashboard" class="nav-tab <?php if( $active_tab == "dashboard" ) echo 'nav-tab-active'; ?>">Dashboard</a>
		<a href="?page=woocommerce_smart_orders_page&tab=general" class="nav-tab <?php if( $active_tab == "general" ) echo 'nav-tab-active'; ?>">General Settings</a>
		<a href="?page=woocommerce_smart_orders_page&tab=statuses" class="nav-tab <?php if( $active_tab == "statuses" ) echo 'nav-tab-active'; ?>">Order Statuses Manager</a>
	</h2>
	
	<div class="wrap woocommerce">


		<h2 style="display: inline;">
			<?php 
			switch( $active_tab ) {
				case "dashboard":
					echo "Dashboard";
					break;
				case "general":
					echo "General Settings";
					break;
				case "statuses":
					echo "Order Statuses";
					break;
			} ?>
		</h2>
		<hr>			

		<?php 
		if( $active_tab == "dashboard" ) { ?>
			<div id="wcsop_dashboard_wrapper">
				<div class="wcsop_dashboard_period">
					<div class="wcsop_dashboard_period__col">
						<p class="wcsop_dashboard_period__text">Detailing by</p>
						<span id="wcsop_dashboard_period__d" class="wcsop_dashboard_period__detailing active" data-detailing="d">Days</span>
						<span id="wcsop_dashboard_period__m" class="wcsop_dashboard_period__detailing" data-detailing="m">Months</span>
						<span id="wcsop_dashboard_period__y" class="wcsop_dashboard_period__detailing" data-detailing="y">Years</span>
					</div>
					<div class="wcsop_dashboard_period__col">
						<p class="wcsop_dashboard_period__text">Date Range</p>
						<input id="wcsop_dashboard_period__daterange" name="wcsop_dashboard_period__daterange">
					</div>
				</div>
				<div class="wcsop_dashboards_charts">
					<div class="wcsop_dashboard_period__col">
						<?php WCSOP_Statistics::display_total_sales_by_period(); ?>
						<?php WCSOP_Statistics::display_top_10_customers(); ?>
					</div>
					<div class="wcsop_dashboard_period__col">
						<?php WCSOP_Statistics::display_geography_and_volume(); ?>
						<?php WCSOP_Statistics::display_top_10_products(); ?>
					</div>
				</div>
			</div>
		<?php
		}
		else if( $active_tab == "general" ) {

			echo "<form action='options.php' method='post'>";
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			echo "</form>";

		} else if( $active_tab == "statuses" ) {
			wcsop_settings_statuses_list();
		}
		?>

	</div>
	<?php 
	}
} 