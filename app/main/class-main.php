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
if ( ! class_exists( 'TACPP4_Terms_Conditions_Per_Product' ) ) {

    /**
     * Class for transxen core.
     */
    class TACPP4_Terms_Conditions_Per_Product {

        public $meta_key;


        /**
         * Constructor for class.
         */
        public function __construct() {

            $this->meta_key = apply_filters( 'gkco_custom_terms_meta_key', '_custom_product_terms_url' );

            // Enqueue front-end scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );

            // Enqueue Back end scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_style_scripts' ), 100 );

            // The code for displaying WooCommerce Product Custom Fields
            add_action( 'woocommerce_product_options_general_product_data',
                array( $this, 'woocommerce_product_custom_fields' ) );

            // The following code Saves  WooCommerce Product Custom Fields
            add_action( 'woocommerce_process_product_meta',
                array( $this, 'woocommerce_product_custom_fields_save' ) );


            // Add product specific Terms and Conditions to WC Checkout
            add_action( 'woocommerce_review_order_before_submit',
                array( $this, 'add_checkout_per_product_terms' ) );

            // Notify user if terms are not selected
            add_action( 'woocommerce_checkout_process',
                array( $this, 'action_not_approved_terms' ), 20 );

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
                TACPP4_PLUGIN_URL . 'assets/css/terms-per-product.css',
                '',
                TACPP4_PLUGIN_VERSION
            );

            // Register plugin's JS script
            wp_register_script(
                'terms-per-product-custom-script',
                TACPP4_PLUGIN_URL . 'assets/js/terms-per-product.js',
                array(
                    'jquery',
                ),
                TACPP4_PLUGIN_VERSION,
                true
            );

            wp_enqueue_script( 'terms-per-product-custom-script' );

        }

        /**
         * Enqueue Admin style/script.
         *
         * @return void
         */
        public function admin_enqueue_style_scripts() {

        }


        /**
         * Add custom fields to WC product
         *
         */
        public function woocommerce_product_custom_fields() {

            global $woocommerce, $post;

            if ( (int) $post->ID <= 0 || ! class_exists( 'WC_Product_Factory' ) ) {
                return;
            }

            // Set up skipped types
            $skipped_product_types = array(
                'external',
            );

            // Get product type
            $product_type = WC_Product_Factory::get_product_type( $post->ID );


            // Do not add the field if the product type is not supported
            if ( in_array( $product_type, $skipped_product_types ) ) {
                return;
            }


            ?>
			<div class="product_custom_field">
                <?php
                $args = array(
                    'id'          => $this->meta_key,
                    'placeholder' => 'Add the URL of the terms page.',
                    'label'       => __( 'Custom Terms and Condition Page (URL)', 'woocommerce' ),
                    'desc_tip'    => 'true'
                );

                // Apply filters
                $args = apply_filters( 'gkco_custom_product_terms_input_args', $args );

                // Custom Product Text Field
                woocommerce_wp_text_input( $args );
                ?>
			</div>
            <?php
        }

        /**
         * Save fields
         *
         */
        public function woocommerce_product_custom_fields_save( $post_id ) {

            // Custom Product Text Field
            $woocommerce_custom_product_text_field = $_POST[ $this->meta_key ];

            // Sanitize input
            $link = filter_var( $woocommerce_custom_product_text_field, FILTER_SANITIZE_URL );

            // Run this action before saving the link
            do_action( 'gkco_before_save_custom_product_terms_link', $link, $woocommerce_custom_product_text_field );

            // Add post meta
            update_post_meta( $post_id, $this->meta_key, esc_attr( $link ) );

        }

        /**
         * Add product Terms and Conditions in checkout page
         *
         */
        public function add_checkout_per_product_terms() {

            // Loop through each cart item
            foreach ( WC()->cart->get_cart() as $cart_item ) {

                $product_id = $cart_item['product_id'];

                $product_terms_url = trim( get_post_meta( $product_id, $this->meta_key, true ) );

                if ( ! empty( $product_terms_url ) ) {
                    ?>
					<div class="extra-terms">
						<p class="form-row terms wc-terms-and-conditions form-row validate-required">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms-<?php echo $product_id; ?>" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST[ 'terms-' . $product_id ] ) ), true ); ?> id="terms-<?php echo $product_id; ?>">

                                <?php
                                $terms_text = '<a href="[TERMS_URL]" target="_blank">[TERMS]</a> ' . __( 'of', 'terms-per-product' ) . ' <strong>[PRODUCT_TITLE]</strong>';

                                $terms_text = apply_filters(
                                    'gkco_custom_product_terms_text',
                                    $terms_text,
                                    $product_terms_url,
                                    $product_id
                                );

                                $search = array(
                                    '[TERMS_URL]',
                                    '[TERMS]',
                                    '[PRODUCT_TITLE]'
                                );

                                $replace = array(
                                    esc_html( $product_terms_url ),
                                    __( 'Terms and Conditions', 'terms-per-product' ),
                                    get_the_title( $product_id ),
                                );

                                $terms_html = str_replace( $search, $replace, $terms_text );

                                // Apply HTML filter
                                $terms_html = apply_filters(
                                		'gkco_custom_product_terms_html',
										$terms_html,
										$product_terms_url,
										$product_id
								);
                                ?>
								<span>
									<?php echo $terms_html; ?>
								</span>

								<span class="required">*</span>

							</label>
						</p>
						<div class="clearfix"></div>
					</div>
                    <?php
                }

            }
        }

        /**
         * Notify user if they do not selected the terms checkbox
         *
         */
        public function action_not_approved_terms() {

            // Loop through each cart item
            foreach ( WC()->cart->get_cart() as $cart_item ) {

                $product_id = $cart_item['product_id'];

                $product_terms_url = trim( get_post_meta( $product_id, $this->meta_key, true ) );

                // Check if the product has a custom terms page set
                if ( ! empty( $product_terms_url ) && ! isset( $_POST[ 'terms-' . $product_id ] ) ) {
                    $error_text = __( 'Please <strong>read and accept</strong> the Terms and Conditions of', 'terms-per-product' ) . " ";
                    $error_text .= "<b>" . get_the_title( $product_id ) . "</b>.";

                    // Add filter for error notice
                    $error_text = apply_filters( 'gkco_custom_product_terms_error_notice', $error_text, $product_id );

                    // Display notice
                    wc_add_notice( $error_text, 'error' );

                }

            }
        }

    }

    new TACPP4_Terms_Conditions_Per_Product();
}
