<?php

namespace Hametuha\WooCommerce\Service\WooPayJp;
use Hametuha\WooCommerce\Service\WooPayJp\Pattern\Singleton;
use Hametuha\WooCommerce\Service\WooPayJp\GateWay\Error;
use Payjp\Card;
use Payjp\Customer;
use Payjp\Payjp;
use Payjp\Token;


/**
 * Credit Card Manager
 *
 * @package woopayjp
 * @property-read array $option
 * @property-read Member $member
 */
class CardManager extends Singleton {

	/**
	 * Constructor
	 *
	 * @param array $setting Not used.
	 */
	protected function __construct( array $setting = [] ) {
		$option = $this->option();
		if ( ! isset( $option['enabled'] ) || 'yes' !== $option['enabled'] ) {
			return;
		}

		// Register assets.
		add_action( 'wp_enqueue_scripts', function () {
			if ( is_account_page() ) {
				wp_enqueue_style( 'woopayjp-cc-helper', woopayjp_asset( '/css/my-account.css' ), [], woopayjp_version() );
				wp_enqueue_script( 'woopayjp-cc-helper', woopayjp_asset( '/js/card-manager.js' ), [
					'jquery-blockui',
				], woopayjp_version(), false);
				wp_localize_script( 'woopayjp-cc-helper', 'WooPayJpCC', [
					'endpoint'        => admin_url( 'admin-ajax.php' ),
					'action_delete'   => 'woopayjp_cc_delete',
					'action_add'      => 'woopayjp_cc_add',
					'nonce'           => wp_create_nonce( 'woopayjp_cc' ),
					'ajaxLoaderImage' => woopayjp_asset( '/img/ajax-loader@2x.gif' ),
				    'confirm'         => __( 'Are you sure to delete this card?', 'woopayjp' ),
				    'failureDelete'   => __( 'Failed to delete Credit card.', 'woopayjp' ),
				    'failureAdd'      => __( 'Failed to add Credit card.', 'woopayjp' ),
					'button_text'     => __( 'Register Card', 'woopayjp' ),
				] );
				add_action( 'wp_head', function () {
					echo $this->card_css();
				} );
			}
		} );

		// Add AJAX endpoint.
		add_action( 'wp_ajax_woopayjp_cc_delete', [ $this, 'ajax_delete' ] );
		add_action( 'wp_ajax_woopayjp_cc_add', [ $this, 'ajax_add' ] );

	}

	/**
	 * Render row
	 *
	 * @param \stdClass $card
	 *
	 * @return string
	 */
	protected function render_row( $card ) {
		ob_start();
		?>
		<tr>
			<td>
				**** **** ****
				<?php echo esc_html( sprintf( '%s (%s)', $card->last4, $card->brand )  )?>
			</td>
			<td><?php echo esc_html( sprintf( '%02d / %02d', $card->exp_month, $card->exp_year ) ) ?></td>
			<td class="my_woopayjp_cc__default--wrapper">
				<?php if ( $card->default ) : ?>
					<span class="my_woopayjp_cc__default--on">✔︎</span>
				<?php else : ?>
					<span class="my_woopayjp_cc__default--off">−</span>
				<?php endif; ?>
			</td>
			<td>
				<?php if ( $this->member->is_removable( get_current_user_id(), $card->id ) ) : ?>
					<a class="button delete-cc" href="#"
					   data-card-id="<?php echo esc_attr( $card->id ) ?>"><?php _e( 'Remove' ) ?></a>
				<?php else : ?>
					<span style="color: grey">
									&times; <?php esc_html_e( 'Undeletable', 'woopayjp' ) ?>
						<small> (<?php esc_html_e( 'Using', 'woopayjp' ) ?>)</small>
								</span>
				<?php endif; ?>
			</td>
		</tr>
		<?php
		$row = ob_get_contents();
		ob_end_clean();
		return $row;
	}

