<?php 
$addons 	= $GLOBALS['addons'];
$product 	= $GLOBALS['product'];
if (isset($product->design) && isset($product->design->elements)) {
	$elements = str_replace("'", '"', $product->design->elements);
	$elements = json_decode($elements, true);	?>

	<?php if (count($elements) > 0) { ?>
		<div id="eproduct-build-addon" class="row product-elements">
			<div class="col-sm-12">
				<?php foreach($elements as $keyElement => $element) { ?>
					<div class="form-group key-<?php echo $keyElement; ?>">
						<label><?php echo $element['title']; ?></label><br />

						<?php if (count($element['colors'])) { ?>

							<div class="list-colors">
								<?php if( isset($element['colorPick']) && $element['colorPick'] == 1 ) { ?>
									
									<?php foreach ($element['colors'] as $color) { ?>
										<?php 
										if (isset($color['img'])) {
											$thumb = '';
											if(isset($color['thumb']))
											{
												$thumb = 'srcset="'.$color['thumb'].'"';
											}
											$title = '';
											$add_tooltip = '';
											if(isset($color['title']) && $color['title'] != '')
											{
												$add_tooltip = 'dg-tooltip';
												$title = $color['title'];
											}
										 ?>
											<span data-color="img" title="<?php echo $title; ?>" data-id="<?php echo md5($color['img']); ?>" <?php echo $thumb; ?> onclick="design.products.build(this, <?php echo $keyElement; ?>, 'img')" class="bg-colors <?php echo $add_tooltip; ?> option-imgs bg-colors-<?php echo md5($color['img']); ?>"><img src="<?php echo $color['img']; ?>" width="25" height="25"></span>
										<?php } else { ?>
											<span data-id="<?php echo $color['color']; ?>" style="background-color:#<?php echo $color['color']; ?>" data-key="<?php echo $keyElement; ?>" data-title="<?php echo $element['title']; ?>" data-color="<?php echo $color['color']; ?>" title="<?php echo $color['title']; ?>"  class="bg-color-pick dg-tooltip bg-colors bg-colors-<?php echo $color['color']; ?>"></span>
										<?php } ?>
									<?php } ?>

								<?php }else{ ?>
									
									<?php foreach ($element['colors'] as $color) { ?>

										<?php 
										if (isset($color['img'])) {
											$thumb = '';
											if(isset($color['thumb']))
											{
												$thumb = 'srcset="'.$color['thumb'].'"';
											}
											$title = '';
											$add_tooltip = '';
											if(isset($color['title']) && $color['title'] != '')
											{
												$add_tooltip = 'dg-tooltip';
												$title = $color['title'];
											}
										?>
											<span data-color="img" data-id="<?php echo md5($color['img']); ?>" title="<?php echo $title; ?>" onclick="design.products.build(this, <?php echo $keyElement; ?>, 'img')" class="bg-colors <?php echo $add_tooltip; ?> option-imgs bg-colors-<?php echo md5($color['img']); ?>">
												<img src="<?php echo $color['img']; ?>" <?php echo $thumb; ?> width="25" height="25">
											</span>
										<?php } else { ?>
											<span style="background-color:#<?php echo $color['color']; ?>" data-id="<?php echo $color['color']; ?>" data-color="<?php echo $color['color']; ?>" title="<?php echo $color['title']; ?>" onclick="design.products.build(this, <?php echo $keyElement; ?>, '<?php echo $element['title']; ?>')" class="bg-colors dg-tooltip bg-colors-<?php echo $color['color']; ?>"></span>
										<?php } ?>
										
									<?php } ?>

								<?php } ?>
							</div>

						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } else { echo '<div class="row product-elements"></div>'; } ?>
<?php } else { echo '<div class="row product-elements"></div>'; } ?>
<script type="text/javascript">
	var chooseText = '<?php echo $addons->__('addon_product_build_choose') ?>';
	var cancelText = '<?php echo $addons->__('addon_product_build_cancel') ?>';
</script>