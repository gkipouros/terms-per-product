<?php
/**
 * Terms & Conditions Per Product
 *
 * This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 *
 * @link              https://2squared.io
 * @since             1.0.0
 * @package           terms-per-product
 *
 * @wordpress-plugin
 * Plugin Name:       Terms and Conditions Per Product
 * Plugin URI:        https://2squared.io
 * Description:       This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 * Version:           1.0.0
 * Author:            2Squared.io
 * Author URI:        https://2squared.io/giannis-kipouros
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       terms-per-product
 * Domain Path:       /languages
 */

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    terms-per-product
 */
if ( ! defined( 'PLUGIN_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'PLUGIN_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * Include files.
 */
function gkco_include_plugin_files() {

	// Include Class files
	$files = array(
		'app/main/class-main',
	);

	// Include Includes files
	$includes = array(

	);

	// Merge the two arrays
	$files = array_merge( $files, $includes );

	foreach ( $files as $file ) {

		// Include functions file.
		require PLUGIN_PATH . $file . '.php';

	}

}

add_action( 'plugins_loaded', 'gkco_include_plugin_files' );
