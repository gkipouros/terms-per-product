<?php
/**
 * Class for custom work.
 *
 * @package Terms_Conditions_Per_Product
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'Terms_Conditions_Per_Product' ) ) {

	/**
	 * Class for transxen core.
	 */
	class Terms_Conditions_Per_Product {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			$files = array();

			if ( ! empty( $files ) ) {

				foreach ( $files as $file ) {

					// Include functions file.
					require TXC_PATH . $file . '.php';

				}
			}

			// Enqueue front-end scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );


			// Enqueue Back end scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_style_scripts' ), 100 );

		}



		/**
		 * Enqueue style/script.
		 *
		 * @return void
		 */
		public function enqueue_style_scripts() {

			// Custom plugin script.
			wp_enqueue_style(
				'terms-per-product-core-style',
				PLUGIN_URL . 'assets/css/plugin-core.css',
				'',
				PLUGIN_VERSION
			);

			// Register plugin's JS script
			wp_register_script(
				'terms-per-product-custom-script',
				PLUGIN_URL . 'assets/js/plugin-core.js',
				array(
					'jquery',
				),
				PLUGIN_VERSION,
				true
			);


	        // Provide a global object to our JS file containing the AJAX url and security nonce
	        wp_localize_script( 'terms-per-product-custom-script', 'ajaxObject',
	            array(
	                'ajax_url'      => admin_url('admin-ajax.php'),
	                'ajax_nonce'    => wp_create_nonce('ajax_nonce'),
					//'plugin_url'	=> plugins_url('/', __FILE__),
	            )
	        );
	        wp_enqueue_script( 'terms-per-product-custom-script');

		}

		/**
		 * Enqueue Admin style/script.
		 *
		 * @return void
		 */
		public function admin_enqueue_style_scripts() {

		}
	}

	new Terms_Conditions_Per_Product();
}
