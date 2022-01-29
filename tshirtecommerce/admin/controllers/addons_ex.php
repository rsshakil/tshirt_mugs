<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');

class Addons_ex extends Controllers
{
	public $api_url 		= 'https://tshirtecommerce.com/index.php';
	public $addon_package 	= MAIN_STORE_URL;
	
	public function index()
	{
		$this->_active();
		$dg 			= new dg();

		$path 			= 'https://tshirtecommerce.com/addons/addons.json';
		$version 		= dirname(ROOT) .DS. 'platforms' .DS. 'assets';
		$addons_installed = array();
		if(file_exists($version))
		{
			$addon_path 	= dirname(ROOT) .DS. 'addons' .DS. 'install' .DS;
			$files 			= $dg->getFiles($addon_path, '.json');
			for($i=0; $i<count($files); $i++)
			{
				$file_name 						= str_replace('.json', '', $files[$i]);
				$addons_installed[$file_name] 	= 1;
				if(file_exists($addon_path.$files[$i]))
				{
					$options 						= json_decode(file_get_contents($addon_path.$files[$i]), true);
					$version 						= str_replace('.', '', $options['version']);
					$addons_installed[$file_name] 	= $version;
				}
			}
			$path 						= $this->addon_package.'addons/addons.json';
			$data['addons_installed'] 	= $addons_installed;
		}
		
		$content = $dg->openURL($path);
		
		$addons 				= json_decode($content);		
		$data['addons'] 		= $addons;
		$data['title'] 			= lang('breadcrumb_addons', true);
		$data['breadcrumb'] 	= lang('breadcrumb_addons', true);
		$data['sub_title'] 		= lang('breadcrumb_manager', true);
		
		$this->view('addon', $data);
	}

	public function _active()
	{
		$dg 			= new dg();
		$settings 		= $dg->getSetting();
		$verified 		= false;

		if(isset($settings->addons_pro) && isset($settings->addons_pro->verified) && $settings->addons_pro->verified == 1 )
		{
			$verified 	= true;
		}
		if($verified !== true)
		{
			$dg->redirect('index.php/addons_ex/verified');
		}
	}

	public function verified()
	{
		$data 					= array();
		$data['purchase_code'] 	= '';
		if(isset($_POST['purchase_code']))
		{
			$domain 				= $_SERVER['HTTP_HOST'];
			$data['purchase_code'] 	= $_POST['purchase_code'];
			$url 					= 'https://updates.tshirtecommerce.com/verify_purchase.php?code='.$data['purchase_code'].'&platform=addons&domain='.$domain;
			$dg 		= new dg();
			$content 	= $dg->openURL($url);
			$purchased 	= json_decode($content, true);
			if( isset($purchased['buyer']) )
			{
				$settings 				= json_decode( json_encode($dg->getSetting()), true );
				$settings['addons_pro'] = array(
					'verified' 	=> 1,
					'buyer' 	=> $purchased['buyer'],
					'start' 	=> $purchased['start'],
					'end' 		=> $purchased['end'],
				);
				$file 			= dirname(ROOT) .DS. 'data' .DS. 'settings.json';
				$dg->WriteFile($file, json_encode($settings));
				$dg->redirect('index.php/addons_ex');
			}
			elseif( isset($purchased['actived']) && $purchased['actived'] == 1 )
			{
				$data['error'] = $purchased['msg'];
			}
			else
			{
				$data['error'] 	= 'Your purchase code not found. Please check again and re-submit.';
			}
		}
		
		$data['title'] 			= lang('breadcrumb_addons', true);
		$data['breadcrumb'] 	= lang('breadcrumb_addons', true);
		$data['sub_title'] 		= 'Verify';
		
		$this->view('addon_verified', $data);
	}

	public function package($id = '', $version = '')
	{
		$this->_active();
		$dg = new dg();

		if($id == '' || $version == '')
		{
			$dg->redirect('index.php/addon');
		}
		
		$url 		= $this->addon_package.'addons/api.php?id='.$id.'&version='.$version;
		$file 	= $dg->openURL($url);
		if(strlen($file) > 100)
		{
			$zip 		= new ZipArchive;
			$path 		= dirname(dirname(ROOT));
			$path_file 	= $path .DS. 'addon_files.zip';
			if($dg->WriteFile($path_file, $file, true) && $zip->open($path_file) == true)
			{
				$zip->extractTo($path);
				$zip->close();
				unlink($path_file);
			}
		}
		$dg->redirect('index.php/cache/index/addons_ex');
	}
	
