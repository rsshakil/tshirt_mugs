<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: March 26, 2019
 *
 * @copyright  Copyright (C) 2016 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

$addons->path_data = dirname(ROOT) .DS. 'data';
$setting = $addons->getSetting();

$text_global = isset($setting->txtDefaultVal) ? $setting->txtDefaultVal : 'Hello';
$color_global = isset($setting->text_color) ? $setting->text_color : 'FF0000';

$text_private = isset($data->textdefault_attribute) ? $data->textdefault_attribute : $text_global;
$color_private = isset($data->colordefault_attribute) ? $data->colordefault_attribute : $color_global;

?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/plugins/pickColor/spectrum.css'); ?>">
<script type='text/javascript' src='<?php echo site_url('assets/plugins/pickColor/spectrum.js'); ?>'></script>
<hr>
<div class="form-group">
	<label class="col-sm-3 control-label">
		<?php echo $addons->__('addon_text_setup_title'); ?>
	</label>
	<div class='col-sm-4'>
		<input type="text" id="productTextValDefault" class="form-control input-sm" name='product[textdefault_attribute]' value="<?php echo $text_private ?>">
		<span class="help-block"><small><?php echo $addons->__('addon_text_setu_help'); ?></small></span>
	</div>
	<div class='col-sm-2'>
		<input type="text" id="productTextColorDefault" class="colors" value="<?php echo $color_private ?>" name="product[colordefault_attribute]">
	</div>
</div>
<script type='text/javascript'>	
jQuery(document).ready(function(){
	jQuery(".colors").spectrum({
		showPalette: true,
		showInput: true,
		preferredFormat: "hex",
		palette: [
			['FFFFFF', 'FCFCFC', 'CCCCCC', '333333'],
			['000000', '428BCA', 'F65E13', '2997AB'],
			['5CB85C', 'D9534F', 'F0AD4E', '5BC0DE'],
			['C3512F', '7C6853', 'F0591A', '2D5C88'],
			['4ECAC2', '435960', '734854', 'A81010'],
		]
	});
});
</script>