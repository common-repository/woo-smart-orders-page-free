/**
 * Drawing charts for statistics.
 */

(function($) {

    $(document).ready(function() {
        var buy_premium_timer = setTimeout(show_buy_premium_version_message, 10000);
        $(document).on("click", "#wcsop_dashboard_wrapper", show_buy_premium_version_message);

        // init the Google Chart
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(getCharts);

        var period = "d";

        var total_sales_data = {
            action: "get_total_sales",
            period: period
        };
        var geo_and_vol_data = {
            action: "get_geography_and_volume",
            period: period
        };
        var top_10_customers_data = {
            action: "get_top_10_customers",
            period: period
        };
        var top_10_products_data = {
            action: "get_top_10_products"
        };

        var top_products_loaded = false;

        // get all charts
        function getCharts() {
            get_total_sales_chart();
            get_geography_and_volume_chart();
            get_top_10_customers();
            if(!top_products_loaded)
                get_top_10_products();
        }

        // total sales by period chart
        function get_total_sales_chart() {
            total_sales_data.period = period;
            $("#wcsop_total_sales_content > div").remove();
            
            $.post(ajaxurl, total_sales_data, function(response) {
                var x = "Day";
                switch(period) {
                    case "m":
                        x = "Month";
                    break;
                    case "y":
                        x = "Year";
                    break;
                    default:
                        x = "Day";
                    break;
                }
                var arr = build_google_array__total_sales(response, x, "Sales");
                var data = google.visualization.arrayToDataTable(arr);

                var ticks = [
                        arr[1][0],
                        arr[arr.length - 1][0]
                    ];
                
                var options = {
                    title: 'Total Sales',
                    curveType: 'none',
                    hAxis: { 
                        ticks: ticks,
                    },
                    focusTarget: "category",
                    titleTextStyle: {
                        fontSize: 16
                    },
                    chartArea: {
                        left: "10%",
                        top: 45,
                        width: "75%",
                        height: "60%"
                    }
                };

                var chart = new google.visualization.LineChart(document.getElementById('wcsop_total_sales_content'));

                chart.draw(data, options);
            });
        }

        // geography + volume chart
        function get_geography_and_volume_chart() { 
            geo_and_vol_data.period = period; 
            $("#wcsop_geography_and_volume_content > div").remove();

            $.post(ajaxurl, geo_and_vol_data, function(response) {
                var arr = build_google_array__geo_and_vol(response, "Country", "Sales");
                var data = google.visualization.arrayToDataTable(arr);
                
                var options = {
                    title: 'Sales by Country (' + response["currency"] + ')',
                    titleTextStyle: {
                        fontSize: 16
                    },
                    chartArea: {
                        left: "10%",
                        top: 45,
                        width: "95%",
                        height: "90%"
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('wcsop_geography_and_volume_content'));

                chart.draw(data, options);
            });
        }

        // Top 10 customers
        function get_top_10_customers() { 
            top_10_customers_data.period = period; 
            $("#wcsop_top_10_customers_content table tr td").parent().remove();

            $.post(ajaxurl, top_10_customers_data, function(response) {
                var cnt = 0;
                for (var key in response) {
                    if(key != "currency") {
                        var row = "<tr>";
                        row += "<td><a href=\"#\">" + response[key].name + "</td>"
                        row += "<td>" + response[key].total + " " + response["currency"] + "</td>"
                        row += "</tr>";
                        $("#wcsop_top_10_customers_content table").append(row);
                        cnt ++;
                    }
                }
                if(cnt < 1) {
                    var row = "<tr>";
                    row += "<td colspan='2'>No data</td>"
                    row += "</tr>";
                    $("#wcsop_top_10_customers_content table").append(row);
                }

            });
        }

        // Top 10 products
        function get_top_10_products() { 
            $("#wcsop_top_10_products_content table tr td").parent().remove();

            $.post(ajaxurl, top_10_products_data, function(response) {
                var cnt = 0;
                for (var key in response) {
                    if(key != "currency") {
                        var row = "<tr>";
                        row += "<td><a href=\"" + response[key].link + "\">" + response[key].name + "</td>"
                        row += "<td>" + response[key].sales + "</td>"
                        row += "<td>" + response[key].price + " " + response["currency"] + "</td>"
                        row += "</tr>";
                        $("#wcsop_top_10_products_content table").append(row);
                        cnt ++;
                    }
                }
                if(cnt < 1) {
                    var row = "<tr>";
                    row += "<td colspan='2'>No data</td>"
                    row += "</tr>";
                    $("#wcsop_top_10_products_content table").append(row);
                }

                top_products_loaded = true;
            });
        }

        // handling time changing on jquery daterangepicker
        function on_change_time_detailing(e) {
            $(".wcsop_dashboard_period__detailing").removeClass("active");
            $(this).addClass("active");

            period = $(this).data("detailing");
            getCharts();
        }

        /* UTILITY */

        // utility formatting date to pass to php
        function format_date(date) {
            var monthNames = [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];

            var day = date.getDate();
            var monthIndex = date.getMonth() + 1;
            var year = date.getFullYear();

            return year + "-" + monthIndex + "-" + day;
            
        }

        // utitilty - build google array for total sales
        function build_google_array__total_sales(obj, x, y) {
            var arr = [ [x, y] ];
            for (var key in obj) {
                arr.push([new Date(key), obj[key]]);
            }
            return arr;
        }

        // utitilty - build google array for geography + volume
        function build_google_array__geo_and_vol(obj, x, y) {
            var arr = [ [x, y] ];
            for (var key in obj) {
                if( key != "currency" )
                    arr.push([key, obj[key]]);
            }
            return arr;
        }

        function show_buy_premium_version_message(e) {
            $(document).off("click", "#wcsop_dashboard_wrapper", show_buy_premium_version_message);
            if(buy_premium_timer)
                clearTimeout(buy_premium_timer);

            var $dashboard_wrapper = $("#wcsop_dashboard_wrapper");
            $dashboard_wrapper.addClass("blurred");
            $dashboard_wrapper.after("<span class='wcsop_buy_premium_version_message'><p>To get this functionality</p><a href='https://codecanyon.net/item/woocommerce-smart-orders-page-for-woocommerce-30/19743352'>buy Premium Version</a></span>");
        }

    });

})(jQuery);