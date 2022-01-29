<?php
namespace Hametuha\WooCommerce\Service\WooPayJp\Validator;


use Hametuha\HametWoo\Pattern\Validator;
use Hametuha\HametWoo\Utility\Compatibility;

/**
 * Validate requirements
 *
 * @package woopyajp
 */
class Requirement extends Validator {

	/**
	 * Check WooCommerce version
	 *
	 * @return bool|\WP_Error
	 */
	public function test_woo_version() {
		if ( Compatibility::satisfies( '3.0' ) ) {
			return true;
		} else {
			return new \WP_Error( 'too_low_version', __( 'Pay.JP requires WooCommerce Version 3.0 and over!', 'woopayjp' ) );
		}
	}

	/**
	 * Check currency
	 *
	 * @return bool|\WP_Error
	 */
	public function test_currency() {
		if ( Compatibility::check_currency( 'JPY' ) ) {
			return true;
		} else {
			return new \WP_Error( 'invalid_currency', __( 'Pay.JP supports only JPY.', 'woopayjp' ) );
		}
	}
}