	public function install()
	{
		include_once(ROOT.DS.'includes'.DS.'upload.php');
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$upload = array(
			'error' => 0,
			'msg'  => ''
		);
		$data = array();
		
		// check key
		if (isset($_POST['key']) && isset($_FILES['file']))
		{
			
			if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' &&  $_POST['key'] != '')
			{
				$check_key = 0;
				
				$key 	= $_POST['key'];
				$index 	= explode('-api-tshirtecommerce-', $key);
				
				if (count($index) > 1 && $index[0] != '')
				{
					// check key
					$args = array(
						'woo_sl_action'	=> 'install',
						'licence_key'	=> $key,				
						'product_unique_id'	=> $index[0],				
						'domain'	=> $_SERVER['HTTP_HOST']
					);
					
					$result = $dg->sendPostData($this->api_url, $args);
					
					if ($result != false && $result != '')
					{
						$addon = json_decode($result);
						
						if (isset($addon->status) && $addon->status == 'success')
						{
							$check_key 		= 1;
							
							// upload file
							$up 			= new Upload();
							$up->path 		= dirname(dirname(ROOT));
							$up->file_size 	= 20971520; // 20Mb
							$up->file_type 	= array(0=>'zip'); // zip file type.
							$upload 		= $up->file($_FILES['file']);
							
							// unzip file
							if($upload['error'] == 0 && $upload['msg'] != '')
							{
								if(file_exists($upload['full_path']))
								{
									$zip = new ZipArchive;
									if(!is_writable($upload['full_path']))
										chmod($upload['full_path'], 755);
									
									if ($zip->open($upload['full_path']) === TRUE) 
									{
										$zip->extractTo($upload['path']);
										$zip->close();
										unlink($upload['full_path']);
										
										$file = dirname(ROOT) .DS. 'addons' .DS. 'install' .DS. $index[0] .'.json';
										if(file_exists($file))
										{
											$content = file_get_contents($file);
											if ($content != false)
											{
												$arr = json_decode($content);
												$arr->key = $key;
												$dg->WriteFile($file, json_encode($arr));
												$dg->redirect('index.php/addons_ex/installed');
											}
										}
										else
										{
											$check_key 		= -3;
										}
									}
									else
									{
										$check_key 		= -2;
									}
								}
							}
							else
							{
								$check_key 		= -1;
							}
						}
					}	
				}

				if ($check_key == 0)
				{
					$data['error'] = 'Your key not found! Please check your key and try again.';
				}
				else if ($check_key == -1)
				{
					$data['error'] = 'Sorry, your system not allow upload file. Please set permission 755 to ROOT folder of your site or you can install via FTP.';
				}
				else if ($check_key == -2)
				{
					$data['error'] = 'Sorry, Your system support unzip file. Please config your server and sure your server support unzip or install via FTP.';
				}
				else if ($check_key == -3)
				{
					$data['error'] = 'Your system not allow write file. Please set permission 755 to FOLDER_YOUR_SITE/tshirtecommerce/addons/install.';
				}
			}
			else
			{
				$data['error'] = 'Please add your key and upload file install!';
			}
		}		
		
		$data['upload'] = $upload;
		
		$data['title'] = lang('breadcrumb_install', true);
		$data['breadcrumb'] = lang('breadcrumb_install', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		$this->view('install', $data);
	}
	
	public function installed()
	{	
		$path 	= dirname(ROOT).DS.'addons'.DS.'install';
		
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg 	= new dg();

		$addons_package 	= $this->_addons_package();
		
		$addons = array();
		$keys = array();
		if(file_exists($path))
		{
			if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
					if(!in_array($entry, array(".","..")) && file_exists($path.DS.$entry) && strpos($path.DS.$entry, '.json'))
					{
						$product_id 	= str_replace('.json', '', $entry);
						$file 			= $dg->readFile($path.DS.$entry);
						$addon 			= json_decode($file);
						if (isset($addon->name))
						{
							$addon->new_version = $addon->version;
							$addon->product_id = $product_id;
							
							if (isset($addon->key))
							{
								if($addon->key != '')
								{
									$args = array(
										'woo_sl_action'	=> 'plugin_update',
										'licence_key'	=> $addon->key,				
										'product_unique_id'	=> $product_id,				
										'domain'	=> $_SERVER['HTTP_HOST']
									);
									
									$result = $dg->sendPostData($this->api_url, $args);
									
									if ($result != false && $result != '')
									{
										$content = json_decode($result);
										
										if (isset($content[0]) && isset($content[0]->status) && isset($content[0]->message) && $content[0]->status == 'success')
										{
											if (isset($content[0]->message->new_version))
											{
												$addon->new_version = $content[0]->message->new_version;
											}										
										}
									}
								}
								else
								{
									$addon->file = $path.DS.$entry;
								}
								
								$addons[] = $addon;
							}
							else
							{
								if(isset($addons_package[$product_id]))
								{
									$addon->new_version = $addons_package[$product_id]['version'];
								}
								$addons[] 	= $addon;
							}
						}
					}
				}
			}
		}
				
