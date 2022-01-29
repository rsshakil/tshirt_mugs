<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce For Japan

 * @package woocommerce-for-japan
 * @version     1.2.27
 * @category Address Setting for Japan
 * @author Artisan Workshop
 */

class AddressField4jp{

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		// Default address fields.
		add_filter( 'woocommerce_default_address_fields', array( $this, 'address_fields'));
		add_filter( 'woocommerce_get_country_locale', array( $this, 'country_locale_japan'));
		// MyPage Edit And Checkout fields.
		add_filter( 'woocommerce_billing_fields', array( $this, 'billing_address_fields'));
		add_filter( 'woocommerce_shipping_fields', array( $this, 'shipping_address_fields'), 20 );
		add_filter( 'woocommerce_formatted_address_replacements', array( $this, 'address_replacements'),20,2);
		add_filter( 'woocommerce_localisation_address_formats', array( $this, 'address_formats'),20);
		// My Account Display for address
		add_filter( 'woocommerce_my_account_my_address_formatted_address', array( $this, 'formatted_address'),20,3);//template/myaccount/my-address.php
		// Check Out Display for address
		add_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'wc4jp_billing_address'),10,2);
		add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'wc4jp_shipping_address'),20,2);
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'admin_order_data_after_shipping_address'), 10 );
		// include get_order function
		add_filter( 'woocommerce_get_order_address', array( $this, 'wc4jp_get_order_address'),20,3);//includes/abstract/abstract-wc-order.php
		// Admin CSS file 
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wc4jp_admin_style') ,20);
		// FrontEnd CSS file 
		add_action( 'wp_enqueue_scripts', array( $this, 'checkout_enqueue_style'), 10 );
		//
		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'auto_zip2address_billing'), 10 );
		add_action( 'woocommerce_after_checkout_shipping_form', array( $this, 'auto_zip2address_shipping'), 10 );
		add_action( 'woocommerce_after_edit_address_form_billing', array( $this, 'auto_zip2address_billing'), 10 );
		add_action( 'woocommerce_after_edit_address_form_shipping', array( $this, 'auto_zip2address_myaccount_shipping'), 10 );
		// Admin Edit Address
		add_filter( 'woocommerce_admin_billing_fields', array( $this, 'admin_billing_address_fields'));
		add_filter( 'woocommerce_admin_shipping_fields', array( $this, 'admin_shipping_address_fields'));
		add_filter( 'woocommerce_customer_meta_fields', array( $this, 'admin_customer_meta_fields'));
		// Change current_user->display_name to Japanese style when update user data
		add_filter( 'woocommerce_update_customer_args', array( $this, 'wc4jp_update_customer_args'), 10, 2 );
		// State() add Ken and Fu.
		add_filter( 'woocommerce_states', array($this, 'wc4jp_states'), 99 );
	}
	//Default address fields
	public function address_fields( $fields ) {
		$fields = array(
			'country' => array(
				'type'     => 'country',
				'label'    => __( 'Country', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				'priority'     => 10,
			),
			'last_name'    => array(
				'label'    => __( 'Last Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
				'priority'     => 20,
			),
			'first_name' => array(
				'label'    => __( 'First Name', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true,
				'priority'     => 25,
			),
			'yomigana_last_name' => array(
				'label'    => __( 'Last Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-first' ),
				'priority'     => 30,
			),
			'yomigana_first_name' => array(
				'label'    => __( 'First Name (Yomigana)', 'woocommerce-for-japan' ),
				'required' => true,
				'class'    => array( 'form-row-last' ),
				'clear'    => true,
				'priority'     => 35,
			),
			'company' => array(
				'label' => __( 'Company Name', 'woocommerce-for-japan' ),
				'class' => array( 'form-row-wide' ),
				'priority'     => 40,
			),
			'postcode' => array(
				'label'       => __( 'Postcode / Zip', 'woocommerce-for-japan' ),
				'placeholder' => _x( '123-4567', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-first', 'address-field' ),
				'validate'    => array( 'postcode' ),
				'priority' => 45,
				'clear'       => false
			),
			'state' => array(
				'type'        => 'state',
				'label'       => __( 'Prefecture', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-last', 'address-field' ),
				'validate'    => array( 'state' ),
				'clear'       => true,
				'priority' => 46,
			),
			'city' => array(
				'label'       => __( 'Town / City', 'woocommerce-for-japan' ),
				'placeholder' => __( 'Town / City', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' ),
				'priority' => 50,
			),
			'address_1' => array(
				'label'       => __( 'Address', 'woocommerce-for-japan' ),
				'placeholder' => _x( 'Street address', 'placeholder', 'woocommerce-for-japan' ),
				'required'    => true,
				'class'       => array( 'form-row-wide', 'address-field' ),
				'priority' => 60,
			),
			'address_2' => array(
				'placeholder' => _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'woocommerce-for-japan' ),
				'class'       => array( 'form-row-wide', 'address-field' ),
				'required'    => false,
				'priority' => 65,
			),
		);
		if(!get_option( 'wc4jp-yomigana'))unset($fields['yomigana_last_name'],$fields['yomigana_first_name']);

		return $fields;
	}
	//country_locale_japan
	public function country_locale_japan( $fields ){
		$fields['JP']['postcode']['priority'] = 45;
		$fields['JP']['state']['priority'] = 46;

		return $fields;
	}
	// Billing/Shipping Specific
	public function billing_address_fields( $fields ) {
		$address_fields = $fields;
		$address_fields['billing_state']['class'] = array( 'form-row-last', 'address-field' );
		$address_fields['billing_state']['clear'] = true;
		$address_fields['billing_phone']['label'] = __( 'Billing Phone', 'woocommerce-for-japan' );
		$address_fields['billing_phone']['clear'] = true;
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['billing_company']);
		return $address_fields;
	}
	public function shipping_address_fields( $fields ) {
		$address_fields = $fields;

		$address_fields['shipping_state']['class'] = array( 'form-row-last', 'address-field' );
		$address_fields['shipping_state']['clear'] = true;
		$address_fields['shipping_phone'] = array(
			'label' 		=> __( 'Shipping Phone', 'woocommerce-for-japan' ),
			'required' 		=> true,
			'class' 		=> array( 'form-row-wide' ),
			'clear'			=> true,
			'validate'		=> array( 'phone' ),
			'priority' => 100,
		);
		if(!get_option( 'wc4jp-company-name'))unset($address_fields['shipping_company']);
		return $address_fields;
	}

	public function address_replacements( $fields, $args ) {
		$fields['{name}'] = $args['last_name'] . ' ' . $args['first_name'];
		$fields['{name_upper}'] = strtoupper( $args['last_name'] . ' ' . $args['first_name'] );
		if(get_option( 'wc4jp-yomigana')){
			if(isset($args['yomigana_last_name']))$fields['{yomigana_last_name}'] = $args['yomigana_last_name'];
			if(isset($args['yomigana_first_name']))$fields['{yomigana_first_name}'] = $args['yomigana_first_name'];
		}
		if(version_compare( WC_VERSION, '3.1', '<=' )){
			$fields['{phone}'] = $args['phone'];
		}
		if(is_order_received_page()){
			$fields['{phone}'] = $args['phone'];
		}

		return $fields;
	}
	public function address_formats( $fields ) {
		//honorific suffix
		$honorific_suffix = '';
		if(get_option('wc4jp-honorific-suffix'))$honorific_suffix = '様';
		
		if(version_compare( WC_VERSION, '3.1', '>=' )){
			if(get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {country}";
			}
			if(!get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {country}";
			}
			if(!get_option( 'wc4jp-company-name') and !get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{last_name} {first_name}".$honorific_suffix."\n {country}";
			}
		}else{
			if(get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
			}
			if(!get_option( 'wc4jp-company-name') and get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{yomigana_last_name} {yomigana_first_name}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
			}
			if(!get_option( 'wc4jp-company-name') and !get_option( 'wc4jp-yomigana')){
				$fields['JP'] = "〒{postcode}\n{state}{city}{address_1}\n{address_2}\n{last_name} {first_name}".$honorific_suffix."\n {phone}\n {country}";
			}			
		}
		if(is_cart()){
			$fields['JP'] = "〒{postcode}{state}{city}{address_1}{address_2} {country}";
		}
		if(is_order_received_page()){
			$fields['JP'] = $fields['JP']."\n {phone}";
		}
		return $fields;
	}
	public function formatted_address( $fields, $customer_id, $name) {
		if(version_compare( WC_VERSION, '2.7', '>' )){
			$fields['yomigana_first_name']  = get_user_meta( $customer_id, $name . '_yomigana_first_name', true );
			$fields['yomigana_last_name']  = get_user_meta( $customer_id, $name . '_yomigana_last_name', true );
			$fields['phone']  = get_user_meta( $customer_id, $name . '_phone', true );
		}else{
			$fields['yomigana_first_name']  = get_user_meta( $customer_id, $name . '_yomigana_first_name', true );
			$fields['yomigana_last_name']  = get_user_meta( $customer_id, $name . '_yomigana_last_name', true );
			$fields['phone']  = get_user_meta( $customer_id, $name . '_phone', true );
		}

		return $fields;
	}
	public function wc4jp_billing_address( $fields, $args) {
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			$fields['yomigana_first_name'] = get_post_meta( $order_id, '_billing_yomigana_first_name', true );
			$fields['yomigana_last_name'] = get_post_meta( $order_id, '_billing_yomigana_last_name', true );
			$fields['phone'] = get_post_meta( $order_id, '_billing_phone', true );
		}else{
			$fields['yomigana_first_name'] = $args->billing_yomigana_first_name;
			$fields['yomigana_last_name'] = $args->billing_yomigana_last_name;
			$fields['phone'] = $args->billing_phone;
		}
		if($fields['country'] == '')$fields['country'] = 'JP';

		return $fields;
	}
	public function wc4jp_shipping_address( $fields, $args) {
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			$fields['yomigana_first_name'] = get_post_meta( $order_id, '_shipping_yomigana_first_name', true );
			$fields['yomigana_last_name'] = get_post_meta( $order_id, '_shipping_yomigana_last_name', true );
			$fields['phone'] = get_post_meta( $order_id, '_shipping_phone', true );
		}else{
			$fields['yomigana_first_name'] = $args->shipping_yomigana_first_name;
			$fields['yomigana_last_name'] = $args->shipping_yomigana_last_name;
			$fields['phone'] = $args->shipping_phone;
		}
		if($fields['country'] == '')$fields['country'] = 'JP';

		return $fields;
	}
	public function admin_order_data_after_shipping_address( $order ){
		$order_id = version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();
		if(version_compare( WC_VERSION, '2.7', '>' )){
			$field['label'] = __( 'Shipping Phone', 'woocommerce-for-japan' );
			$field_value = get_post_meta( $order_id, '_shipping_phone', true );
			$field_value = wc_make_phone_clickable( $field_value );
			echo '<div style="display:block;clear:both;"><p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p></div>';
		}
	}

	public function wc4jp_get_order_address( $address, $type, $args ){
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$order_id = $args->get_id();
			if ( 'billing' === $type ) {
				$address['yomigana_first_name'] = get_post_meta( $order_id, '_billing_yomigana_first_name', true );
				$address['yomigana_last_name'] = get_post_meta( $order_id, '_billing_yomigana_last_name', true );
			}else{
				$address['yomigana_first_name'] = get_post_meta( $order_id, '_shipping_yomigana_first_name', true );
				$address['yomigana_last_name'] = get_post_meta( $order_id, '_shipping_yomigana_last_name', true );
				$address['phone'] = get_post_meta( $order_id, '_shipping_phone', true );
			}
		}else{
			if ( 'billing' === $type ) {
				$address['yomigana_first_name'] =$args->billing_yomigana_first_name;
				$address['yomigana_last_name'] =$args->billing_yomigana_last_name;
			} else {
				$address['yomigana_first_name'] =$args->shipping_yomigana_first_name;
				$address['yomigana_last_name'] =$args->shipping_yomigana_last_name;
				$address['phone'] = $args->shipping_phone;
			}
		}
		return $address;
	}

	//Admin CSS file function
	public function load_custom_wc4jp_admin_style() {
		$suffix       = '.min';

		// Register scripts
		wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/woocommerce/assets/js/admin/woocommerce_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION );
		wp_enqueue_script( 'woocommerce_admin' );
	}

	//FrontEnd CSS file function
	public function checkout_enqueue_style() {
		$current_theme = wp_get_theme();
		if((is_checkout() or is_account_page() )and $current_theme->get( 'Name' ) == 'Storefront'){
			wp_register_style( 'custom_checkout_wc4jp_css', plugins_url() . '/woocommerce-for-japan/assets/css/checkout-wc4jp.css', false, WC4JP_VERSION );
			wp_enqueue_style( 'custom_checkout_wc4jp_css' );
		}
		if(is_order_received_page()){
			wp_register_style( 'custom_order_received_wc4jp_css', plugins_url() . '/woocommerce-for-japan/assets/css/order-received-wc4jp.css', false, WC4JP_VERSION );
			wp_enqueue_style( 'custom_order_received_wc4jp_css' );
		}
	}

	public function admin_billing_address_fields( $fields ) {
		foreach($fields as $key => $value){
			$new_fields[$key] = $value;
		}
		$fields = array(
			'last_name' => $new_fields['last_name'],
			'first_name' => $new_fields['first_name'],
			'country' => $new_fields['country'],
			'postcode' => $new_fields['postcode'],
			'state' => $new_fields['state'],
			'city' => $new_fields['city'],
			'company' => $new_fields['company'],
			'address_1' => $new_fields['address_1'],
			'address_2' => $new_fields['address_2'],
			'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'email' => $new_fields['email'],
			'phone' => $new_fields['phone'],
		);
		if(!get_option( 'wc4jp-company-name'))unset($fields['company']);
		if(!get_option( 'wc4jp-yomigana')){
			unset($fields['yomigana_last_name']);
			unset($fields['yomigana_first_name']);
		};

		return $fields;
	}
	public function admin_shipping_address_fields( $fields ) {
		foreach($fields as $key => $value){
			$new_fields[$key] = $value;
		}
		$fields = array(
			'last_name' => $new_fields['last_name'],
			'first_name' => $new_fields['first_name'],
			'country' => $new_fields['country'],
			'postcode' => $new_fields['postcode'],
			'state' => $new_fields['state'],
			'city' => $new_fields['city'],
			'company' => $new_fields['company'],
			'address_1' => $new_fields['address_1'],
			'address_2' => $new_fields['address_2'],
			'yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'show'	=> false
			),
			'phone' => array(
				'label' => __( 'Phone', 'woocommerce-for-japan' ),
				'show'	=> false
			)
		);

		if(!get_option( 'wc4jp-company-name'))unset($fields['company']);
		if(!get_option( 'wc4jp-yomigana'))unset($fields['yomigana_last_name'],$fields['yomigana_first_name']);

		return $fields;
	}
	public function admin_customer_meta_fields( $fields ){
		$customer_meta_fields = $fields;
		//Billing fields
		$billing_fields = $fields['billing']['fields'];
		$customer_meta_fields['billing']['fields'] = array(
			'billing_last_name' => $billing_fields['billing_last_name'],
			'billing_first_name' => $billing_fields['billing_first_name'],
			'billing_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'billing_company'  => $billing_fields['billing_company'],
			'billing_country'  => $billing_fields['billing_country'],
			'billing_postcode' => $billing_fields['billing_postcode'],
			'billing_state'  => $billing_fields['billing_state'],
			'billing_city'  => $billing_fields['billing_city'],
			'billing_address_1'  => $billing_fields['billing_address_1'],
			'billing_address_2'  => $billing_fields['billing_address_2'],
			'billing_phone'  => $billing_fields['billing_phone'],
			'billing_email'  => $billing_fields['billing_email'],
		);
		//Shipping fields
		$shipping_fields = $fields['shipping']['fields'];
		$customer_meta_fields['shipping']['fields'] = array(
			'shipping_last_name' => $shipping_fields['shipping_last_name'],
			'shipping_first_name' => $shipping_fields['shipping_first_name'],
			'shipping_yomigana_last_name' => array(
				'label' => __( 'Last Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_yomigana_first_name' => array(
				'label' => __( 'First Name Yomigana', 'woocommerce-for-japan' ),
				'description' => '',
			),
			'shipping_company'  => $shipping_fields['shipping_company'],
			'shipping_country'  => $shipping_fields['shipping_country'],
			'shipping_postcode' => $shipping_fields['shipping_postcode'],
			'shipping_state'  => $shipping_fields['shipping_state'],
			'shipping_city'  => $shipping_fields['shipping_city'],
			'shipping_address_1'  => $shipping_fields['shipping_address_1'],
			'shipping_address_2'  => $shipping_fields['shipping_address_2'],
			'shipping_phone'  => array(
			'label' => __( 'Phone', 'woocommerce-for-japan' ),
			'description' => '',
			),
		);
		if(!get_option( 'wc4jp-company-name'))unset($customer_meta_fields['billing']['fields']['billing_company'], $customer_meta_fields['shipping']['fields']['shipping_company']);
		if(!get_option( 'wc4jp-yomigana'))unset($customer_meta_fields['billing']['fields']['billing_yomigana_last_name'], $customer_meta_fields['billing']['fields']['billing_yomigana_first_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_last_name'], $customer_meta_fields['shipping']['fields']['shipping_yomigana_first_name']);
		return $customer_meta_fields;
	}

	// Automatic input from postal code to Address for billing
	public function auto_zip2address_billing(){
		$this->auto_zip2address( 'billing', apply_filters('billing_select_num',2) );
	}

	// Automatic input from postal code to Address for shipping
	public function auto_zip2address_shipping(){
		$this->auto_zip2address( 'shipping', apply_filters('shipping_select_num',2) );
	}

	// Automatic input from postal code to Address for shipping
	public function auto_zip2address_myaccount_shipping(){
		$this->auto_zip2address( 'shipping', 2 );
	}
	// Automatic input from postal code to Address
	function auto_zip2address( $method, $num ){
	    if(version_compare( WC_VERSION, '3.6', '>=' )){
            $wc4jp_countries = new WC_Countries;
            $states = $wc4jp_countries->get_states();
        }else{
            global $states;
        }
		$states = $this->wc4jp_states( $states );
		add_action( 'wp_enqueue_scripts', 'yahoo_api_scripts' );
		if(get_option( 'wc4jp-yahoo-app-id' )){
			$yahoo_app_id = get_option( 'wc4jp-yahoo-app-id' );
		}else{
			$yahoo_app_id = 'dj0zaiZpPWZ3VWp4elJ2MXRYUSZzPWNvbnN1bWVyc2VjcmV0Jng9MmY-';
		}
		if(version_compare( WC_VERSION, '2.7', '>=' )){
			$state_id = 'select2-'.$method.'_state-container';
		}else{
			$state_id = 'select2-chosen-'.$num;
		}
		if(get_option( 'wc4jp-zip2address' )){
			wp_enqueue_script( 'yahoo-app','https://map.yahooapis.jp/js/V1/jsapi?appid='.$yahoo_app_id,array('jquery'),WC4JP_VERSION);
			echo '
<script type="text/javascript">
// Search Japanese Postal number
jQuery(function($) {
$(document).ready(function(){
	$("#'.$method.'_postcode").keyup(function(){
	    var zip = $("#'.$method.'_postcode").val(),
	    zipCount = zip.length;
	    if(zipCount == 4 && zip.charAt(zipCount -1) != "-") {
		    alert("'.__('Please enter a hyphen [-] when entering a zip code.','woocommerce-for-japan').'");
	    }else if(zipCount > 7) {
    var url = "https://map.yahooapis.jp/search/zip/V1/zipCodeSearch";
    var param = {
        appid: "'.$yahoo_app_id.'",
        output: "json",
        query: $("#'.$method.'_postcode").val()
    };
    $.ajax({
        url: url,
        data: param,
        dataType: "jsonp",
        success: function(result) {
            var ydf = new Y.YDF(result);
            // Display Address from Zip
            dispZipToAddress'.$method.'(ydf);
        },
        error: function() {
            // Error handling
        }
    });
    }
    });
});
});
// Display Address from Zipcode
function dispZipToAddress'.$method.'(ydf) {
	var address = ydf.features[0].property.Address;
	var state = address.substr( 0, 3 );
	var states = new Array();';

	foreach((array)$states['JP'] as $key => $value){
		$key = substr($key, 2);
		if($key == '14' || $key == "30" || $key == "46"){
			echo 'states['.$key.'] = "'.mb_substr($value, 0, 3).'";';
		}else{
			echo 'states['.$key.'] = "'.$value.'";';
		}
	}

		echo '
	var state_id = jQuery.inArray(state, states);
	jQuery("#'.$method.'_state").val(state_id);	
	var text_num = 3;
	if(state_id == "14" || state_id == "30" || state_id == "46"){
		text_num = 4;
	}
	var city = address.substr( text_num );
	jQuery("#'.$method.'_city").val(city);
	states[14] = "'.$states['JP']['JP14'].'";
	states[30] = "'.$states['JP']['JP30'].'";
	states[46] = "'.$states['JP']['JP46'].'";
	if(state_id > 9){
	document.getElementById("'.$method.'_state").value = "JP" + state_id;
	}else{
	document.getElementById("'.$method.'_state").value = "JP0" + state_id;
	}
	document.getElementById("'.$state_id.'").innerHTML = states[state_id];
}
</script>
		';
		}
	}
	/**
	 * Update customer data when create and update
	 *
	 * @param obj $customer_array and $customer
	 */
	public function wc4jp_update_customer_args($customer_array, $customer) {
		$customer_array = array(
			'ID' => $customer->get_id(),
			'display_name' => $customer->get_last_name() . ' ' . $customer->get_first_name(),
		);
		if(isset($customer_array['role'])){
			$customer_array['role'] = $customer->get_role();
		}elseif(isset($customer_array['user_email'])){
			$customer_array['user_email'] = $customer->get_email();
		}
		return $customer_array;
	}

	//Add Ken and Fu to prefectures in Japan.
	public function wc4jp_states( $states ){
		if(get_locale() == 'ja' and version_compare( WC()->version, '3.0.0', '<') ){
			$states['JP']['JP02'] = __( 'Aomori-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP03'] = __( 'Iwate-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP04'] = __( 'Miyagi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP05'] = __( 'Akita-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP06'] = __( 'Yamagata-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP07'] = __( 'Fukushima-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP08'] = __( 'Ibaraki-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP09'] = __( 'Tochigi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP10'] = __( 'Gunma-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP11'] = __( 'Saitama-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP12'] = __( 'Chiba-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP14'] = __( 'Kanagawa-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP15'] = __( 'Niigata-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP16'] = __( 'Toyama-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP17'] = __( 'Ishikawa-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP18'] = __( 'Fukui-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP19'] = __( 'Yamanashi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP20'] = __( 'Nagano-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP21'] = __( 'Gifu-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP22'] = __( 'Shizuoka-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP23'] = __( 'Aichi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP24'] = __( 'Mie-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP25'] = __( 'Shiga-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP26'] = __( 'Kyoto-Fu', 'woocommerce-for-japan' );
			$states['JP']['JP27'] = __( 'Osaka-Fu', 'woocommerce-for-japan' );
			$states['JP']['JP28'] = __( 'Hyogo-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP29'] = __( 'Nara-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP30'] = __( 'Wakayama-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP31'] = __( 'Tottori-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP32'] = __( 'Shimane-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP33'] = __( 'Okayama-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP34'] = __( 'Hiroshima-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP35'] = __( 'Yamaguchi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP36'] = __( 'Tokushima-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP37'] = __( 'Kagawa-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP38'] = __( 'Ehime-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP39'] = __( 'Kochi-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP40'] = __( 'Fukuoka-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP41'] = __( 'Saga-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP42'] = __( 'Nagasaki-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP43'] = __( 'Kumamoto-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP44'] = __( 'Oita-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP45'] = __( 'Miyazaki-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP46'] = __( 'Kagoshima-Ken', 'woocommerce-for-japan' );
			$states['JP']['JP47'] = __( 'Okinawa-Ken', 'woocommerce-for-japan' );
		}

		return $states;
	}
}
// Address Fields Class load
new AddressField4jp();
