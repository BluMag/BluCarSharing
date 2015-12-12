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

function generate_bcs_data() {
    global $wpdb;

    $table_name = $wpdb->prefix . "bcs_pathes";
    $user_table = $wpdb->prefix . "users";
    $pathes = $wpdb->get_results('SELECT * FROM ' . $table_name);

    $var = "<h3>Trajets</h3><br />";
    foreach ( $pathes as $path  )
    {
        $driver_name = $wpdb->get_var('SELECT display_name FROM ' . $user_table);
        $var = $var . '<div><h4>' . $path->time_of_departure . ', from ' . $path->start_point . ' to ' . $path->end_point . '</h4>';
        $var = $var . '<p>' . $path->description . '</p>';
        $var = $var . '<em>' . $path->seats . ' places disponibles</em> ';
        $var = $var . ' - Conducteur : <strong> ' . $driver_name . '</strong></div><br>';
    }
    return $var;
 }

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
            $_p['post_content'] = generate_bcs_data();
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
