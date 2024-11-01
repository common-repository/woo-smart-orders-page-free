<?php 
/**
 * All the statistics functions to display in the Admin Panel.
 * */

 class WCSOP_Statistics {

    /**
     * Get total sales by time period
     * d - day
     * w - week
     * m - month
     * y - year
     *
     * @param string $period - default, by day
     * @return array
     */
    public static function get_total_sales_by_period() {

        $total_sales_array["2017, May"] = 0;
        $total_sales_array["2017, June"] = 498;
        $total_sales_array["2017, July"] = 1856;
        $total_sales_array["2017, August"] = 1514;
        $total_sales_array["2017, September"] = 50;

        wp_send_json( $total_sales_array );

    }
    
    /**
     * Output content of Total Sales by Period widget
     *
     * @return void
     */
    public static function display_total_sales_by_period() {

        echo '<div id="wcsop_total_sales_content" class="wcsop_chart"></div>';

    }

    /**
     * Get orders stats by country 
     *
     * @return void
     */
    public static function get_geography_and_volume() {
        
        $geography_and_volume_array["Australia"] = 20027.2;
        $geography_and_volume_array["Austria"] = 13249.88;
        $geography_and_volume_array["Belgium"] = 49041;
        $geography_and_volume_array["Brazil"] = 1767;
        $geography_and_volume_array["Canada"] = 1964;
        $geography_and_volume_array["Cuba"] = 7461;
        $geography_and_volume_array["France"] = 17123;
        $geography_and_volume_array["India"] = 18416.2;
        $geography_and_volume_array["Ireland"] = 10192;
        $geography_and_volume_array["Japan"] = 13447;
        $geography_and_volume_array["New Zealand"] = 1434;
        $geography_and_volume_array["Russia"] = 7721.6;
        $geography_and_volume_array["United Kingdom (UK)"] = 2788.08;
        $geography_and_volume_array["United States (US)"] = 13514;
        $geography_and_volume_array["currency"] = "USD";

        wp_send_json( $geography_and_volume_array );

    }

    /**
     * Output content of Geo + Vol widget
     *
     * @return void
     */
    public static function display_geography_and_volume() {

        echo '<div id="wcsop_geography_and_volume_content" class="wcsop_chart"></div>';

    }

    /**
     * Get top 10 customers
     *
     * @return void
     */
    public static function get_top_10_customers() {

        for( $i = 0; $i < 10; $i++ ) {
            $top_10_customers_array[$i]["total"] = 100 + 5 * rand(1, 50);
            $top_10_customers_array[$i]["name"] = self::random_name();
            $top_10_customers_array[$i]["id"] = 1;
        }

        $top_10_customers_array["currency"] = "USD";

        wp_send_json( $top_10_customers_array );

    }

    private static function random_name() {
        $names = array(
            'Christopher',
            'Ryan',
            'Ethan',
            'John',
            'Zoey',
            'Sarah',
            'Michelle',
            'Samantha',
        );
         
        //PHP array containing surnames.
        $surnames = array(
            'Walker',
            'Thompson',
            'Anderson',
            'Johnson',
            'Tremblay',
            'Peltier',
            'Cunningham',
            'Simpson',
            'Mercado',
            'Sellers'
        );
         
        //Generate a random forename.
        $random_name = $names[mt_rand(0, sizeof($names) - 1)];
         
        //Generate a random surname.
        $random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];
         
        //Combine them together and print out the result.
        return $random_name . ' ' . $random_surname;
    }

    private static function sort_customers_by_total( $a, $b ) {
        if( $a["total"] == $b["total"] )
            return 0;

        return ( $a["total"] < $b["total"] ) ? 1 : -1;
    }

    /**
     * Output content of top 10 customers widget
     *
     * @return void
     */
    public static function display_top_10_customers() {

        echo '
        <div id="wcsop_top_10_customers_content" class="wcsop_chart">
            <h3>Top 10 Customers</h3>
            <table>
                <th>Name</th>
                <th>Total</th>
            </table>
        </div>';

    }

    /**
     * Get top 10 products
     *
     * @return void
     */
    public static function get_top_10_products() {
        
        $top_10_products_array[0]["name"] = "Happy Ninja";
        $top_10_products_array[0]["link"] = "#";
        $top_10_products_array[0]["sales"] = "515";
        $top_10_products_array[0]["price"] = "18";

        $top_10_products_array[1]["name"] = "Woo Single #2";
        $top_10_products_array[1]["link"] = "#";
        $top_10_products_array[1]["sales"] = "472";
        $top_10_products_array[1]["price"] = "3";

        $top_10_products_array[2]["name"] = "Happy Ninja";
        $top_10_products_array[2]["link"] = "#";
        $top_10_products_array[2]["sales"] = "460";
        $top_10_products_array[2]["price"] = "35";

        $top_10_products_array[3]["name"] = "Woo Album #2";
        $top_10_products_array[3]["link"] = "#";
        $top_10_products_array[3]["sales"] = "458";
        $top_10_products_array[3]["price"] = "9";

        $top_10_products_array[4]["name"] = "Flying Ninja";
        $top_10_products_array[4]["link"] = "#";
        $top_10_products_array[4]["sales"] = "451";
        $top_10_products_array[4]["price"] = "15";

        $top_10_products_array[5]["name"] = "Happy Ninja";
        $top_10_products_array[5]["link"] = "#";
        $top_10_products_array[5]["sales"] = "446";
        $top_10_products_array[5]["price"] = "18";

        $top_10_products_array[6]["name"] = "Woo Single #2";
        $top_10_products_array[6]["link"] = "#";
        $top_10_products_array[6]["sales"] = "443";
        $top_10_products_array[6]["price"] = "3";

        $top_10_products_array[7]["name"] = "Happy Ninja";
        $top_10_products_array[7]["link"] = "#";
        $top_10_products_array[7]["sales"] = "438";
        $top_10_products_array[7]["price"] = "35";

        $top_10_products_array[8]["name"] = "Woo Album #2";
        $top_10_products_array[8]["link"] = "#";
        $top_10_products_array[8]["sales"] = "435";
        $top_10_products_array[8]["price"] = "9";

        $top_10_products_array[9]["name"] = "Flying Ninja";
        $top_10_products_array[9]["link"] = "#";
        $top_10_products_array[9]["sales"] = "430";
        $top_10_products_array[9]["price"] = "15";

        $top_10_products_array["currency"] = "USD";

        wp_send_json( $top_10_products_array );

    }

    private static function sort_products_by_sales( $a, $b ) {
        if( $a["sales"] == $b["sales"] )
            return 0;

        return ( $a["sales"] < $b["sales"] ) ? 1 : -1;
    }

    /**
     * Output content of top 10 products widget
     *
     * @return void
     */
    public static function display_top_10_products() {

        echo '
        <div id="wcsop_top_10_products_content" class="wcsop_chart">
            <h3>Top 10 Products for all the time</h3>
            <table>
                <th>Name</th>
                <th>Sales</th>
                <th>Price</th>
            </table>
        </div>';

    }

 }