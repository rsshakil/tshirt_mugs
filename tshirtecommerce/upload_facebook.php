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
session_start();

require_once(ROOT.DS."includes".DS."face.php");
require_once(ROOT.DS."includes".DS."functions.php");

$dg = new DG();
$setting = $dg->getSetting();
if(!empty($setting->facebook_api) && !empty($setting->facebook_secret))
{
	$appId = $setting->facebook_api;
	$secret = $setting->facebook_secret;
	if(!empty($setting->facebook_version))
		$version = $setting->facebook_version;
	else
		$version = 'v2.5';
	$config = array(
		'app_id' => $appId,
		'app_secret' => $secret,
		'default_graph_version' => $version
	);

	$fb = new Face($config);
	
	$album_id = '';
	$after = '';
	$before = '';
	if(isset($_GET['album_id']))
		$album_id = $_GET['album_id'];
	if(isset($_GET['after']))
		$after = $_GET['after'];
	if(isset($_GET['before']))
		$before = $_GET['before'];

	// get photo.
	if($album_id == '')
	{
		$per_page = '';
		if($after != '')
			$per_page = '&after'.$after;
		if($before != '')
			$per_page = '&before'.$before;
		
		$albums = $fb->getAllAlbum(24, $per_page);
		if(count($albums['data']) && is_array($albums['data']))
		{
			echo '<div class="row" style="overflow: auto; max-height: 300px;">';
			foreach($albums['data'] as $album)
			{
				if($album['count'] != '')
					$count = '<i style="color: #f65e13;">('.$album['count'].')</i>';
				else
					$count = '';
				echo '
					<div class="col-xs-6 col-sm-4 col-md-3" style="float: none; margin-right: -4px; display: inline-block; margin-bottom: 5px; margin-top: 5px;">
						<a href="javascript:void(0);" class="thumbnail" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php?album_id='.$album['album_id'].'\')" title="'.$album['title'].'">
							<img class="img-responsive" src="'.$album['cover_photo'].'" alt="'.$album['title'].'">
							<span class="help-block" style="margin-bottom: 0px;">'.$album['title'].$count.'</span>
						</a>
					</div>
				';
			}
			echo '</div>';
			
			// pagination.
			//echo '<pre>'; print_r($albums['page']); echo '</pre>';
			if(count($albums['page']))
			{
				echo '<ul class="pagination pull-right">';
				if(isset($albums['page']['previous']))
					echo '<li>
						<a href="javascript:void(0);" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php?before='.$albums['page']['cursors']['before'].'\')"><span aria-hidden="true">&laquo;</span></a>
					</li>';
					
				if(isset($albums['page']['next']))
					echo '<li>
						<a href="javascript:void(0);" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php?after=='.$albums['page']['cursors']['after'].'\')"><span aria-hidden="true">&raquo;</span></a>
					</li>';
				echo '</ul>';
			}
		}
	}else
	{	
		// get album.
		$per_page = '';
		if($after != '')
			$per_page = '&after'.$after;
		if($before != '')
			$per_page = '&before'.$before;
			
		$photos = $fb->getAllPhoto($album_id, 24, $per_page);
		if(count($photos['data'])){
			echo '<div class="row">';
			echo '<div class="col-md-12">';
			echo '<a href="javascript:void(0);" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php\')" class="btn btn-danger btn-xs pull-right" style="margin-bottom: 10px;">Back</a>';
			echo '</div>';
			echo '</div>';
			echo '<div class="row" style="overflow: auto; max-height: 300px;">';
			foreach($photos['data'] as $photo){
				echo '
				<div class="col-xs-6 col-sm-4 col-md-3" style="display: inline-block; float: none; margin-bottom: 5px; margin-top: 5px; margin-right: -4px;">
					<a href="javascript:void(0);" onclick="design.upload.facebook(\''.$photo['photo_big'].'\')" class="thumbnail">
						<img class="img-responsive" src="'.$photo['photo'].'">
					</a>
				</div>
				';
			}
			echo '</div>';
			
			// pagination.
			//echo '<pre>'; print_r($photos['page']); echo '</pre>';
			if(count($photos['page']))
			{
				echo '<ul class="pagination pull-right">';
				if(isset($photos['page']['previous']))
					echo '<li>
						<a href="javascript:void(0);" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php?album_id='.$album_id.'&before='.$photos['page']['cursors']['before'].'\')"><span aria-hidden="true">&laquo;</span></a>
					</li>';
					
				if(isset($photos['page']['next']))
					echo '<li>
						<a href="javascript:void(0);" onclick="fbAjax(\''.$dg->url().'tshirtecommerce/upload_facebook.php?album_id='.$album_id.'&after=='.$photos['page']['cursors']['after'].'\')"><span aria-hidden="true">&raquo;</span></a>
					</li>';
				echo '</ul>';
			}
		}
	}
}

?>