		$data['addons'] 		= $addons;
		
		$data['title'] 			= lang('breadcrumb_installed', true);
		$data['breadcrumb'] 	= lang('breadcrumb_installed', true);
		$data['sub_title'] 		= lang('breadcrumb_manager', true);
		
		$this->view('installed', $data);
	}
	
	public function update($product_id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$path_info = dirname(ROOT) .DS. 'addons' .DS. 'install' .DS. $product_id.'.json';
		
		if($product_id != '' && file_exists($path_info))
		{
			$content = file_get_contents($path_info);
			if ($content != false)
			{
				$addon = json_decode($content);
				
				$args = array(
					'woo_sl_action'	=> 'plugin_update',
					'licence_key'	=> $addon->key,				
					'product_unique_id'	=> $product_id,				
					'domain'	=> $_SERVER['HTTP_HOST']
				);
				
				$result = $dg->sendPostData($this->api_url, $args);
				
				if ($result != false && $result != '')
				{
					$content = json_decode($result);				
					if (isset($content[0]) && isset($content[0]->status) && isset($content[0]->message) && $content[0]->status == 'success')
					{
						if (isset($content[0]->message->package))
						{
							$addon->version = $content[0]->message->new_version;
							$addon->date = $content[0]->message->date;							
							
							// download and upzip file
							$file 		= $dg->openURL($content[0]->message->package);
							$zip 			= new ZipArchive;
							
							$path 		= dirname(dirname(ROOT));
							$path_file 	= $path .DS. 'addon.zip';
							if($dg->WriteFile($path_file, $file) && $zip->open($path_file) == true)
							{
								$zip->extractTo($path);
								$zip->close();
								unlink($path_file);
								
								$dg->WriteFile($path_info, json_encode($addon));								
							}
						}									
					}
				}
			}
		}		
		$dg->redirect('index.php/addons_ex/installed');
	}

	public function remove($product_id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$path_file = dirname(ROOT) .DS. 'addons' .DS. 'remove' .DS. $product_id.'.json';
		
		if($product_id != '' && file_exists($path_file))
		{
			// remove addons.
			$list_file = file_get_contents($path_file);
			$list_files = json_decode($list_file);
			if(is_array($list_files))
			{
				foreach($list_files as $key=>$val)
				{
					$val = str_replace('/', DS, $val);
					$path = dirname(ROOT).DS.$val;
					if(file_exists($path))
					{
						unlink($path);
					}
				}
			}
		}
		
		$dg->redirect('index.php/addons_ex');
	}

	public function _addons_package()
	{
		$dg 		= new dg();
		$url 		= $this->addon_package.'addons/addons.json';

		$content 	= $dg->openURL($url);

		$data 	= array();
		if($content != false)
		{
			$rows = json_decode($content, true);
			for($i=0; $i<count($rows); $i++)
			{
				$data[$rows[$i]['id']] = $rows[$i]; 
			}
		}
		return $data;
	}
}
?>