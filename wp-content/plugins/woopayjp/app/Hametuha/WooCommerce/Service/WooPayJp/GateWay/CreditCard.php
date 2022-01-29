<?php
namespace Hametuha\WooCommerce\Service\WooPayJp\GateWay;


use Hametuha\HametWoo\Utility\Compatibility;
use Hametuha\HametWoo\Utility\Tools;
use Hametuha\WooCommerce\Service\WooPayJp\Member;
use Payjp\Charge;
use Payjp\Payjp;

/**
 * Pay.JP PAYMENT GATEWAY
 *
 * @package Hametuha\WooCommerce\Service\Webpay
 * @property-read Member $member
 * @property bool $should_capture
 * @property int  $capture_expires
 */
class CreditCard extends \WC_Payment_Gateway_CC {

	public $sandbox = 'yes';

	public $private_key = '';

	public $public_key = '';

	public $hidden = 'no';
	
	/**
	 * @var bool True if apple pay is enabled.
	 */
	public $use_apple_pay = false;

	use Tools {
		Tools::__get as traitGet;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id = 'payjp';
		$this->has_fields = true;
		$this->method_title = 'Pay.JP';
		$this->method_description = sprintf(
				__( 'Payment Gateway by Pay.JP. You have to create account at <a href="%s">Pay JP</a>.', 'woopayjp' ),
				add_query_arg( [
					'utm_source'   => 'hametuah',
					'utm_medium'   => 'wpadmin',
					'utm_campaign' => 'woopayjp',
				], 'https://pay.jp' )
			);
		// Get setting values.
		$this->title = $this->get_option( 'title', __( 'Credit Card', 'woopayjp' ) );
		$this->description = $this->get_option( 'description', __( 'Pay with credit card via Pay.JP.', 'woopayjp' ) );
		$this->enabled = $this->get_option( 'enabled', 'no' );
		// If license is not available, always sandbox.
		$production = ( 'yes' !== $this->get_option( 'sandbox', 'yes' ) );
		$valid = apply_filters( 'woopayjp_is_sekisyo_passed', true );
		$this->sandbox = ( $valid && $production ) ? 'no' : 'yes';
		$this->hidden = $this->get_option( 'hidden', 'no' );
		$this->private_key = 'yes' == $this->sandbox ? $this->get_option( 'private_key', '' ) : $this->get_option( 'production_private_key', '' );
		$this->public_key  = 'yes' == $this->sandbox ? $this->get_option( 'public_key', '' ) : $this->get_option( 'production_public_key', '' );
		$this->use_apple_pay = 'yes' === $this->get_option( 'use_apple_pay', 'no' );
		// Set publick key to pay.js
		wp_localize_script( 'payjp', 'PayjpVars', [
			'pubkey'        => $this->public_key,
			'applePayFails' => __( 'Failed to complete Apple Pay session. Please try again later or try another payment.', 'woopayjp' ),
			'cardReady'     => __( 'Authorized with Apple Pay', 'woopayjp' ),
			'unacceptable'  => __( 'You cannot use this card brand via Apple Pay at the moment. Please use other brand or enter card number.', 'woopayjp' ),
		] );
		// Form fields.
		$this->init_form_fields();
		// Load the setting.
		$this->init_settings();
		// Set up supports.
		$this->supports = [
			'products',
			'default_credit_card_form',
			'refunds',
		];
		$should_show_card_list = apply_filters( 'woopayjp_should_show_card_list', true );
		if ( $should_show_card_list ) {
			$this->supports = array_merge( $this->supports, [
				'subscriptions',
				'subscription_cancellation',
				'subscription_suspension',
				'subscription_reactivation',
			] );
		}
		// Add hooks for admin panel.
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		// Initialize credentials.
		if ( 'yes' === $this->enabled ) {
			if ( $this->public_key && $this->private_key ) {
				// Add credit card hook.
				if ( 'no' == $this->hidden ) {
					add_filter( 'woocommerce_credit_card_form_fields', [ $this, 'credit_card_form_fields' ], 10, 2 );
					add_action( 'woocommerce_credit_card_form_start', [ $this, 'credit_card_form_start' ] );
				}
				// Add hook for Recurring payment.
				add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ $this, 'process_recurring_payment', ], 10, 2 );
				// Load assets for checkout page.
				add_action( 'wp_enqueue_scripts', function () {
					if ( is_checkout() ) {
						wp_enqueue_style( 'woopayjp-checkout', woopayjp_asset( '/css/checkout.css' ), [], woopayjp_version() );
						wp_enqueue_script( 'woopayjp-checkout', woopayjp_asset( '/js/checkout.js' ), [ 'jquery', 'payjp' ], woopayjp_version(), true );
					}
				} );
			} else {
				add_action( 'admin_notices', function () {
					printf( '<div class="error"><p><strong>WebPay: </strong>%s</p></div>', esc_html__( 'Public key and private key are required.', 'woopayjp' ) );
				} );
			}
		}
	}

	/**
	 * Initialize form fields
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'enabled' => [
				'title' => __( 'Enable/Disable', 'woocommerce' ),
				'label' => __( 'Enable Pay.JP Payment Gateway.', 'woopayjp' ),
				'type' => 'checkbox',
				'description' => '',
				'default' => 'no',
			],
			'title' => [
				'title' => __( 'Title', 'woocommerce' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
				'default' => __( 'Credit card', 'woocommerce' ),
				'desc_tip' => true,
			],
			'description' => [
				'title' => __( 'Description', 'woocommerce' ),
				'type' => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
				'default' => __( 'Pay with your credit card via Pay.JP.', 'woopayjp' ),
				'desc_tip' => true,
			],
			'sandbox' => [
				'title' => __( 'Sandbox', 'woocommerce' ),
				'label' => __( 'Enable Sandbox Mode', 'woopayjp' ),
				'type' => 'checkbox',
				'description' => sprintf(
					'%s %s',
					__( 'On sandbox mode, real payments will not be taken. Enable this on test environment. You can find card number for test <a href="https://pay.jp/docs/testcard">here</a>.', 'woopayjp' ),
					sprintf(
						__( 'If license is invalid, sandbox mode is always on. You are now <strong>%s</strong>.', 'woopayjp' ),
						('yes' == $this->sandbox ? __( 'Sandbox Mode', 'woopayjp' ) : __( 'Live Mode', 'woopayjp' ) )
					)
				),
				'default' => 'yes',
			],
			'should_capture' => [
				'title'   => __( 'Capture Payment', 'woopayjp' ),
				'label'   => __( 'Capture payment after shipment completed.', 'woopayjp' ),
				'type'    => 'checkbox',
				'description' => sprintf( __( 'If checked, order with shipping will be charged and payment will be captured after shipment has been completed. For more details see <a href="%s" target="_blank">Pay.JP Doc</a>.', 'woopayjp' ), 'https://pay.jp/docs/authcapture' ),
				'default' => 'no',
			],
			'capture_expiry' => [
				'title'   => __( 'Capture Expiry Days', 'woopayjp' ),
				'type'    => 'number',
				'description' => __( 'Capture will be expired in specified days. Order will be canceled.', 'woopayjp' ),
				'default' => 7,
				'desc_tip' => true,
			],
			'hidden' => [
				'title' => __( 'Visibility', 'woopayjp' ),
				'label' => __( 'Hidden on checkout form', 'woopayjp' ),
				'type' => 'checkbox',
				'description' => __( 'If checked, your customer cannot choose this payment method. Useful on changing gateway on production environment.', 'woopayjp' ),
				'default' => 'no',
			],
			'private_key' => [
				'title' => __( 'Test Private API Key', 'woopayjp' ),
				'type' => 'text',
				'description' => __( 'You can get it at Pay.JP\'s dashboard.', 'woopayjp' ),
				'default' => '',
				'desc_tip' => true,
			],
			'public_key' => [
				'title' => __( 'Test Public API Key', 'woopayjp' ),
				'type' => 'text',
				'description' => __( 'You can get it at Pay.JP\'s dashboard.', 'woopayjp' ),
				'default' => '',
				'desc_tip' => true,
			],
			'production_private_key' => [
				'title' => __( 'Production Private API Key', 'woopayjp' ),
				'type' => 'text',
				'description' => __( 'You can get it at Pay.JP\'s dashboard.', 'woopayjp' ),
				'default' => '',
				'desc_tip' => true,
			],
			'production_public_key' => [
				'title' => __( 'Production Public API Key', 'woopayjp' ),
				'type' => 'text',
				'description' => __( 'You can get it at Pay.JP\'s dashboard.', 'woopayjp' ),
				'default' => '',
				'desc_tip' => true,
			],
			'use_apple_pay' => [
				'title' => __( 'Apple Pay', 'woopayjp' ),
				'label' => __( 'Enable Apple Pay', 'woopayjp' ),
				'type' => 'checkbox',
				'description' => sprintf(
						__( 'To use Apple Pay, you have to <a href="%1$s" target="_blank">pass an inspection</a> on your production environment. Besides that, you also have to <a href="%2$s">register your domain</a>: <code>%3$s</code>', 'woopayjp' ),
						'https://pay.jp/docs/apple-pay',
						'https://pay.jp/d/settings/applepay',
						preg_replace( '#^https?://#u', '', untrailingslashit( home_url() ) )
					),
				'default' => 'no',
			],
		];
	}

	/**
	 * Show description for sandbox
	 *
	 * @param string $id This ID.
	 */
	public function credit_card_form_start( $id ) {
		if ( $id === $this->id ) {
			if ( 'yes' === $this->sandbox ) {
			    $message = sprintf( '<strong>%s</strong>', esc_html( __( 'This is sandbox environment.', 'woopayjp' ) ) );
				/**
				 * woopayjp_sandbox_message
                 *
                 * @package woopayjp
                 * @since 1.0.0
                 * @param string $message Message displayed on sandbox mode.
                 * @return string
				 */
			    $message = apply_filters( 'woopayjp_sandbox_message', $message );
			    printf( '<p class="description description--woopayjp">%s</p>', $message );
			}
		}
	}

	/**
	 * Add credit card fields
	 *
	 * @param array $args Fields's argument.
	 * @param string $id ID of payment gateway.
	 *
	 * @return array
	 */
	public function credit_card_form_fields( $args, $id ) {
		if ( $id !== $this->id ) {
			return $args;
		}
		// Hijack default CC field.
		$fields = '<div id="woopayjp-cc-info">';
		
		ob_start();
		?>
		<p class="form-row form-row-side form-row-soopayjp">
			<label for="payjp-card-name"><?php esc_html_e( 'Card Info', 'woopayjp' ) ?><span class="required">*</span></label>
			<script
					type="text/javascript"
					src="https://checkout.pay.jp/"
					class="payjp-button"
					data-key="<?= esc_attr( $this->public_key ) ?>"
					data-text="<?php esc_html_e( 'Enter Card Information', 'woopayjp' ) ?>"
					data-submit-text="<?php esc_html_e( 'Go to Check Out', 'woopayjp' ) ?>"
					data-partial="true">
			</script>
		</p>

		<?php
		$fields .= ob_get_contents();
		ob_end_clean();
		
		// Apple Pay
		if ( $this->use_apple_pay ) {
			$fields .= sprintf(
				'<div id="apple-pay-button" class="apple-pay-button" data-order-label="%s" data-order-total="%d"></div>',
				esc_attr( get_bloginfo( 'name' ) ),
				preg_replace( '#\D#u', '', WC()->cart->get_total() )
			);
		}
		
		/**
		 * woopayjp_allow_card_register
		 *
		 * @param bool $allow   Default is user is logged in.
		 * @param CreditCard $gateway Payment Gateway Class.
		 */
		$pay_with_registered_card = apply_filters( 'woopayjp_allow_card_register', is_user_logged_in(), $this );

		// Check if card is set.
		if ( $pay_with_registered_card ) {
			$checked = <<<HTML
				<p class="form-row form-row-wide form-row--woopayjp">
					<label>%1\$s <small class="required">%2\$s</small></label>
					<label>
						<input type="checkbox" name="{$this->id}-card-save" id="{$this->id}-card-save" checked value="yes" />
						%3\$s
						<small>%4\$s</small>
					</label>
				</p>
HTML;
			$fields .= sprintf( $checked,
				__( 'Register Card', 'woopayjp' ),
				__( '* for Subscription', 'woopayjp' ),
				__( 'Save this card for next checkout.', 'woopayjp' ),
				__( 'NOTICE: Card number will never be saved.', 'woopayjp' )
			);
		}
		$fields .= '</div>';
		$args = [ 'woopayjp-card' => $fields ];
		if ( $pay_with_registered_card ) {
			// Select card.
			$cards = $this->member->get_cards( get_current_user_id() );
			if ( $cards ) {
				$out = '
					<p class="form-row form-row-wide form-row--woopayjp">
						<label>' . __( 'Pay with registered Card', 'woopayjp' ) . '</label>
						<select name="payjp-card-select" id="payjp-card-select">
							<option value="">' . __( 'Select card...', 'woopayjp' ) . '</option>';
				foreach ( $cards as $card ) {
					$out .= '
					<option value="' . esc_attr( $card->id ) . '">
						 ' . esc_html( $card->brand ) . ' **** ' . $card->last4 . '
						  ( ' . sprintf( '%02d', $card->exp_month ) . ' / ' . esc_html( $card->exp_year ) . ' )
					</option>';
				}
				$out .= '
						</select>
					</p>';
				$args['woopayjp-select'] = $out;
			}
		}
		return $args;
	}


	/**
	 * Show process order
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$transaction_id = 'woopayjp-' . $order_id . '-' . time();
		try {
			if ( Compatibility::subscription_available() ) {
				$has_recurring = wcs_order_contains_subscription( $order );
			} else {
				$has_recurring = false;
			}
			$cc_save = ( 'yes' === $this->input->post( 'payjp-card-save' ) || $has_recurring );
			$cc_registered = trim( $this->input->post( 'payjp-card-select' ) );
			// Build params for WebPay.
			Payjp::setApiKey( $this->private_key );
			$charge = [
				'amount' => (int) $order->get_total(),
				'currency' => 'jpy',
				'description' => $transaction_id,
			];
			// If credit card is specified, check it's validity.
			if ( $cc_registered ) {
				//
				// Credit card is specified.
				//
				if ( ! $this->member->can_use( $cc_registered, get_current_user_id() ) ) {
					throw new \Exception( __( 'Specified card is currently not available.', 'woopayjp' ) );
				}
				$user = $this->member->info( get_current_user_id() );
				$user->default_card = $cc_registered;
				$user->save();
				$charge['customer'] = $user->id;
			} else {
				// This is credit card transaction.
				$token = $this->input->post( 'payjp-token' );
				// Save credit card.
				if ( $cc_save ) {
					// Try save credit card.
					if ( ! is_user_logged_in() ) {
						throw new \Exception( sprintf( __( 'To save credit card, you have to <a href="%s">log in</a>.', 'woopayjp' ), wp_login_url( wc_get_cart_url() ) ) );
					}
					// O.K. Let's save cc.
					$cc_registered = $this->member->save_card( get_current_user_id(), $token )->id;
					$user = $this->member->info( get_current_user_id() );
					$user->default_card = $cc_registered;
					$charge['customer'] = $user->id;
				} else {
					// This is normal transaction.
					$charge['card'] = $token;
				}
			}
			if ( $has_recurring && ! $cc_registered ) {
				throw new \Exception( __( 'You should register credit card for subscription', 'woopayjp' ) );
			}
			// Do transaction if total amount more than 0.
			if ( $charge['amount'] > 0 ) {
				// Check if this should be capture
				if ( $order->needs_processing() && $this->should_capture && $this->capture_expires ) {
					$expires_at = current_time( 'timestamp' ) + ( 60 * 60 * 24 * $this->capture_expires );
					add_post_meta( $order_id, '_woocommerce_payjp_should_expires', date_i18n( 'Y-m-d H:i:s', $expires_at ) );
					$charge['capture'] = false;
					$charge['expiry_days'] = $this->capture_expires;
				}
				$response = Charge::create( $charge );
				add_post_meta( $order_id, '_woocommerce_payjp_transaction', $response->id );

			}
			// Save credit card number.
			if ( $cc_registered ) {
				add_post_meta( $order_id, '_woocommerce_payjp_card_id', $cc_registered );
			}
			$order->add_order_note( sprintf( __( 'Transaction %s succeeded.', 'woopayjp' ), $transaction_id ) );
		} catch ( \Exception $e ) {
			wc_add_notice( 'Error: ' . $e->getMessage(), 'error' );
			return [];
		}
		if ( $order->needs_processing() ) {
			$order->payment_complete( $transaction_id );
		} else {
			$order->update_status( 'completed' );
		}
		WC()->cart->empty_cart();

		return [
			'result' => 'success',
			'redirect' => $this->get_return_url( $order ),
		];
	}

	/**
	 * Let's try recurring
	 *
	 * @param float $amount_to_charge Amount to charge.
	 * @param \WC_Order|int $order Order object.
	 */
	public function process_recurring_payment( $amount_to_charge, $order ) {
		try {
			// Get user ID.
			$order = wc_get_order( $order );
			if ( ! $user_id = $order->get_user_id() ) {
				throw new \Exception( __( 'User of this subscription not found.', 'woopayjp' ) );
			}
			// Get saved card.
			$subscription_id = get_post_meta( $order->get_id(), '_subscription_renewal', true );
			$original_subscription = get_post( $subscription_id );
			if ( 'shop_subscription' != $original_subscription->post_type || ! $original_subscription->post_parent ) {
				throw new \Exception( __( 'Parent subscription not found.', 'woopayjp' ), 404 );
			}
			$card_seq = get_post_meta( $original_subscription->post_parent, '_woocommerce_payjp_card_id', true );
			if ( ! $card_seq ) {
				throw new \Exception( 'This subscription has no credit card information.' );
			}
			// Try Transaction.
			$transaction_id = 'woopayjp-' . $order->get_id() . '-' . time();
			// Do recursive payment.
			$user = $this->member->info( $order->get_user_id() );
			$user->default_card = $card_seq;
			$user->save();
			$result = Charge::create( [
				'customer' => $user->id,
				'amount' => (int) $amount_to_charge,
				'description' => $transaction_id,
				'currency' => 'jpy',
			] );
			add_post_meta( $order->get_id(), '_woocommerce_payjp_recurring_log', $result->id );
			add_post_meta( $order->get_id(), '_woocommerce_payjp_transaction', $result->id );
			$order->add_order_note( sprintf( __( 'Transaction succeed as ID: %s', 'woopayjp' ), $result->id ) );
			\WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );
			if ( $order->needs_processing()) {
				$order->payment_complete();
			} else {
				$order->update_status( 'completed' );
			}
		} catch ( \Exception $e ) {
			$order->update_status( 'failed' );
			$order->add_order_note( $e->getMessage() );
			\WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order );
		}
	}

	/**
	 * Process a refund if supported
	 *
	 * @param  int $order_id Order ID.
	 * @param  float $amount Amount.
	 * @param  string $reason Reason.
	 *
	 * @return  boolean True or false based on success, or a WP_Error object
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		try {
			$order = wc_get_order( $order_id );
			// Check if this order is refundable.
			$tran_id = get_post_meta( $order_id, '_woocommerce_payjp_transaction', true );
			if ( ! $tran_id ) {
				throw new \Exception( __( 'This transaction has no card information.', 'woopayjp' ) );
			}
			$refund_log = (int) get_post_meta( $order_id, '_woocommerce_payjp_refunded', true );
			$refund = [
				'refund_reason' => $reason,
			    'amount' => absint( $amount ),
			];
			PayJp::setApiKey( $this->private_key );
			$charge = Charge::retrieve( $tran_id );
			$response = $charge->refund( $refund );
			$refund_log++;
			update_post_meta( $order_id, '_woocommerce_payjp_refunded', $refund_log );
			return true;
		} catch ( \Exception $e ) {
			if ( isset( $order ) ) {
				$order->add_order_note( sprintf( __( 'Failed refund: %s', 'woopayjp' ), $e->getMessage() ) );
			}
			return false;
		}
	}

	/**
	 * Capture order if possible
	 *
	 * @param \WC_Order$order
	 * @return \WP_Error|bool
	 */
	public function capture_order( $order ) {
		$should_expires = get_post_meta( $order->get_id(), '_woocommerce_payjp_should_expires', true );
		if ( ! $should_expires ) {
			return false;
		}
		$now = new \DateTime(current_time( 'mysql' ) );
		$expires = new \DateTime( $should_expires );
		if ( $now > $expires ) {
			// This is expired order!
			return new \WP_Error( 'woopayjp_expired', __( 'This order is already expired.', 'woopayjp' ), [
				'order_id' => $order->get_id(),
			] );
		}
		try {
			Payjp::setApiKey( $this->private_key );
			$tran_id = get_post_meta( $order->get_id(), '_woocommerce_payjp_transaction', true );
			$charge = Charge::retrieve( $tran_id );
			$response = $charge->capture();
			if ( $response->captured ) {
				update_post_meta( $order->get_id(), '_woocommerce_payjp_captured', current_time( 'mysql' ) );
				return true;
			} else {
				return new \WP_Error( 'woopayjp_capture_failed', __( 'Failed to capture', 'woopayjp' ) );
			}
		} catch ( \Exception $e ) {
			return new \WP_Error( 'woopayjp_capture_failed', $e->getMessage(), [
				'order_id' => $order->get_id(),
				'code' => $e->getCode(),
			] );
		}
	}

	/**
	 * Detect if order should be expired.
	 *
	 * @param \WC_Order $order
	 *
	 * @return bool
	 */
	public function should_expire( $order ) {
		// Check status.
		$status = $order->get_status();
		if ( false === array_search( $status, [ 'completed', 'processing' ] ) ) {
			return false;
		}
		// Check limit.
		$limit = get_post_meta( $order->get_id(), '_woocommerce_payjp_should_expires', true );
		if ( ! $limit ) {
			return false;
		}
		// If captured, O.K.
		$captured = get_post_meta( $order->get_id(), '_woocommerce_payjp_captured', true );
		if ( ( 'completed' == $status ) && ( $captured ) ) {
			return false;
		}
		// If future, O.K.
		if ( new \DateTime( date_i18n('Y-m-d H:i:s') ) <  new \DateTime( $limit ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Getter.
	 *
	 * @param string $name Key.
	 *
	 * @return mixed.
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'member':
				return Member::get_instance();
				break;
			case 'should_capture':
				return 'yes' === $this->get_option( 'should_capture' );
				break;
			case 'capture_expires':
				$expires = $this->get_option( 'capture_expiry' );
				return is_numeric( $expires ) && ( 60 >= $expires ) && ( 0 < $expires ) ? (int) $expires : 0;
				break;
			default:
				return $this->traitGet( $name );
				break;
		}
	}
}
