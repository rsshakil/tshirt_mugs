<?php
/**
 * Plugin Name: Ultimate Addons for Custom Product Designer
 * Plugin URI: http://tshirtecommerce.com
 * Description: Ultimate Addons for Custom Product Designer (WooCommerce, Opencart, Prestashop)
 * Version: 1.0.0
 * Author: tshirtecommerce.com
 * Author URI: http://tshirtecommerce.com
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( 'No script!' );

if( !function_exists('openURL') )
{
	function openURL($url)
	{
		$data = false;
		if( function_exists('curl_exec') )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, network_site_url());
			$data = curl_exec($ch);
			curl_close($ch);
		}
		if( $data == false && function_exists('file_get_contents') )
		{
			$data = @file_get_contents($url);
		}
		return $data;	
	}
}

function p9f_addon_version()
{
	$version 	= '1.0.0';

	$file 		= dirname(WP_CONTENT_DIR).'/'.'tshirtecommerce/platforms/version.json';
	if( file_exists($file) )
	{
		$data 	= json_decode( file_get_contents($file), true );
		if( isset($data['version']) ) $version = $data['version'];
	}
	return $version;
}

function p9f_addon_lastversion()
{
	$version 	= array(
		'version' 	=> '1.0.0',
		'date' 		=> '21 March 19' 
	);

	$url 		= 'https://updates.tshirtecommerce.com/ultimate-addons.json';
	$content 	= openURL($url);
	if($content)
	{
		$data 	= json_decode($content, true);
		if(isset($data[0])) $version = $data[0];
	}

	return $version;
}

function p9f_addon_update()
{
	$updated 			= array(
		'status' 		=> false,
		'lastversion' 	=> '1.0.0',
		'version' 		=> '1.0.0'
	);

	$website_version 	= p9f_addon_version();
	$version1 			= str_replace('.', '', $website_version);

	$last_version 		= p9f_addon_lastversion();
	$version2 			= str_replace('.', '', $last_version['version']);

	if($version2 > $version1)
	{
		$updated['status']			= true;
		$updated['version']			= $website_version;
		$updated['lastversion']		= $last_version['version'];
	}

	return $updated;
}

/* Add link Docs */
add_filter( 'plugin_row_meta', 'p9f_plugin_row_meta', 10, 2 );
function p9f_plugin_row_meta( $links, $file )
{
    if ( plugin_basename( __FILE__ ) == $file )
    {
        $row_meta = array(
          'docs'    => '<a href="' . esc_url( 'http://docs.tshirtecommerce.com/wordpress/article/install-ultimate-addons/' ) . '" target="_blank" aria-label="Docs">Docs</a>'
        );
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}

/*
* Add link update and download file
 */
function p9f_download_files( $links )
{
	$path 		= dirname(WP_CONTENT_DIR).'/'.'tshirtecommerce/';

	if( file_exists($path.'platforms') )
	{
		$links = array_merge( array(
			'<a href="' . esc_url( network_admin_url('admin.php?page=ultimate_addons_install') ) . '">Settings</a>'
		), $links );
	}
	else
	{
		$links = array_merge( array(
			'<a href="' . esc_url( network_admin_url('admin.php?page=ultimate_addons_install') ) . '">Download files</a>'
		), $links );
	}
	
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'p9f_download_files' );

/*
* Show notic if missing install pluign default.
 */
function p9f_admin_notice_error()
{
	$path 		= dirname(WP_CONTENT_DIR).'/'.'tshirtecommerce/';

	$class 		= 'notice notice-error';
	if( !file_exists($path) )
	{
		$message 	= 'Your site missing installed plugin <a href="https://codecanyon.net/item/woocommerce-custom-product-designer/10959830" target="_blank"><b>WooCommerce Custom Product Designer</b></a>. You need install this plugin before use addon. <a href="'.network_admin_url('plugin-install.php').'"><b>Install plugin</b></a> or <a href="https://codecanyon.net/item/woocommerce-custom-product-designer/10959830"><b>Buy Plugin</b></a>';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
	}
	elseif( !file_exists($path.'platforms/') )
	{
		$message 	= '<a href=""><b>CLICK HERE</b></a> to download file of <a href="https://codecanyon.net/item/ultimate-addons-for-custom-product-designer-woocommerce-opencart-prestashop/23438365" target="_blank">Ultimate Addons for Custom Product Designer</a>. <a href="http://docs.tshirtecommerce.com/wordpress/article/install-ultimate-addons" target="_blank">How to install and setting addon.</a>';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
	}
}
add_action( 'admin_notices', 'p9f_admin_notice_error' );

/*
** Add menu download files
 */
add_action( 'tshirtecommerce_menu', 'ultimate_addons_menu' );
function ultimate_addons_menu()
{
	add_submenu_page( 'online_designer', 'T-shirt eCommerce', 'Ultimate Addons', 'administrator', 'ultimate_addons_install', 'ultimate_addons_install');
}

function ultimate_addons_install()
{
	$path 					= dirname(WP_CONTENT_DIR).'/'.'tshirtecommerce/';
	
	if( !file_exists($path) )
	{
		return;
	}

	$error 					= '';
	$purchase_code_added 	= get_option( 'purchase_code_ultimate_addons' );
	
	if($purchase_code_added != '' && isset($_GET['download']) && $_GET['download'] == 1)
	{
		$download 		= 'http://updates.tshirtecommerce.com/api.php?code='.$purchase_code_added.'&platform=addons&version=101';
		$data 			= openURL($download);
		if ($data != false)
		{
			$file 		= dirname(WP_CONTENT_DIR).'/' .'ultimate_addons.zip';
			if(@file_put_contents($file, $data))
			{
				WP_Filesystem();
				$unzipfile = unzip_file( $file, dirname(WP_CONTENT_DIR).'/' );
				if ( $unzipfile )
				{
					wp_redirect( network_admin_url('admin.php?page=online_designer&task=cache') );
					exit;
				}
				else
				{
					$error = 'Sorry, your server not allow writable file. Please <a href="'.$download.'" target="_blank">download file</a>, unzip and upload to folder <b>'.dirname(WP_CONTENT_DIR).'/'.'</b>';
				}
			}
			else
			{
				$error = 'Sorry, your server not allow writable file. Please <a href="'.$download.'" target="_blank">download file</a>, unzip and upload to folder <b>'.dirname(WP_CONTENT_DIR).'/'.'</b>';
			}
		}
		else
		{
			$error = 'Sorry, your server not allow connect to other server and download file. Please <a href="'.$download.'" target="_blank">download file</a>, unzip and upload to folder <b>'.dirname(WP_CONTENT_DIR).'/'.'</b>';
		}
	}
	
	$purchase_code 			= '';
	if( isset($_POST['purchase_code_ultimate_addons']) && $_POST['purchase_code_ultimate_addons'] != '' )
	{
		$purchase_code 	= trim( $_POST['purchase_code_ultimate_addons'] );
		$domain 		= $_SERVER['HTTP_HOST'];
		$url 			= 'http://updates.tshirtecommerce.com/verify_purchase.php?code='.$purchase_code.'&platform=addons&domain='.$domain;
		$content 		= openURL($url);

		$updated 		= false;
		if ($content != false)
		{
			$result = json_decode($content, true);
			if( isset($result['error']) && $result['error'] == 0 )
			{
				update_option( 'purchase_code_ultimate_addons', $purchase_code );
				$updated = true;
				wp_redirect( network_admin_url('admin.php?page=ultimate_addons_install&download=1') );
				exit;
			}
			elseif(isset($result['error']) && $result['error'] == 1 && isset($result['actived']))
			{
				$error 	= $result['msg'];
			}
		}
		
		if( $updated === false )
		{
			update_option( 'purchase_code_ultimate_addons', '' );
			if($error == '') $error 	= 'Your purchase code not found! Please try again!';
		}
	}
?>
	<div class="wrap">
		<h1>Ultimate Addons for Custom Product Designer</h1>
		<p>Thank you purchased. Please <a href="http://docs.tshirtecommerce.com/wordpress/article/install-ultimate-addons/" target="_blank">read document</a> of install and use addon.</p>
		<p style="color: red;background-color:yellow;padding:10px 15px;display:inline-block;border-left: 3px solid red;">Please backup folder <b><?php echo $path; ?></b> of your site before install Ultimate Addons! </p>

		<?php if($error != '') { ?>
		<h4 style="color:red;"><?php echo $error; ?></h4>
		<?php } ?>

		<?php 
		if($purchase_code_added){
			$path 		= dirname(WP_CONTENT_DIR).'/'.'tshirtecommerce/platforms/';
			if(file_exists($path))
			{
				$updated 	= p9f_addon_update();

				if($updated['status'])
				{
					echo '<p>You using version <b>'.$updated['version'].'</b> of Ultimate Addons for Custom Product Designer. Please click <a href="'.network_admin_url('admin.php?page=ultimate_addons_install&download=1').'"><b>HERE</b></a> update version <b>'.$updated['lastversion'].'</b>.</p>';
				}
				else
				{
					echo '<p>You downloaded all files. Please click <a href="'.network_admin_url('admin.php?page=ultimate_addons_install&download=1').'"><b>HERE</b></a> if you want re-download.</p>';
				}
			}
			else
			{
				echo '<a href="'.network_admin_url('admin.php?page=ultimate_addons_install&download=1').'" class="button button-primary">Download files now!</a>';
			}
		?>
		
		<?php }else{ ?>
		<p>Add your purchase code and download all file to your server. <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="_bank">Click here</a> for instructions to find your purchase code.</p>

		<form method="POST" action="<?php echo network_admin_url('admin.php?page=ultimate_addons_install'); ?>">
			<table class="form-table">
				<tr valign="top">
	        		<th scope="row">Your purchase code</th>
	        		<td><input type="text" size="50" name="purchase_code_ultimate_addons" value="<?php echo $purchase_code; ?>" placeholder="Your purchase code" /></td>
	        	</tr>
	        </table>
	        <p class="submit">
	        	<input type="submit" name="submit" id="submit" class="button button-primary" value="Save & Download files">
	        </p>
	    </form>
		<?php } ?>
	</div>
<?php
}
?>