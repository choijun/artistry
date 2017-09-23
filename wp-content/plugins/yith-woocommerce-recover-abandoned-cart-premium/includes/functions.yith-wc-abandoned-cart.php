<?php
if ( !defined( 'ABSPATH' ) || ! defined( 'YITH_YWRAC_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements helper functions for YITH WooCommerce Recover Abandoned Cart
 *
 * @package YITH WooCommerce Recover Abandoned Cart
 * @since   1.0.0
 * @author  Yithemes
 */

global $yith_ywrac_db_version;

$yith_ywrac_db_version = '1.0.0';

if ( !function_exists( 'yith_ywrac_db_install' ) ) {
    /**
     * Install the table yith_ywrac_email_log
     *
     * @return void
     * @since 1.0.0
     */
    function yith_ywrac_db_install() {
        global $wpdb;
        global $yith_ywrac_db_version;

        $installed_ver = get_option( "yith_ywrac_db_version" );

        if ( $installed_ver != $yith_ywrac_db_version ) {

            $table_name = $wpdb->prefix . 'yith_ywrac_email_log';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`email_id` varchar(255) NOT NULL,
		`email_template_id` int(11) NOT NULL,
		`ywrac_cart_id` int(11) NOT NULL,
		`date_send` datetime NOT NULL,
		PRIMARY KEY (id)
		) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            add_option( 'yith_ywrac_db_version', $yith_ywrac_db_version );
        }
    }
}



if ( !function_exists( 'yith_ywrac_update_db_check' ) ) {
    /**
     * check if the function yith_ywrac_db_install must be installed or updated
     *
     * @return void
     * @since 1.0.0
     */
    function yith_ywrac_update_db_check() {
        global $yith_ywrac_db_version;

        if ( get_site_option( 'yith_ywrac_db_version' ) != $yith_ywrac_db_version ) {
            yith_ywrac_db_install();
        }
    }
}


if ( !function_exists( 'yith_ywrac_locate_template' ) ) {
    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $path
     * @param array  $var
     *
     * @return void
     * @since 1.0.0
     */
    function yith_ywrac_locate_template( $path, $var = NULL ) {
        global $woocommerce;

        if ( function_exists( 'WC' ) ) {
            $woocommerce_base = WC()->template_path();
        }
        elseif ( defined( 'WC_TEMPLATE_PATH' ) ) {
            $woocommerce_base = WC_TEMPLATE_PATH;
        }
        else {
            $woocommerce_base = $woocommerce->plugin_path() . '/templates/';
        }

        $template_woocommerce_path = $woocommerce_base . $path;
        $template_path             = '/' . $path;
        $plugin_path               = YITH_YWRAC_DIR . 'templates/' . $path;

        $located = locate_template( array(
            $template_woocommerce_path, // Search in <theme>/woocommerce/
            $template_path,             // Search in <theme>/
            $plugin_path                // Search in <plugin>/templates/
        ) );

        if ( !$located && file_exists( $plugin_path ) ) {
            return apply_filters( 'yith_ywrac_locate_template', $plugin_path, $path );
        }

        return apply_filters( 'yith_ywrac_locate_template', $located, $path );
    }
}


if ( !function_exists( 'yith_ywrac_get_excerpt' ) ) {
    /**
     * Return the excerpt of template email
     *
     * @return void
     * @since 1.0.0
     */
    function yith_ywrac_get_excerpt( $post_id ) {
        $post         = get_post( $post_id );
        $excerpt      = ( $post->post_excerpt != '' ) ? $post->post_excerpt : $post->post_content;
        $num_of_words = apply_filters( 'yith_ywrac_get_excerpt_num_words', 10 );
        return wp_trim_words( $excerpt, $num_of_words );
    }
}


if ( !function_exists( 'yith_ywrac_get_roles' ) ) {
    /**
     * Return the roles of users
     *
     * @return void
     * @since 1.0.0
     */
    function yith_ywrac_get_roles(){
        global $wp_roles;
        return array_merge( array( 'all' => __( 'All', 'yith-woocommerce-recover-abandoned-cart' ) ), $wp_roles->get_names() );
    }
}


if ( !function_exists( 'ywrac_get_cutoff' ) ) {
    /**
     * calculate the cutoff time
     *
     * @return int
     * @since 1.0.0
     */

    function ywrac_get_cutoff( $qty, $type ){
        $cutoff = 0;
       if( $type == 'hours' ){
           $cutoff = 60*60*$qty;
       }elseif( $type == 'days' ){
           $cutoff = 24*60*60*$qty;
       }elseif( $type == 'minutes' ){
           $cutoff = 60*$qty;
       }

        return $cutoff;
    }
}

if( !function_exists( 'ywrac_is_customer_unsubscribed' ) ) {
    /**
     * Check if a customer is currently unsubscribed from email
     *
     * @param int | string
     * @since 1.0.4
     * @return bool
     * @author Francesco Licandro
     */
    function ywrac_is_customer_unsubscribed( $user = null ) {

        $blacklist = get_option( 'ywrac_mail_blacklist', '' );
        $blacklist = maybe_unserialize( $blacklist );
        ! $blacklist && $blacklist = array();

        if( is_null( $user ) ){
            $customer_id = get_current_user_id();
        }elseif( is_email( $user ) ) {
            $customer = get_user_by( 'email', $user );
            if( $customer ) {
                $customer_id = $customer->ID;
            }
            else {
                return in_array( $user, $blacklist );
            }
        }
        else {
            $customer_id = intval( $user );
        }

        return get_user_meta( $customer_id, '_ywrac_is_unsubscribed', true ) == '1';
    }
}

if ( ! function_exists( 'ywrac_check_valid_admin_page' ) ) {
	/**
	 * Return if the current pagenow is valid for a post_type, useful if you want add metabox, scripts inside the editor of a particular post type
	 *
	 * @param $post_type_name
	 *
	 * @return bool
	 * @since 1.1.0
	 * @author Emanuela Castorina
	 */
	function ywrac_check_valid_admin_page( $post_type_name ) {
		global $pagenow;
		$post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : ( isset( $_REQUEST['post_ID'] ) ? $_REQUEST['post_ID'] : 0 );
		$post = get_post( $post );

		if ( ( $post && $post->post_type == $post_type_name ) || ( $pagenow == 'post-new.php' && isset( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] == $post_type_name ) ) {
			return true;
		}

		return false;
	}
}


function ywrac_get_product_price( $_product ){

	$tax_display_cart = get_option( 'woocommerce_tax_display_cart' );

	if ( $tax_display_cart == 'excl' ) {
		$product_price = yit_get_price_excluding_tax( $_product );
	} else {
		$product_price = yit_get_price_including_tax( $_product );
	}

	return apply_filters( 'woocommerce_cart_product_price', wc_price( $product_price ), $_product );

}

function ywrac_get_product_subtotal( $_product, $quantity ) {

	$price            = $_product->get_price();
	$taxable          = $_product->is_taxable();
	$tax_display_cart = get_option( 'woocommerce_tax_display_cart' );

	$price_include_tax = wc_prices_include_tax();
	// Taxable
	if ( $taxable ) {

		if ( $tax_display_cart == 'excl' ) {

			$row_price        = yit_get_price_excluding_tax( $_product, $quantity );
			$product_subtotal = wc_price( $row_price );

			if ( $price_include_tax ) {
				$product_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
			}

		} else {

			$row_price        =  yit_get_price_including_tax( $_product, $quantity );

			$product_subtotal = wc_price( $row_price );

			if ( ! $price_include_tax ) {
				$product_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
			}

		}

		// Non-taxable
	} else {

		$row_price        = $price * $quantity;
		// var_dump($row_price);
		$product_subtotal = wc_price( $row_price );

	}

	return $product_subtotal;
}
