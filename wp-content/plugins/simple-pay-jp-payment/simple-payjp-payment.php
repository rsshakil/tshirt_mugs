<?php
/*
Plugin Name: Simple PAY.JP Payment
Plugin URI: https://it-soudan.com/simple-payjp-payment/
Description: Add payment by PAY.JP
Version: 0.1.5
Author: koyacode
Author URI: https://it-soudan.com/
Text Domain: simple-payjp-payment
Domain Path: /languages
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

require_once 'vendor/payjp/payjp-php/init.php';


function simplepayjppayment_get_secret_key() {
    $live_enabled = get_option( 'simplepayjppayment-live-enabled' );
    $secret_key = get_option( 'simplepayjppayment-test-secret-key', "" );
    if ( $live_enabled == 1 ) {
        $secret_key = get_option( 'simplepayjppayment-live-secret-key', "" );
    }
    return $secret_key;
}

function simplepayjppayment_create_customer( $mail ) {
    try {
        Payjp\Payjp::setApiKey($secret_key);
        $result = \Payjp\Customer::create(array(
            "email" => $mail
        ));
        if (isset($result['error'])) {
            throw new Exception();
        }
    } catch (Exception $e) {
        esc_html_e( 'Payment failed', 'simple-payjp-payment' );
        exit;
    }
    return $result['id'];
}

function simplepayjppayment_create_payment( $secret_key, $token, $amount, $currency, $description ) {
    // $customer_id = simplepayjppayment_create_customer();
    try {
        Payjp\Payjp::setApiKey($secret_key);
        $result = Payjp\Charge::create(array(
                "card" => $token,
                "amount" => $amount,
                "currency" => $currency,
                "description" => $description,
        ));
        if (isset($result['error'])) {
            throw new Exception();
        }
    } catch (Exception $e) {
        return false;
    }

    return true;
}

function simplepayjppayment_handle_form_submitted() {
    if ( !isset( $_POST['payjp-token'] ) ) {
        return;
    }

    if ( !isset( $_POST['amount'] ) ) {
        esc_html_e( 'amount is empty', 'simple-payjp-payment' );
        exit;
    }

    if ( !isset( $_POST['form-id'] ) ) {
        esc_html_e( 'form id is empty', 'simple-payjp-payment' );
        exit;
    }

    $err = '';
    $safe_token = sanitize_text_field ( $_POST['payjp-token'] );
    $safe_amount = intval( $_POST['amount'] );
    $safe_form_id = sanitize_text_field( $_POST['form-id'] );
    $currency = 'jpy';

    $secret_key = simplepayjppayment_get_secret_key();
    if ( $secret_key == "" ) {
        esc_html_e( 'Invalid key', 'simple-payjp-payment' );
        exit;
    }

    $desc = "form-id:" . $safe_form_id;
    if ( isset( $_POST['user_mail'] ) ) {
        $safe_mail = sanitize_text_field( $_POST['user_mail'] );
        $desc .=  ", mail:" . $safe_mail;
    }
    if ( isset( $_POST['user_name'] ) ) {
        $safe_name = sanitize_text_field( $_POST['user_name'] );
        $desc .=  ", name:" . $safe_name;
    }

    $result = simplepayjppayment_create_payment( $secret_key, $safe_token, $safe_amount, $currency, $desc );
    if ( $result ) {
        esc_html_e( 'Payment completed', 'simple-payjp-payment' );
    } else {
        esc_html_e( 'Payment failed', 'simple-payjp-payment' );
    }

    exit;
}


// option
$simplepayjppayment_option_default = new SimplePayjpPayment_Option_Default();
class SimplePayjpPayment_Option_Default {
    public $test_public_key = '';
    public $test_secret_key = '';
    public $live_public_key = '';
    public $live_secret_key = '';
    public $live_enabled = 0;
}

// shortcode
function simplepayjppayment_handler( $atts ) {

    $a = shortcode_atts( array(
        'amount'  => 0, 
        'form-id'  => "",
        'name' => 'yes',
    ), $atts );

    $safe_amount = intval( $a['amount'] );
    if ( ( $safe_amount < 50 ) || ( 100000 <= $safe_amount ) ) {
        return esc_html__( 'Invalid amount value', 'simple-payjp-payment' );
    }

    $safe_form_id = sanitize_text_field( $a['form-id'] );
    if ( $safe_form_id === "" ) {
        return esc_html__( 'Invalid form id', 'simple-payjp-payment' );
    }

    $name_enabled = $a['name'] === 'yes' ? true : false;

    $live_enabled = get_option( 'simplepayjppayment-live-enabled', 0 );
    $public_key = get_option( 'simplepayjppayment-test-public-key', "" );
    if ( $live_enabled == 1 ) {
        $public_key = get_option( 'simplepayjppayment-live-public-key', "" );
    }
    if ( $public_key == "" ) {
        return esc_html__( 'Invalid key', 'simple-payjp-payment' );
    }

    $_SESSION["key"] = md5(uniqid().mt_rand());
    ?>

    <?php ob_start(); ?>
    <div class="simplepayjppayment-container">
        <form action="<?php the_permalink(); ?>" method="post">
            <input type="hidden" name="key" value="<?php echo htmlspecialchars( $_SESSION["key"], ENT_QUOTES );?>">
            <label for="simplepayjppayment-mail">E-mail:</label>
            <input type="email" id="simplepayjppayment-mail" name="user_mail">
            <?php if ( $name_enabled ) { ?>
                <br /><label for="simplepayjppayment-mail"><?php esc_html_e( 'Name', 'simple-payjp-payment' ); ?>:</label>
                <input type="text" id="simplepayjppayment-name" name="user_name">
            <?php } ?>
            <script src="https://checkout.pay.jp/" class="payjp-button" data-key="<?php
            echo esc_attr( $public_key ); ?>"></script>
            <input name="amount" value="<?php echo esc_attr( $safe_amount ); ?>" type="hidden">
            <input name="form-id" value="<?php echo esc_attr( $safe_form_id ); ?>" type="hidden">
        </form>
    </div>
    <?php return ob_get_clean();
}

function my_template_redirect() {
    if ( ! empty( $_POST ) && ! empty( $_POST[ 'form-id' ] ) ) {
        session_start();
        if( isset( $_SESSION["key"], $_POST["key"] ) && $_SESSION["key"] == $_POST["key"] ) {
            unset( $_SESSION["key"] );
            simplepayjppayment_handle_form_submitted();
        } else {
            wp_safe_redirect( get_permalink(), 302 );
            exit();
        }
    } else {
        session_start();
        add_shortcode( 'simple-payjp-payment', 'simplepayjppayment_handler' );
    }
}
add_action( 'template_redirect', 'my_template_redirect' , 10000 );

// css
function simplepayjppayment_register_my_styles() {
	wp_enqueue_style(
        'simplepayjppayment',
        plugins_url( 'css/simple-payjp-payment.css', __FILE__ )
    );
}
add_action( 'wp_enqueue_scripts', 'simplepayjppayment_register_my_styles' );

// admin menu
function simplepayjppayment_admin_menu() {
    add_options_page(
        __('Simple PAY.JP Payment', 'simple-payjp-payment'),
        __('Simple PAY.JP Payment', 'simple-payjp-payment'),
        'administrator',
        'simplepayjppayment_show_admin_panel',
        'simplepayjppayment_show_admin_panel'
    );
}
add_action( 'admin_menu', 'simplepayjppayment_admin_menu');

function simplepayjppayment_show_admin_panel() {
    global $simplepayjppayment_option_default;

    $simplepayjppayment_option_default->test_public_key = get_option( 'simplepayjppayment-test-public-key', "" );
    $simplepayjppayment_option_default->test_secret_key = get_option( 'simplepayjppayment-test-secret-key', "" );
    $simplepayjppayment_option_default->live_public_key = get_option( 'simplepayjppayment-live-public-key', "" );
    $simplepayjppayment_option_default->live_secret_key = get_option( 'simplepayjppayment-live-secret-key', "" );
    $simplepayjppayment_option_default->live_enabled = get_option( 'simplepayjppayment-live-enabled', 0 );
?>
<div class="warp">
    <h2>Simple PAY.JP Payment</h2>
    <form id="simplepayjppayment-form" method="post" action="">
        <?php wp_nonce_field( 'my-nonce-key', 'simplepayjppayment_admin_menu' ); ?>

        <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><label for="test-secret-key"><?php esc_html_e( 'Test Secret Key', 'simple-payjp-payment' ); ?> </label>
            </th>
            <td>
                <input type="text" name="test-secret-key" class="regular-text" value="<?php echo esc_attr( $simplepayjppayment_option_default->test_secret_key ) ; ?>">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="test-public-key"><?php esc_html_e( 'Test Public Key', 'simple-payjp-payment' ); ?> </label>
            </th>
            <td>
                <input type="text" name="test-public-key" class="regular-text" value="<?php echo esc_attr( $simplepayjppayment_option_default->test_public_key ) ; ?>">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="live-secret-key"><?php esc_html_e( 'Live Secret Key', 'simple-payjp-payment' ); ?> </label>
            </th>
            <td>
                <input type="text" name="live-secret-key" class="regular-text" value="<?php echo esc_attr( $simplepayjppayment_option_default->live_secret_key ) ; ?>">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="live-public-key"><?php esc_html_e( 'Live Public Key', 'simple-payjp-payment' ); ?> </label>
            </th>
            <td>
                <input type="text" name="live-public-key" class="regular-text" value="<?php echo esc_attr( $simplepayjppayment_option_default->live_public_key ) ; ?>">
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="live-enabled"><?php esc_html_e( 'Enable Live', 'simple-payjp-payment' ); ?>
            </label>
            </th>
            <td>
                <input type="checkbox" name="live-enabled" class="checkbox" value="1" 
                <?php if ( $simplepayjppayment_option_default->live_enabled == 1 ) {
                ?> checked="checked"><?php } ?>
            </td>
        </tr>
        </tbody>
        </table>

        <p><input type="submit"
        value="<?php echo esc_attr( __( 'Save', 'simple-payjp-payment' ) ); ?>"
        class="button button-primary button-large">
        </p>
    </form>
</div>
<?php
}


function simplepayjppayment_admin_init() {
    global $SimplePayjpPayment_option_default;

    if ( ! isset( $_POST['simplepayjppayment_admin_menu'] ) || ! $_POST['simplepayjppayment_admin_menu']) {
        return;
    }

    if ( ! check_admin_referer( 'my-nonce-key', 'simplepayjppayment_admin_menu' ) ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $safe_test_public_key = '';
    if ( isset( $_POST['test-public-key'] ) ) {
        $test_public_key = (string) filter_input( INPUT_POST, 'test-public-key' );
        $safe_test_public_key = sanitize_text_field( $test_public_key );
    }
    update_option( 'simplepayjppayment-test-public-key', $safe_test_public_key );

    $safe_test_secret_key = '';
    if ( isset( $_POST['test-secret-key'] ) ) {
        $test_secret_key = (string) filter_input( INPUT_POST, 'test-secret-key' );
        $safe_test_secret_key = sanitize_text_field( $test_secret_key );
    }
    update_option( 'simplepayjppayment-test-secret-key', $safe_test_secret_key );

    $safe_live_public_key = '';
    if ( isset( $_POST['live-public-key'] ) ) {
        $live_public_key = (string) filter_input( INPUT_POST, 'live-public-key' );
        $safe_live_public_key = sanitize_text_field( $live_public_key );
    }
    update_option( 'simplepayjppayment-live-public-key', $safe_live_public_key );

    $safe_live_secret_key = '';
    if ( isset( $_POST['live-public-key'] ) ) {
        $live_secret_key = (string) filter_input( INPUT_POST, 'live-secret-key' );
        $safe_live_secret_key = sanitize_text_field( $live_secret_key );
    }
    update_option( 'simplepayjppayment-live-secret-key', $safe_live_secret_key );

    $safe_live_enabled = 0;
    if ( isset( $_POST['live-enabled'] ) ) {
        $safe_live_enabled = intval( $_POST['live-enabled'] );
    }
    update_option( 'simplepayjppayment-live-enabled', $safe_live_enabled );

    wp_safe_redirect( menu_page_url( 'simplepayjppayment_admin_menu', false ) );
}
add_action( 'admin_init', 'simplepayjppayment_admin_init' );

// admin css
function simplepayjppayment_register_my_admin_styles( $hook ) {
    if ( 'settings_page_simplepayjppayment_show_admin_panel' != $hook ) {
        return;
    }
	wp_enqueue_style(
        'style-name-admin',
        plugins_url( 'css/simple-payjp-payment-admin.css', __FILE__ )
    );
}
add_action( 'admin_enqueue_scripts', 'simplepayjppayment_register_my_admin_styles' );


// i18n
function simplepayjppayment_load_textdomain() {
    load_plugin_textdomain( 'simple-payjp-payment', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'simplepayjppayment_load_textdomain' );

?>
