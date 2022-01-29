<?php

namespace Hametuha\WooCommerce\Service\WooPayJp\Tabs;


use Hametuha\WooCommerce\Service\WooPayJp\CardManager;
use Hametuha\HametWoo\Pattern\Tab;

/**
 * Credit card list.
 *
 * @package Hametuha\WooCommerce\Service\WebPay\Tabs
 */
class CardList extends Tab {

	/**
	 * @var string
	 */
	protected $endpoint = 'credit-cards';

	/**
	 * Get page title
	 *
	 * @return string
	 */
	protected function get_title() {
		return __( 'Registered Cards', 'woopayjp' );
	}

	public function new_menu_items( array $items ) {
		$new_items = [];
		foreach ( $items as $key => $label ) {
			if ( 'customer-logout' == $key ) {
				$new_items[ $this->endpoint ] = $this->get_title();
			}
			$new_items[ $key ] = $label;
		}
		return $new_items;
	}

	/**
	 * Call credit card manager
	 *
	 * @todo Merge 2 classes.
	 */
	public function endpoint_content() {
		CardManager::get_instance()->credit_card_list();
	}
}
