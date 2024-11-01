<?php 
/**
 * Lookup order widget.
 */

class WCSOP_Lookup_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct( "wcsop_lookup_widget", "WC Smart Orders - Lookup Order Widget", array(
            "description" => "A widget for your unregistered customers to view a single order.",
            "classname" => "wcsop_lookup_widget"
        ) );
    }

    /**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
        $title = $instance["title"];
        $lookup_page = get_permalink( get_option( "wcsop_lookup_order_page_id" ) );
        if( get_current_user_id() > 0 ) {
            $lookup_page = wc_get_page_permalink( "myaccount" );
        } ?>
        
        <form id="wcsop_lookup_widget" method="POST" class="widget widget_recent_comments" action="<?php echo $lookup_page; ?>">
            <h2><?php echo $title; ?></h2>
            <label for="wcsop_lookup_order_widget__number">Order number<span class="required">*</span></label><br>
            <input type="text" id="wcsop_lookup_order_widget__number" name="order_id" placeholder="Order number.." /><br>
            <?php if( get_current_user_id() == 0 ) : ?>
                <label for="wcsop_lookup_order_widget__email">Email<span class="required">*</span></label><br>
                <input type="email" id="wcsop_lookup_order_widget__email" name="email" placeholder="Your email.." /><br>
            <?php endif; ?>
            <br>
            <input type="submit" class="woocommerce-Button button" name="lookup_order" id="wcsop_lookup_order_widget__button" value="<?php esc_attr_e( 'Lookup Order', 'woocommerce' ); ?>" />
        </form>
        <?php
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
        $title = "";
        // если instance не пустой, достанем значения
        if (!empty($instance)) {
            $title = $instance["title"];
        }
    
        $tableId = $this->get_field_id("title");
        $tableName = $this->get_field_name("title");
        echo '<label for="' . $tableId . '">Title</label><br>';
        echo '<input id="' . $tableId . '" type="text" name="' .
        $tableName . '" value="' . $title . '"><br>';
    
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
        $values = array();
        $values["title"] = htmlentities($new_instance["title"]);

        if( !$this->the_slug_exists( $values["title"] ) ) {
            $page_title = $values["title"];
            $page_content = '[lookup_order_page]';
            $page_check = get_page_by_title( $page_title );
            $page = array(
                'post_type' => 'page',
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_slug' => sanitize_title( $page_title ),
            );

            if( !isset( $page_check->ID ) ) {
                $page_id = wp_insert_post( $page );
                update_option( "wcsop_lookup_order_page_id", $page_id );
            }
        }

        return $values;
	}

    private function the_slug_exists( $post_name ) {
        global $wpdb;
        if( $wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A') ) {
            return true;
        } else {
            return false;
        }
    }
}