	/**
	 * Show credit card form
	 */
	public function credit_card_list() {
		$cards      = $this->member->get_cards( get_current_user_id() );
		$class_name = empty( $cards ) ? ' my_woopayjp_cc--empty' : '';
		?>
		<section class="my_woopayjp_cc">
			<table class="shop_table my_account_orders my_woopayjp_cc__table<?php echo esc_attr( $class_name ); ?>">
				<thead>
				<tr>
					<th class="subscription">
						<?php _e( 'Card Number', 'woocommerce' ) ?>
					</th>
					<th class="">
						<?php _e( 'Expiry (MM/YY)', 'woocommerce' ) ?>
					</th>
                    <th>
                        <?php _e( 'Default', 'woopayjp' ) ?>
                    </th>
					<th>
						<?php _e( 'Action' ) ?>
					</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $cards as $meta_id => $card ) : ?>
					<?= $this->render_row( $card ) ?>
				<?php endforeach; ?>
				</tbody>
			</table>
			<div class="my_woopayjp_cc__alert error">
				<p><?php esc_html_e( 'No credit card is registered.', 'woopayjp' ) ?></p>
			</div>
			<h3 class="my_woopayjp_cc_add__title"><?php esc_html_e( 'Register New Credit Card', 'woopayjp' ) ?></h3>


			<form id="woopayjp-add-card-form">
				<script
						type="text/javascript"
						src="https://checkout.pay.jp/"
						class="payjp-button"
						data-key="<?= esc_attr( $this->public_key ) ?>"
						data-lang="<?= 'ja' == get_locale() ? 'ja' : 'en' ?>"
						data-on-created="wooPayJpCardSubmit"
						data-on-failed="wooPayJpCardFailed"
						data-text="<?= esc_attr_e( 'Register Card', 'woopayjp' ) ?>"
						data-submit-text="<?= esc_attr_e( 'Register This Card', 'woopayjp' ); ?>"
						data-partial="true"
				>
				</script>
			</form>
		</section>
		<?php
	}

	/**
	 * Delete credit card via AJAX.
	 */
	public function ajax_delete() {
		try {
			$this->validate_ajax();
			// Check if user can remove this card.
			$card_id = $this->input->get( 'card_id' );
			if ( ! $card_id || ! $this->member->is_removable( get_current_user_id(), $card_id ) ) {
				throw new \Exception( __( 'This card is not deletable.', 'woopayjp' ), 500 );
			}
			$this->set_api_key();
			if ( $this->member->delete_card( get_current_user_id(), $card_id ) ) {
				wp_send_json( [
					'success' => true,
				] );
			} else {
				throw new \Exception( __( 'Failed to delete Credit card.', 'woopayjp' ) );
			}
		} catch ( \Exception $e ) {
			wp_send_json( [
				'success' => false,
				'code'    => $e->getCode(),
				'message' => Error::translate( $e ),
			] );
		}
	}

	/**
	 * Add credit card.
	 */
	public function ajax_add() {
		try {
			$this->validate_ajax();
			// O.K. Let's save cc.
			$this->set_api_key();
			$card_token = $this->input->post( 'payjp_token' );
			$card = $this->member->save_card( get_current_user_id(), $card_token );
			$user = $this->member->info( get_current_user_id() );
			$json = [ 'success' => true ];
			$json['html'] = $this->render_row( $card );
			wp_send_json( $json );
		} catch ( \Exception $e ) {
			wp_send_json( [
				'success' => false,
				'code'    => $e->getCode(),
				'message' => Error::translate( $e ),
			] );
		}
	}

	/**
	 * Validate ajax request
	 *
	 * @throws \Exception Check nonce and verify credentials.
	 */
	protected function validate_ajax() {
		// Check nonce.
		if ( ! $this->input->verify_nonce( 'woopayjp_cc' ) ) {
			throw new \Exception( __( 'Invalid access.', 'woopayjp' ), 403 );
		}
	}


	/**
	 * Getter
	 *
	 * @param string $name Key name.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'member':
				return Member::get_instance();
				break;
			default:
				return parent::__get( $name );
				break;
		}
	}

}
