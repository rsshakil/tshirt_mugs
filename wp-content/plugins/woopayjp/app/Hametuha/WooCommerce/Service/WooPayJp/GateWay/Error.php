<?php

namespace Hametuha\WooCommerce\Service\WooPayJp\GateWay;


use Hametuha\HametWoo\Pattern\Singleton;

/**
 * Error reference
 *
 * @since 1.0.0
 * @package woopayjp
 */
class Error extends Singleton {

	/**
	 * Message container
	 *
	 * @var array
	 */
	protected $messages = [];

	/**
	 * Error constructor.
	 */
	protected function __construct() {
		$this->messages = [
			'invalid_number' => __( 'Card number is invalid.', 'woopayjp' ),
			'invalid_cvc' => __( 'CSC is invalid.', 'woopayjp' ),
			'invalid_expiry_month' => __( 'Invalid expiry month.', 'woopayjp' ),
			'invalid_expiry_year' => __( 'Invalid expiry year', 'woopayjp' ),
			'expired_card' => __( 'This card is expired.', 'woopayjp' ),
			'card_declined' => __( 'This card is declined.', 'woopayjp' ),
			'processing_error' => __( 'Transaction failed processing.', 'woopayjp' ),
			'missing_card' => __( 'Customer has no card.', 'woopayjp' ),
			'invalid_id' => __( 'Invalid ID.', 'woopayjp' ),
			'no_api_key' => __( 'API key is not set.', 'woopayjp' ),
			'invalid_api_key' => __( 'Invalid API key.', 'woopayjp' ),
			'invalid_plan' => __( 'Invalid plan.', 'woopayjp' ),
			'invalid_expiry_days' => __( 'Invalid expiry days.', 'woopayjp' ),
			'unnecessary_expiry_days' => __( 'Expiry days are unnecessary.', 'woopayjp' ),
			'invalid_flexible_id' => __( 'Invalid ID specified.', 'woopayjp' ),
			'invalid_timestamp' => __( 'Invalid Unix timestamp.', 'woopayjp' ),
			'invalid_trial_end' => __( 'Invalid trial end.', 'woopayjp' ),
			'invalid_string_length' => __( 'Invalid string length.', 'woopayjp' ),
			'invalid_country' => __( 'Invalid country code.', 'woopayjp' ),
			'invalid_currency' => __( 'Invalid currency code.', 'woopayjp' ),
			'invalid_address_zip' => __( 'Invalid zip code.', 'woopayjp' ),
			'invalid_amount' => __( 'Invalid payment amount.', 'woopayjp' ),
			'invalid_plan_amount' => __( 'Invalid plan amount.', 'woopayjp' ),
			'invalid_card' => __( 'Invalid card.', 'woopayjp' ),
			'invalid_customer' => __( 'Invalid customer.', 'woopayjp' ),
			'invalid_boolean' => __( 'Invalid value.', 'woopayjp' ),
			'invalid_email' => __( 'Invalid mail address.', 'woopayjp' ),
			'no_allowed_param' => __( 'Parameter is not allowed.', 'woopayjp' ),
			'no_param' => __( 'No parameter is set.', 'woopayjp' ),
			'invalid_querystring' => __( 'Query string is invalid.', 'woopayjp' ),
			'missing_param' => __( 'Required parameter is not set.', 'woopayjp' ),
			'invalid_param_key' => __( 'Parameter key is invalid.', 'woopayjp' ),
			'no_payment_method' => __( 'Payment method is not specified.', 'woopayjp' ),
			'payment_method_duplicate' => __( 'Payment method is duplicated.', 'woopayjp' ),
			'payment_method_duplicate_including_customer' => __( 'Payment method or cusomer name is duplicated.', 'woopayjp' ),
			'failed_payment' => __( 'Specified payment is failed.', 'woopayjp' ),
			'invalid_refund_amount' => __( 'Invalid refund amount.', 'woopayjp' ),
			'already_refunded' => __( 'Already refunded.', 'woopayjp' ),
			'cannot_refund_by_amount' => __( 'Partial refund is allowed only once.', 'woopayjp' ),
			'invalid_amount_to_not_captured' => __( 'Not refundable because the transaction is not finished.', 'woopayjp' ),
			'refund_amount_gt_net' => __( 'Refund amount is greater than net amount.', 'woopayjp' ),
			'capture_amount_gt_net' => __( 'Captured amout is greater than net amount.', 'woopayjp' ),
			'invalid_refund_reason' => __( 'Invalid refund reason.', 'woopayjp' ),
			'already_captured' => __( 'Already captured.', 'woopayjp' ),
			'cant_capture_refunded_charge' => __( 'Failed to capture because it\'s already refunded.', 'woopayjp' ),
			'charge_expired' => __( 'This charge is expired.', 'woopayjp' ),
			'alerady_exist_id' => __( 'This ID already exists.', 'woopayjp' ),
			'token_already_used' => __( 'Token is already used.', 'woopayjp' ),
			'already_have_card' => __( 'Card is already registered with this customer.', 'woopayjp' ),
			'dont_has_this_card' => __( 'Customer doesn\'t have specified card.', 'woopayjp' ),
			'doesnt_have_card' => __( 'Customer has no card.', 'woopayjp' ),
			'invalid_interval' => __( 'Invalid subscription interval.', 'woopayjp' ),
			'invalid_trial_days' => __( 'Invalid trial days.', 'woopayjp' ),
			'invalid_billing_day' => __( 'Invalid billing day.', 'woopayjp' ),
			'exist_subscribers' => __( 'The plan cannot be deleted because it has customer.', 'woopayjp' ),
			'already_subscribed' => __( 'Customer is already subscribing.', 'woopayjp' ),
			'already_canceled' => __( 'Customer has already canceled.', 'woopayjp' ),
			'already_pasued' => __( 'Subscription is already paused.', 'woopayjp' ),
			'subscription_worked' => __( 'Subscription is already working.', 'woopayjp' ),
			'test_card_on_livemode' => __( 'Test card is used on live mode.', 'woopayjp' ),
			'not_activated_account' => __( 'Account is not activated.', 'woopayjp' ),
			'too_many_test_request' => __( 'Request exceeds test limit.', 'woopayjp' ),
			'invalid_access' => __( 'Invalid access.', 'woopayjp' ),
			'payjp_wrong' => __( 'An error occurs on PAY.JP.', 'woopayjp' ),
			'pg_wrong' => __( 'An error occurs on Payment Agency.', 'woopayjp' ),
			'not_found' => __( 'Endpoint not found.', 'woopayjp' ),
			'not_allowed_method' => __( 'Method is not allowed.', 'woopayjp' ),
		];
	}

	/**
	 * Convert exception's message
	 *
	 * @param \Exception $e
	 *
	 * @return string
	 */
	public static function translate( \Exception $e ) {
		$instance = self::get_instance();
		if ( isset( $instance->messages[ $e->getCode() ] ) ) {
			return $instance->messages[ $e->getCode() ];
		} else {
			return $e->getMessage();
		}
	}
}
