<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.osdn.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

define('FORCE_SSL_ADMIN', true);
if ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
       $_SERVER['HTTPS']='on';
}

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define( 'DB_NAME', 'notjustbottle' );

/** MySQL データベースのユーザー名 */
define( 'DB_USER', 'root' );

/** MySQL データベースのパスワード */
define( 'DB_PASSWORD', 'Aquajacket1114' );

/** MySQL のホスト名 */
define( 'DB_HOST', 'localhost:3306' );

/** データベースのテーブルを作成する際のデータベースの文字セット */
define( 'DB_CHARSET', 'utf8mb4' );

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'EdurYgK|Q6-gI18KQ+lK{K.YE[AuQhJUxf,)!TJu|^5E<s%9Ru=875A-k)%72,OE' );
define( 'SECURE_AUTH_KEY',  'NL@5V<rl ,&Ro$De}Udj7ZiW3.[q|R()=D1nlg!+j3!d12yY!@0;rGn~|&QjxVs[' );
define( 'LOGGED_IN_KEY',    '+*!n@K8{lWV5c.1,iqj&~oCbS.^S2Bc|?^lLY@filh# 3=*LqM%=9l4!!x/Lah E' );
define( 'NONCE_KEY',        '*7?Iq:Vhz{`krQ@Mo+1*taq>NJw@AZ#|i4eapK1B0`f7W%gMsL{B>LuK~B<@VJt_' );
define( 'AUTH_SALT',        'rH((Be(@OI5}40/8ctrR<633Y>pQS/^2$#[1AA] `Sh2)n(P_+:yTy1ZN~1F%a.A' );
define( 'SECURE_AUTH_SALT', '9 bsLY4?t.+Xfk#MK;O9Cq#wS:Qc(MBPROuZ##&>9h,9>2d2BlK$U@9g#n8qOB_1' );
define( 'LOGGED_IN_SALT',   '<&l5M.8S&1T0R]a.0K5)+viWnuX7O?jJE<v{wL@<OBZuPlWJ&$@*&7e{@UTHvaYR' );
define( 'NONCE_SALT',       'rVKMFN] JsQ1_)aZ8Eu)}fcVtBJ9@$CCgps4=S>$_4>7G=6|XF}YDM$-]iK`qrF!' );

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でのパブリッシングをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

