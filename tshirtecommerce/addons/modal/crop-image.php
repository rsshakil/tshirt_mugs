<?php $addons = $GLOBALS['addons']; ?>
<input type="text" id="cropActionLabel" value="<?php echo $addons->__('addon_cropimage_cropAction_en'); ?>" style="display: none"/>
<script type="text/html" id="crop-toolbar-layout">
	<div class="btn-group btn-group-lg">
		<button type="button" class="btn btn-default" onclick="design.tools.crop.done();" title="<?php echo $addons->__('addon_cropimage_cropAction_en'); ?>"><i class="glyph-icon flaticon-12 flaticon-checked"></i></button>
		<button type="button" class="btn btn-default" onclick="design.tools.crop.close();" title="<?php echo $addons->__('addon_cropimage_closeAction_en'); ?>"><i class="glyph-icon flaticon-12 flaticon-cross"></i></button>
	</div>
</script>