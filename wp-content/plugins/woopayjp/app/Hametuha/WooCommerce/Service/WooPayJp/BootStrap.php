<?php
namespace Hametuha\WooCommerce\Service\WooPayJp;

use Hametuha\HametWoo\Pattern\Singleton;
use Hametuha\WooCommerce\Service\WooPayJp\GateWay\CreditCard;
use Hametuha\WooCommerce\Service\WooPayJp\Tabs\CardList;
use Hametuha\WooCommerce\Service\WooPayJp\Validator\Requirement;

/**
 * Plugin's bootStrap
 *
 * @package woopayjp
 */
class BootStrap extends Singleton {

	/**
	 * Constructor
	 *
	 * @param array $setting Settings array.
	 */
	protected function __construct( array $setting = [] ) {
		$errors = Requirement::validate();
		if ( is_wp_error( $errors ) ) {
			add_action( 'admin_notices', function () use ( $errors ) {
				printf( '<div class="error"><p>[Pay.JP Error]%s</p></div>', implode( '<br />', array_map( function( $message ) {
					return wp_kses( $message, [
						'a' => [ 'href', 'target' ],
					    'strong' => [],
					] );
				}, $errors->get_error_messages() ) ) );
			} );
		} else {
			// Register JS
			add_action( 'init', [ $this, 'register_assets' ] );
			// Add gate way.
			add_filter( 'woocommerce_payment_gateways', function ( $methods ) {
				$methods[] = 'Hametuha\\WooCommerce\\Service\\WooPayJp\\GateWay\\CreditCard';
				return $methods;
			} );
			// Add Credit Card Manager.
			$option = get_option( 'woocommerce_payjp_settings', [] );
			$should_show_card_list = isset( $option['enabled'] ) && 'yes' == $option['enabled'];
			/**
			 * woopayjp_should_show_card_list
			 *
			 * Determine if card list is available.
			 *
			 * @param bool $should_show_card_list Default is whether plugin is enabled.
			 */
			$should_show_card_list = apply_filters( 'woopayjp_should_show_card_list', $should_show_card_list );
			if ( $should_show_card_list ) {
				CardManager::get_instance();
				// Add Tab content.
				CardList::get_instance();
			}

			// Register hooks for capture stuff
			$this->capture_order();

			// Add cron task
			add_action( 'init', [ $this, 'register_cron' ] );

			// Register scripts
			add_action( 'init', function() {
				wp_register_script( 'payjp-js', 'https://js.pay.jp/', [], null, true );
			} );
		}
	}

	/**
	 * Register hooks
	 */
	public function capture_order() {
		// Capture order
		add_action( 'woocommerce_order_status_processing_to_completed', function( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( 'payjp' == $order->get_payment_method() ) {
				/** @var CreditCard $gateway */
				$gateway = wc_get_payment_gateway_by_order( $order );
				if ( $gateway ) {
					$result = $gateway->capture_order( $order );
					if ( is_wp_error( $result ) ) {
						$order->add_order_note( $result->get_error_message() );
					} elseif ( $result ) {
						$order->add_order_note( __( 'Payment for Pay.JP has been captured.', 'woopayjp' ) );
					}
				}
			}
		} );

		// Add row column
		add_action( 'manage_shop_order_posts_custom_column', function( $column, $item ) {
			switch ( $column ) {
				case 'order_status':
					$order = wc_get_order( $item );
					if ( 'payjp' !== $order->get_payment_method() ) {
						break;
					}
					/** @var CreditCard $gateway */
					$gateway = wc_get_payment_gateway_by_order( $order );
					if ( $gateway->should_expire( $order ) ) {
						printf( '<mark class="%s tips" style="margin-top: 5px;" data-tip="%s">%s</mark>', esc_attr( sanitize_html_class( 'failed' ) ), esc_attr( wc_get_order_status_name( 'failed' ) ), esc_html__( 'Expired', 'woopayjp' ) );
					}
					break;
				default:
					// Do nothing.
					break;
			}
		}, 11, 2 );

		// Add meta box notice
		add_action( 'woocommerce_admin_order_data_after_order_details', function( \WC_Order $order ) {
			if ( 'payjp' !== $order->get_payment_method() ) {
				return;
			}
			/** @var CreditCard $gateway */
			$gateway = wc_get_payment_gateway_by_order( $order );
			if ( ! $gateway->should_expire( $order ) ) {
				return;
			}
			?>
			<p class="form-field form-field-wide wc-customer-user">
				<label><?php esc_html_e( 'Payment Limit:', 'woopayjp' ) ?></label>
				<strong style="color: red">
					<?php printf(
						esc_html__( 'This order\'s payment should be captured until %s!', 'woopayjp' ),
						mysql2date( get_option( 'date_format' ), get_post_meta( $order->get_id(), '_woocommerce_payjp_should_expires', true ) )
					) ?>
				</strong>
			</p>
			<?php
		} );
	}
	
	/**
	 * Register scripts
	 */
	public function register_assets() {
		wp_register_script( 'payjp', 'https://js.pay.jp/', '', null, true );
		$js = <<<JS
if(!window.PayjpVars){
  // error.
  window.console && console.error('[Error]: PayjpVars is not defined!');
} else {
  // Set public key
  Payjp.setPublicKey(PayjpVars.pubkey);
}
JS;
		wp_add_inline_script( 'payjp', $js );
	}

	/**
	 * Register cron
	 */
	public function register_cron() {
		$hook = 'woopayjp_check_expiry';
		if ( ! wp_next_scheduled( $hook ) ) {
			/**
			 * woopayjp_expiry_check_recurrence
			 *
			 * How often expiry check works.
			 *
			 * @param string $schedule WP Cron's schedule name. Default 'hourly'.
			 */
			$recurrence = apply_filters( 'woopayjp_expiry_check_recurrence', 'hourly' );
			wp_schedule_event( current_time( 'timestamp', true ), $recurrence, $hook );
		}
		// Do expiry.
		add_action( $hook, function() {
			$query = new \WP_Query( [
				'post_type' => 'shop_order',
				'post_status' => 'wc-processing',
				'meta_query' => [
					[
						'key' => '_payment_method',
						'value' => 'payjp',
					],
					[
						'key' => '_woocommerce_payjp_should_expires',
						'value' => current_time( 'mysql' ),
						'compare' => '<',
						'type' => 'DATETIME',
					],
				],
			] );
			while ( $query->have_posts() ) {
				$query->the_post();
				$order = wc_get_order( get_post() );
				$order->update_status( 'failed' );
				$order->add_order_note( __( 'This order is failed because of payment limit expiration.', 'woopayjp' ) );
			}
		} );
	}
}
