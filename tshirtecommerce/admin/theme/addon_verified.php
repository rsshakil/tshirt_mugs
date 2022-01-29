<?php if(isset($data['error'])) { ?>
<div class="alert alert-danger" role="alert"><?php echo $data['error']; ?></div>	
<?php } ?>
<form action="<?php echo site_url('index.php/addons_ex/verified'); ?>" method="POST">
	<p>Please verify your purchased <a href="https://codecanyon.net/item/ultimate-addons-for-custom-product-designer-woocommerce-opencart-prestashop/23438365" target="_blank">ultimate addons</a> to download and update addon.</p>
	<div class="form-group">
		<label for="">Purchase code:</label>
		<input type="text" name="purchase_code" class="form-control" value="<?php echo $data['purchase_code']; ?>" style="width: 300px;">
	</div>
	<p class="help-block"><a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="_bank">Click here</a> for instructions to find your purchase code.</p>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>