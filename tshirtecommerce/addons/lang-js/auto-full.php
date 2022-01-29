<?php
$product 		= $GLOBALS['product'];
$art_full_area 	= 0;
if(isset($product->art_full_area) && $product->art_full_area)
{
	$art_full_area = 1;
}
?>
<script type="text/javascript">
	var art_full_area = '<?php echo $art_full_area; ?>';
</script>