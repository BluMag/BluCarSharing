<?php

/**
 * Fired during plugin activation
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Wp_Bcs
 * @subpackage Wp_Bcs/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Bcs
 * @subpackage Wp_Bcs/includes
 * @author     Wekitech <contact@google.com>
 */
class Wp_Bcs_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;

        $bcs_page_title = 'Blu Covoiturage';
        $bcs_page_name = 'blu-covoiturage';

        delete_option("bcs_page_title");
        add_option("bcs_page_title", $bcs_page_title, '', 'yes');

        delete_option("bcs_page_name");
        add_option("bcs_page_name", $bcs_page_name, '', 'yes');

        delete_option("bcs_page_id");
        add_option("bcs_page_id", '0', '', 'yes');

        $bcs_page = get_page_by_title($bcs_page_title);

        if (!$bcs_page) {
            // Creation of post object
            $_p = array();
            $_p['post_title'] = $bcs_page_title;
            $_p['post_content'] = 'This text may be edited by the plugin, don\'t touch it';
            $_p['post_status'] = 'publish';
            $_p['post_type'] = 'page';
            $_p['comment_status'] = 'closed';
            $_p['ping_status'] = 'closed';
            $_p['post_category'] = array(1); //Uncategorised

            $bcs_page_id = wp_insert_post($_p);
        }
        else {
            // Make sure the page is visible
            $bcs_page_id = $bcs_page->ID;

            $bcs_page->post_status = 'publish';
            $bcs_page_id = wp_update_post($bcs_page);
        }

        delete_option('bcs_page_id');
        add_option('bcs_page_id', $bcs_page_id);

        ///////////////////////////////////////////////////////////////////////

        $table_name = $wpdb->prefix . "bcs_pathes";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT NOT NULL,
            time_of_departure DATETIME NOT NULL,
            start_point TEXT NOT NULL,
            end_point TEXT NOT NULL,
            description TEXT NOT NULL,
            seats INT NOT NULL,
            PRIMARY KEY (id)
        );';

        $wpdb->query( $sql  );
    }

}
