<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: November 26 2015
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
 $product = $GLOBALS['product']; 
?>

<script type='text/javascript'>
	var allow_change_printing_type 	= <?php echo (isset($product->allow_change_printing_type) ? 1 : 0) ?>;
	var allow_show_printing_type 	= <?php echo (isset($product->allow_show_printing_type) ? 1 : 0) ?>;
	var allow_screen_printing 		= <?php echo (isset($product->allow_screen_printing) ? 1 : 0) ?>;
	var allow_dtg_printing 			= <?php echo (isset($product->allow_dtg_printing) ? 1 : 0) ?>;
	var allow_sublimation_printing	= <?php echo (isset($product->allow_sublimation_printing) ? 1 : 0) ?>;
	var allow_embroidery_printing	= <?php echo (isset($product->allow_embroidery_printing) ? 1 : 0) ?>;
</script>

