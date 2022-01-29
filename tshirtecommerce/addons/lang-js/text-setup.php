<?php
	$settings        = $GLOBALS['settings'];
	$product         = $GLOBALS['product'];

	$txt_text_global = setValue($settings, 'txtDefaultVal', 'Hello');
	$txt_color_global = setValue($settings, 'text_color', '#FF0000');

	$txt_text_default = setValue($product, 'textdefault_attribute', $txt_text_global);
	$txt_color_default = setValue($product, 'colordefault_attribute', $txt_color_global);
?>
<script type="text/javascript">
	var txt_text_global = `<?php echo $txt_text_global ?>`;
	var txt_color_global = `<?php echo $txt_color_global ?>`;

	var txt_text_default = `<?php echo $txt_text_default ?>`;
	var txt_color_default = `<?php echo $txt_color_default ?>`;
</script>