<?php 
/**
 * Order Status class
 * 
 * @var statuses_option - is the option name in DB where all the custom WCSOP order statuses are in.
 * @var icon - is the string that will be concatinated to "dashicons-" class
 * @var icon_type - can be of four values: round-filled, round-light, rect-filled, rect-light
 * @var color - the color of a filled part of the status icon
 */

class WCSOP_Order_Status {

    /**
     * Build row for settings page
     *
     * @param [type] $status
     * @param [type] $index
     * @return void
     */
    public static function build_row() {
        ?>
        <tr>
            <td class="wcsop-order-cell-enabled">
                <span class="dashicons dashicons-yes" style="font-size:30px; color:green;"></span>
            </td>
            <td class="wcsop-order-cell-name">
                <p><a class="wcsop_order_status_name" href="#">Homy</a></p>
                <div>
                    <a class="wcsop_edit_order_status" href="#">Edit</a>
                    <span>|</span>
                    <a id="wcsop_delete_custom_order" data-name="Homy">Delete</a>
                </div>
            </td>
            <td class="wcsop-order-cell-icon">
                <span class="wcsop_preview-0 dashicons dashicons-admin-home" data-name="Homy" data-icon="admin-home" data-color="#19aa25" data-icon_type="round-light" style="border: 1px solid rgb(25, 170, 37); border-radius: 30px; padding: 6px; font-size: 18px; margin-top: 0px; color: rgb(25, 170, 37); background-color: white;"></span>
            </td>
            <td class="wcsop-order-cell-color">
                #19aa25            
            </td>
            <td class="wcsop-order-cell-icon-type">
                round-light
            </td>
        </tr>

        <tr>
            <td class="wcsop-order-cell-enabled">
                <span class="dashicons dashicons-yes" style="font-size:30px; color:green;"></span>
            </td>
            <td class="wcsop-order-cell-name">
                <p><a class="wcsop_order_status_name" href="#">Worldwide</a></p>
                <div>
                    <a class="wcsop_edit_order_status" href="#">Edit</a>
                    <span>|</span>
                    <a id="wcsop_delete_custom_order" data-name="Worldwide">Delete</a>
                </div>
            </td>
            <td class="wcsop-order-cell-icon">
                <span class="wcsop_preview-1 dashicons dashicons-admin-site" data-name="Worldwide" data-icon="admin-site" data-color="#205fdb" data-icon_type="round-filled" style="border: 1px solid rgb(32, 95, 219); border-radius: 30px; padding: 6px; font-size: 18px; margin-top: 0px; background-color: rgb(32, 95, 219); color: white;"></span>
            </td>
            <td class="wcsop-order-cell-color">
                #205fdb
            </td>
            <td class="wcsop-order-cell-icon-type">
                round-filled
            </td>
        </tr>
        <?php
    }

}