<?php
/**
 * Terms & Conditions Per Product
 *
 * This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 *
 * @link              https://gianniskipouros.com
 * @since             1.0.1
 * @package           terms-per-product
 *
 * @wordpress-plugin
 * Plugin Name:       Terms and Conditions Per Product
 * Plugin URI:        https://gianniskipouros.com
 * Description:       This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 * Version:           1.0.5
 * Author:            Giannis Kipouros
 * Author URI:        https://gianniskipouros.com
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
if ( ! defined( 'TACPP4_LUGIN_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'TACPP4_PLUGIN_VERSION', '1.0.5' );
}

if ( ! defined( 'TACPP4_PLUGIN_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'TACPP4_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'TACPP4_PLUGIN_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'TACPP4_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'TACPP4_PLUGIN_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'TACPP4_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * Include files.
 */
function tacpp_include_extra_plugin_files() {

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
		require TACPP4_PLUGIN_PATH . $file . '.php';

	}

}

add_action( 'plugins_loaded', 'tacpp_include_extra_plugin_files' );
