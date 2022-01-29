<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-21
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

 $dg = new dg();
 
 $settings = $dg->getSetting();
 if(!isset($data->show_price))
 {
	 if(isset($settings->show_price))
		$data->show_price = $settings->show_price;
	 else
		 $data->show_price = 1;
 }
 if(!isset($data->btn_price))
 {
	 if(isset($settings->btn_price))
		$data->btn_price = $settings->btn_price;
	 else
		 $data->btn_price = 1;
 }
 if(!isset($data->btn_add_cart))
 {
	 if(isset($settings->btn_add_cart))
		$data->btn_add_cart = $settings->btn_add_cart;
	 else
		 $data->btn_add_cart = 1;
 }
?>
<hr>
<div class="form-group">
	<div class="col-sm-12">
		<strong><?php echo $addons->__('addon_setting_submit_price_title'); ?></strong>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_setting_submit_price_show_price'); ?></label>
	<div class="col-sm-4">
		<label class="radio-inline">
			<input type="radio" value="1" name="product[show_price]" <?php if(isset($data->show_price) && $data->show_price == 1) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_yes_title'); ?>
		</label>
		<label class="radio-inline">
			<input type="radio" value="0" name="product[show_price]" <?php if(empty($data->show_price)) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_no_title'); ?>
		</label>
	</div>				
</div>

<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_setting_submit_price_show_button_submit_price'); ?></label>
	<div class="col-sm-4">
		<label class="radio-inline">
			<input type="radio" value="1" name="product[btn_price]" <?php if(isset($data->btn_price) && $data->btn_price == 1) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_yes_title'); ?>
		</label>
		<label class="radio-inline">
			<input type="radio" value="0" name="product[btn_price]" <?php if(empty($data->btn_price)) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_no_title'); ?>
		</label>	
	</div>				
</div>

<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_setting_submit_price_show_button_add_cart'); ?></label>
	<div class="col-sm-4">
		<label class="radio-inline">
			<input type="radio" value="1" name="product[btn_add_cart]" <?php if(isset($data->btn_add_cart) && $data->btn_add_cart == 1) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_yes_title'); ?>
		</label>
		<label class="radio-inline">
			<input type="radio" value="0" name="product[btn_add_cart]" <?php if(empty($data->btn_add_cart)) echo 'checked=""'; ?>>
			<?php echo $addons->__('addon_no_title'); ?>
		</label>		
	</div>				
</div>