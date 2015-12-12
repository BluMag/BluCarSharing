<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              google.com
 * @since             1.0.0
 * @package           Wp_Bcs
 *
 * @wordpress-plugin
 * Plugin Name:       BluCarSharing
 * Plugin URI:        google.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Wekitech
 * Author URI:        google.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-bcs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-bcs-activator.php
 */
function activate_wp_bcs() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bcs-activator.php';
    Wp_Bcs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-bcs-deactivator.php
 */
function deactivate_wp_bcs() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bcs-deactivator.php';
    Wp_Bcs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_bcs' );
register_deactivation_hook( __FILE__, 'deactivate_wp_bcs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-bcs.php';


function get_pathes() {
    global $wpdb;

    $pathes = $wpdb->get_results(
        "
        SELECT *
        FROM $wpdb->posts
        "
    );
    return $pathes;
}

function zero_modify_page_title($title) {
    $var = "";
    $pathes = get_pathes();
    foreach ( $pathes as $path  )
    {
        $var = $var . ' | ' . $path->post_title;
    }
    return $var;
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_bcs() {

    $plugin = new Wp_Bcs();
    $plugin->run();
    add_filter('the_title', 'zero_modify_page_title', 20);
}

run_wp_bcs();
