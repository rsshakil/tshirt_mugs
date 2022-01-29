<?php
/**
 * Sekisyo bootstrap
 */

// Register 3 licenses
\Hametuha\Sekisyo\GateKeeper::register(
	'rmiDm',
	__DIR__ . '/woopay-jp.php',
	__( 'WooPayJP Single License', 'woopayjp' ),
	__( 'You can install WooPayJP for this site', 'woopayjp' ),
	'https://gianism.info/wp-json/warifu/v1/license/rmiDm/',
	12
);

\Hametuha\Sekisyo\GateKeeper::register(
	'ZJEUF',
	__DIR__ . '/woopay-jp.php',
	sprintf( __( 'WooPayJP %d License', 'woopayjp' ), 3 ),
	sprintf( __( 'You can install WooPayJP for %1$d sites including %2$s', 'woopayjp' ), 3, get_bloginfo( 'name' ) ),
	'https://gianism.info/wp-json/warifu/v1/license/ZJEUF/',
	12
);

\Hametuha\Sekisyo\GateKeeper::register(
	'CqgHP',
	__DIR__ . '/woopay-jp.php',
	sprintf( __( 'WooPayJP %d License', 'woopayjp' ), 10 ),
	sprintf( __( 'You can install WooPayJP for %1$d sites including %2$s', 'woopayjp' ), 10, get_bloginfo( 'name' ) ),
	'https://gianism.info/wp-json/warifu/v1/license/CqgHP/',
	12
);

/**
 * Show error message
 */
add_action( 'admin_notices', function () {
	if ( current_user_can( 'activate_plugins' ) && ! \Hametuha\Sekisyo\GateKeeper::is_valid( [
			'rmiDm',
			'ZJEUF',
			'CqgHP'
		] )
	) {
		printf(
			'<div class="%s"><p>%s %s</p></div>',
			(
				( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'updated' : 'error'
			),
			wp_kses( sprintf(
				__( '[WooPay.JP] license is invalid. Please go to <a href="%s">setting page</a> and enter license. If you don\'t have one, grab license at <a href="%s" target="_blank">gianism.info</a>! ', 'warifu' ),
				admin_url( 'plugins.php?page=sekisyo' ),
				'https://gianism.info/ja/add-on/woopayjp/'
			), [ 'a' => [ 'href' => true, 'target' => true ] ] ),
			(
				( defined( 'WP_DEBUG' ) && WP_DEBUG )
				? __( 'If this is test environment, it\'s O.K.', 'woopayjp' )
				: ''
			)
		);
	}
} );

/**
 * Check if license is valid.
 */
add_filter( 'woopayjp_is_sekisyo_passed', function ( $passed ) {
	return \Hametuha\Sekisyo\GateKeeper::is_valid( [ 'rmiDm', 'ZJEUF', 'CqgHP' ] );
} );
