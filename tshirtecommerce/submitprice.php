<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE
 *
 */

error_reporting(0);
date_default_timezone_set('America/Los_Angeles');
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

const ALLOW_SEND_ADMIN 		= 1;
const TEXT_SUBJECT 			= 'Submit price of product design!';
const EMAIL_ADMINISTRATOR 	= 'sales@tshirtecommerce.com';
const TEXT_MESSAGE 			= '<p>Hi {name}, </p><p>Thanks you for design!</p><p>You have a new design: {url}</p><p>Price: {price}</p><p>Price: {tax}</p><p>Quantity: {quantity}</p><p>Attribute: {attribute}</p><p>Email: {email}</p><p>Phone: {phone}</p><p>Address: {address}</p></br><p>Thanks, Tshirtecommerce Teams!</p>';

include_once ROOT .DS. 'includes' .DS. 'functions.php';
include (ROOT .DS. 'includes' .DS. 'addons.php');
$addons = new addons();

// Get product data.
$file 		= ROOT .DS. 'data' .DS. 'submitpricedatas.json';
$dgClass  	= new dg();
$products 	= $dgClass->getProducts();
$settings 	= $dgClass->getSetting();

$data = file_get_contents('php://input');
$data = json_decode($data, true);

// set data.
$product_id 	= isset($data['product_id']) ? $data['product_id'] : '';
$name 			= isset($data['name']) ? $data['name'] : '';
$email 			= isset($data['email']) ? $data['email'] : '';
$phone 			= isset($data['phone']) ? $data['phone'] : '';
$address 		= isset($data['address']) ? $data['address'] : '';
$link 			= isset($data['link']) ? $data['link'] : '';
$download 		= isset($data['download']) ? $data['download'] : array();
$price 			= isset($data['price']) ? $data['price'] : 0;
$thumb 			= isset($data['thumb']) ? $data['thumb'] :'';
$quantity 		= isset($data['quantity']) ? $data['quantity'] : 1;
$attributes 	= isset($data['attribute']) ? $data['attribute'] : array();
$color 			= isset($data['color']) ? $data['color'] : '';
$color_title 	= isset($data['color_title']) ? $data['color_title'] : '';
$note 			= isset($data['note']) ? $data['note'] : '';

$tax 			= 0;
$tax_name 		= '';
// TODO for ps and oc

$send_admin 	= isset($settings->send_email_price) ? $settings->send_email_price : ALLOW_SEND_ADMIN;
$admin_email 	= isset($settings->email_price) ? $settings->email_price : EMAIL_ADMINISTRATOR;
$subject 		= isset($settings->submit_price_subject) ? $settings->submit_price_subject : TEXT_SUBJECT;
$message 		= isset($settings->config_email_price) ? $settings->config_email_price : TEXT_MESSAGE;

$submit_prices = '{}';
if (file_exists($file)) {
	$submit_prices = $dgClass->openURL($file);
} else {
	$dgClass->writeFile($file, '{}');
}
$submit_prices = json_decode($submit_prices, true);

$price_id = 1;
if (count($submit_prices)) {
	foreach ($submit_prices as $value) {
		if ($value['id'] > $price_id) {
			$price_id = $value['id'];
		}
	}
	$price_id = $price_id + 1;
}

$submitdata = array();
foreach ($products as $product) {
	if ($product->id == $product_id) {
		$attribute = '<span class="tooltips" style="background: #'.$color.'; display: block; width: 25px; height: 25px; margin: 0px auto; border: 1px solid #ccc;" title="'.$color_title.'"></span>';
		$i = 0;
		// get attribute.
		foreach ($attributes as $key=>$val) {
			if ($i != 0) {
				$attribute .= '</br>';
			}
			
			$attribute .= '<strong>'.$product->attributes->name[$key].': </strong>';
			foreach ($product->attributes->titles[$key] as $k=>$v) {
				if (isset($val[$k]) && $product->attributes->type[$key] == 'textlist') {
					if (empty(trim($val[$k]))) {
						$val[$k] = 0;
					}
					$attribute .= $v.'-'.$val[$k].'; ';
				} elseif (isset($val[$k])) {
					$attribute .= $v.'; ';
				}
			}
			$i++;
		}
		
		// get tax.
		$filetax = ROOT .DS. 'data' .DS. 'taxes.json';
		if (file_exists($filetax)) {
			$taxes 		= array();
			$taxdata 	= $dgClass->openURL($filetax);
			$taxes 		= json_decode($taxdata);

			if (count($taxes) && isset($product->tax)) {
				foreach ($taxes as $val) {
					if ($val->id == $product->tax) {
						$tax = $val->value;
						$tax_name = $val->title;
					}
				}
			}
		}
		
		$submitdata['id'] 			= $price_id;
		$submitdata['product_id'] 	= $product_id;
		$submitdata['name'] 		= $name;
		$submitdata['email'] 		= $email;
		$submitdata['phone'] 		= $phone;
		$submitdata['title'] 		= $product->title;
		$submitdata['price'] 		= $price;
		$submitdata['address'] 		= $address;
		$submitdata['quantity'] 	= $quantity;
		$submitdata['attribute'] 	= $attribute;
		$submitdata['tax'] 			= $tax_name.': '.$tax;
		$submitdata['note'] 		= $note;
		$submitdata['url'] 			= $link;
		$submitdata['download'] 	= $download;
		$submitdata['thumb'] 		= $thumb;
		$submitdata['status'] 		= 0;
		$submitdata['date'] 		= date('Y-m-d H:i:s');
	}
}

// save product data.
$msg 		= $addons->lang['addon_setting_submit_price_success_email_msg'];
$msg_email 	= $addons->lang['addon_setting_submit_price_success_msg'];
$error 		= $addons->lang['addon_setting_submit_price_error_msg'];

$res = array(
	'error'=> 1,
	'msg'=> $error
);

if (count($submitdata)) {
	$submit_prices[] = $submitdata;
	$datainput = json_encode($submit_prices);

	if ($dgClass->writeFile($file, $datainput)) {
		$res['error'] = 0;
		$res['msg'] = $msg_email;

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: '.$admin_email;
		
		//get content email.
		$message = str_replace('{name}', $name, $message);
		$message = str_replace('{url}', $link, $message);
		$message = str_replace('{email}', $email, $message);
		$message = str_replace('{phone}', $phone, $message);
		$message = str_replace('{address}', $address, $message);
		$message = str_replace('{price}', $price, $message);
		$message = str_replace('{tax}', $tax, $message);
		$message = str_replace('{attribute}', $attribute, $message);
		$message = str_replace('{quantity}', $quantity, $message);

		if (mail($email, $subject, $message, $headers)) {
			$res['msg'] = $msg;
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: '.$email;

			if ($send_admin == 1) {
				mail($admin_email, $subject, $message, $headers);
			}
		}
	}
}

echo json_encode($res);
return;