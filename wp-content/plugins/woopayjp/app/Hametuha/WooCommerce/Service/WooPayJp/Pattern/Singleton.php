<?php

namespace Hametuha\WooCommerce\Service\WooPayJp\Pattern;

use Payjp\Payjp;

/**
 * Singleton override
 *
 * @package woopayjp
 * @property string $public_key
 */
class Singleton extends \Hametuha\HametWoo\Pattern\Singleton {

	/**
	 * Get option
	 *
	 * @return array
	 */
	protected function option() {
		static $option = [];
		if ( ! $option ) {
			$option = get_option( 'woocommerce_payjp_settings', [] );
			if ( 'yes' !== $option['sandbox'] ) {
				// This is production.
				if ( $license = apply_filters( 'woopayjp_is_sekisyo_passed', true ) ) {
					$option['private_key'] = $option['production_private_key'];
					$option['public_key'] = $option['production_public_key'];
				}
			}
		}
		return $option;
	}


	/**
	 * Set API key
	 */
	protected function set_api_key() {
		$option = $this->option();
		Payjp::setApiKey( $option['private_key'] );
	}

	/**
	 * Enqueue Payjp scripts
	 */
	protected function enqueue_payjp_js() {
		static $did_enqueue = false;
		if ( ! $did_enqueue ) {
			$did_enqueue = true;
			wp_enqueue_script( 'payjp-js' );
			$pub_key = esc_js( $this->public_key );
			$js = <<<JS
Payjp.setPublicKey("{$pub_key}");
JS;
			wp_add_inline_script( 'payjp-js', $js );
		}
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'public_key':
				$option = $this->option();
				return isset( $option['public_key'] ) ? $option['public_key'] : '';
				break;
			default:
				return parent::__get( $name );
				break;
		}
	}
}
