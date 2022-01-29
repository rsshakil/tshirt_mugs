<?php
namespace Hametuha\WooCommerce\Service\WooPayJp;

use Hametuha\WooCommerce\Service\WooPayJp\Pattern\Singleton;
use Payjp\Customer;
use Payjp\Payjp;
use Payjp\Token;

/**
 * Member function
 *
 * @package Hametuha\WooCommerce\Service\Webpay
 */
class Member extends Singleton {


	/**
	 * Get Pay.JP customer object.
	 *
	 * @param int $user_id
	 * @return Customer
	 */
	public function info( $user_id ) {
		$this->set_api_key();
		if ( ! ( $payjp_id = get_user_meta( $user_id, '_payjp_user_id', true ) ) ) {
			// Create customer and save.
			$user_data = get_userdata( $user_id );
			$response = Customer::create( [
				'email' => $user_data->user_email,
				'description' => $user_data->user_login,
				'metadata' => [ 'wp_id' => $user_id ],
			] );
			update_user_meta( $user_id, '_payjp_user_id', $response->id );
			$payjp_id = $response->id;
		}
		return Customer::retrieve( $payjp_id );
	}

	/**
	 * Get registered cards
	 *
	 * @param int $user_id WordPress' user id.
	 *
	 * @return array
	 */
	public function get_cards( $user_id ) {
		$cards = [];
		try {
			$user = $this->info( $user_id );
			$default = $user->default_card;
			$card_data = $this->info( $user_id )->cards;
			if ( $card_data->count ) {
				foreach ( $card_data->data as $card ) {
					$card->default = $card->id == $default;
					$cards[] = $card;
				}
			}
		} catch ( \Exception $e ) {
			// Do nothing.
		}
		return $cards;
	}

	/**
	 * Get specific card.
	 *
	 * @param int    $user_id WordPress' user ID.
	 * @param string $card_id Card id.
	 *
	 * @return \stdClass
	 */
	public function get_card( $user_id, $card_id ) {
		foreach ( $this->get_cards( $user_id ) as $card ) {
			if ( $card->id === $card_id ) {
				return $card;
			}
		}
		return null;
	}

	/**
	 * Detect if card is removable.
	 *
	 * @param int    $user_id WordPress's user_id.
	 * @param string $card_id Card ID.
	 *
	 * @return bool
	 */
	public function is_removable( $user_id, $card_id ) {
		static $orders = null;
		if ( ! function_exists( 'wcs_get_users_subscriptions' ) ) {
			return true;
		}
		if ( is_null( $orders ) ) {
			$orders = wcs_get_users_subscriptions( $user_id );
		}
		foreach ( $orders as $subscription ) {
			/** @var \WC_Subscription $subscription */
			$order    = wc_get_order( $subscription->get_parent_id() );
			if ( 'active' !== $subscription->get_status() || 'payjp' !== $order->get_payment_method() ){
				continue;
			}
			if ( get_post_meta( $order->get_id(), '_woocommerce_payjp_card_id', true ) === (string) $card_id ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Save card
	 *
	 * @param int    $user_id WordPress' user id.
	 * @param string $token  Credit card token.
	 *
	 * @throws \Exception Failed to save card data.
	 * @return object
	 */
	public function save_card( $user_id, $token ) {
		if ( ! ( $user = get_userdata( $user_id ) ) ) {
			throw new \Exception( __( 'User doesn\'t exist.', 'woopayjp' ) );
		}
		$user = $this->info( $user_id );
		$response = $user->cards->create( [
			'card' => $token,
		] );
		return $response;
	}

	/**
	 * Delete registered cards
	 *
	 * @param int    $user_id WordPress' user id.
	 * @param string $card_id Sequence number.
	 *
	 * @return bool
	 */
	public function delete_card( $user_id, $card_id ) {
		foreach ( $this->get_cards( $user_id ) as $umeta_index => $card ) {
			if ( $card->id === $card_id ) {
				// Delete from Pay.JP
				$user = $this->info( $user_id );
				$card = $user->cards->retrieve( $card->id );
				$card->delete();
				return true;
			}
		}
		return false;
	}

	/**
	 * Get credit card token
	 *
	 * @param array $card
	 * @return mixed|null
	 */
	public function get_card_token( array $card ) {
		$this->set_api_key();
		$response = Token::create( [
			'card' => $card,
		] );
		return $response->id;
	}

	/**
	 * Detect if user has access to card
	 *
	 * @param string $card_id Card ID.
	 * @param int    $user_id WordPress' user id.
	 *
	 * @return bool
	 */
	public function can_use( $card_id, $user_id ) {
		foreach ( $this->get_cards( $user_id ) as $card ) {
			if ( $card->id === $card_id ) {
				return true;
			}
		}
		return false;
	}

}
