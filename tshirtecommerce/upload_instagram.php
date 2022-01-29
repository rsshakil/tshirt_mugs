<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * API
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

error_reporting(0);
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require_once(ROOT.DS."includes".DS."ins.php");
require_once(ROOT.DS."includes".DS."functions.php");

$dg = new dg();
$setting = $dg->getSetting();
if(!empty($setting->instagram_apikey) && !empty($setting->instagram_apisecret))
{
	$apiKey      = $setting->instagram_apikey;
	$apiSecret   = $setting->instagram_apisecret;
	$apiCallback = $dg->url().'tshirtecommerce/upload_instagram.php';
	
	$config = array(
	  'apiKey'      => $apiKey,
	  'apiSecret'   => $apiSecret,
	  'apiCallback' => $apiCallback
	);
	
	$in = new ins($config);
	
	if(isset($_GET['next_max_id']))
		$next_id = $_GET['next_max_id'];
	else
		$next_id = '';
	
	$data = $in->getAllPhoto($next_id);
			
	if(count($data['data']))
	{
		foreach($data['data'] as $photo)
		{
			// Show photo: {'photo_big', 'photo', 'photo_medium'}
			//echo "<img src='".$photo['photo_big']."'>";
			//echo "<img src='".$photo['photo_medium']."'>";
			echo '
			<div class="col-xs-6 col-sm-4 col-md-3" style="display: inline-block; float: none; margin-bottom: 5px; margin-right: -4px;">
				<a href="javascript:void(0);" onclick="design.upload.Instagram(\''.$photo['photo_big'].'\')" class="thumbnail">
					<img class="img-responsive" src="'.$photo['photo'].'">
				</a>
			</div>
			';
		}
		
		if($data['next_max_id'] != '')
			echo '<div class="col-md-12 text-center"><button id="btn-instagram-loadmore" class="btn btn-primary" type="button" onclick="inAjax(\''.$dg->url().'tshirtecommerce/upload_instagram.php?next_max_id='.$data['next_max_id'].'\', \'append\')"><i class="fa fa-plus"></i></button></div>';
		else
			echo '<script type="text/javascript">setTimeout(function(){self.close();}, 100);</script>';
	}
}
?>