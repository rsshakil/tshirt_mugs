<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-01
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
	$addons 	= $GLOBALS['addons'];
	$product 	= $GLOBALS['product'];
	$setting 	= $GLOBALS['settings'];
	
	$print_type = '';
	if(isset($product)){
		$print_type = $product->print_type;
	}

	$print_types = $addons->getPrintings();

if (count($print_types)) {
	foreach($print_types as $key => $print) {
		if ($print_type == $key) {
?>

<div class="modal fade printing-type-current-modal" tabindex="-1" role="dialog" id="printTypeModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<?php echo $print['title'] ?>
				</h4>
			</div>
			<div class="modal-body">
				<?php echo (isset($print['description']) ? $print['description'] : '') ?>			
			</div>
		</div>
	</div>
</div>
<?php } } } ?>