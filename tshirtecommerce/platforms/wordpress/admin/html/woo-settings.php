<div class="form-field options_group p9f-form-field">
	<label><b>Designer Settings</b></label>
	<span class="p9f-settings">
		<p class="field-p">
			<?php
			$checkbox = '';
			if( isset($settings['open_designer']) && $settings['open_designer'] == 1 )
				$checkbox = 'checked="checked"';
			?>
			<label>
				<input type="checkbox" name="product_designer_settings[open_designer]" <?php echo $checkbox; ?>> Open page design when click link product <?php echo wc_help_tip("Note: some theme not support this option."); ?>
			</label>
		</p>
	</span>
</div>
<script type="text/javascript">
var url_product_design = '<?php echo admin_url('admin.php?page=online_designer&task=product/child'); ?>';
</script>