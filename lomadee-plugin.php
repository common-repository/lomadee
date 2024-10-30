<?php

/**
 * Lomadee
 *
 * @since             0.0.1
 * @package           lomadee-oficial
 *
 * @wordpress-plugin
 * Plugin Name:       Lomadee
 * Plugin URI:        https://wordpress.org/plugins/lomadee
 * Description:       A Lomadee ajuda você a divulgar as melhores ofertas do e-commerce automaticamente no seu blog. Instale agora mesmo.
 * Version:           0.0.1
 * Author:            Lomadee
 * Author URI:        http://www.lomadee.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lomadee-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_lomadee_plugin() {

	$plugin = new lomadee_plugin();
	$plugin->run();

}
run_lomadee_plugin();
