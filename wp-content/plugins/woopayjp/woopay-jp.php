<?php
/**
 * Plugin Name: WooPay.JP
 * Plugin URI: https://gianism.info/ja/add-on/woopayjp
 * Description: Add Pay.JP payment gateway to WooCommerce.
 * Version: 1.3.1
 * PHP Version: 5.4.0
 * Author: Hametuha INC. <support@hametuha.co.jp>
 * Author URI: https://hametuha.co.jp/
 * License: GPL3 or later
 * Text Domain: woopayjp
 * WC requires at least: 3.0
 * WC tested up to: 3.4.5
 *
 * @package woopayjp
 */

defined( 'ABSPATH' ) or die( 'Do not load directly.' );


/**
 * Get plugin info
 *
 * @internal
 * @return array
 */
function _woopayjp_info( $error = '' ) {
	static $info = [];
	if ( ! $info ) {
		$info = get_file_data( __FILE__, array(
			'name' => 'Plugin Name',
			'version' => 'Version',
			'php_version' => 'PHP Version',
			'domain' => 'Text Domain',
		) );
	}
	if ( $error ) {
		$info['error'] = $error;
	}
	return $info;
}

/**
 * Init plugin
 */
function _woopayjp_init() {
	// Add i18n.
	load_plugin_textdomain( 'woopayjp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	$plugin_info = _woopayjp_info();
	// Check PHP Version.
	if ( version_compare( phpversion(), $plugin_info['php_version'], '<' ) ) {
		_woopayjp_info( sprintf( __( 'Plugin %s requires PHP %s and over, but your PHP is %s', 'woopayjp' ), $plugin_info['name'], $plugin_info['php_version'], phpversion() ) );
		add_action( 'admin_notices', '_woopayjp_error' );
		return;
	}
	// Load bootstrap.
	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		require __DIR__ . '/vendor/autoload.php';
		require __DIR__ . '/sekisyo.php';
		call_user_func( [ 'Hametuha\\WooCommerce\\Service\\WooPayJp\\BootStrap', 'get_instance' ] );
	} else {
		_woopayjp_info( __( 'Composer auto loader missing. Please run "composer install".', 'woopayjp' ) );
	    add_action( 'admin_notices', '_woopayjp_error' );
    }
}

/**
 * Show error messages
 */
function _woopayjp_error() {
	$plugin_info = _woopayjp_info();
	esc_html__( 'Add Pay.JP payment gateway to WooCommerce.', 'woopayjp' );
	?>
    <div class="error">
        <p>
            <strong>[Error]</strong>
			<?php echo esc_html( $plugin_info['error'] ) ?>
        </p>
    </div>
	<?php
}

/**
 * Flush rewrite rules.
 *
 * @ignore
 */
function _woopayjp_flush() {
	flush_rewrite_rules();
}

/**
 * Get asset URL
 *
 * @param  string $path Relative path from assets folder.
 * @return string
 */
function woopayjp_asset( $path ) {
	return plugin_dir_url( __FILE__ ) . 'assets/' . ltrim( $path, '/' );
}

/**
 * Get plugin's version
 *
 * @return string
 */
function woopayjp_version() {
	static $version = '0.0.0';
	if ( '0.0.0' == $version ) {
		$data = _woopayjp_info();
		$version = $data['version'];
	}
	return $version;
}

// Initialize plugin.
add_action( 'plugins_loaded', '_woopayjp_init' );

// Flush rewrite rules on activation.
register_activation_hook( __FILE__, '_woopayjp_flush' );
