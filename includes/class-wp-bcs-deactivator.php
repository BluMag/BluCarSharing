<?php

/**
 * Fired during plugin deactivation
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Wp_Bcs
 * @subpackage Wp_Bcs/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Bcs
 * @subpackage Wp_Bcs/includes
 * @author     Wekitech <contact@google.com>
 */
class Wp_Bcs_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        global $wpbd;

        $bcs_page_title = get_option("bcs_page_title");
        $bcs_page_name = get_option("bcs_page_name");

        $bcs_page_id = get_option("bcs_page_id");
        if ($bcs_page_id) {
             wp_delete_post($bcs_page_id);
        }

        delete_option("bcs_page_title");
        delete_option("bcs_page_name");
        delete_option("bcs_page_id");
	}

}
