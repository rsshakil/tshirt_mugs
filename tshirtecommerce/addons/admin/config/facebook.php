<h4><?php echo $addons->__('addon_facebook_api_setting_title'); ?></h4>
<div class="form-group row">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_facebook_api'); ?></label>
	<div class="col-sm-6">
		<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['facebook_api'])) echo $data['settings']['facebook_api']; else echo ''; ?>" name="setting[facebook_api]">
	</div>
</div>
<div class="form-group row">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_facebook_secret_setting_title'); ?></label>
	<div class="col-sm-6">
		<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['facebook_secret'])) echo $data['settings']['facebook_secret']; else echo ''; ?>" name="setting[facebook_secret]">
	</div>
</div>
<div class="form-group row">
	<label class="col-sm-3 control-label"><?php echo $addons->__('addon_facebook_version_setting_title'); ?></label>
	<div class="col-sm-6">
		<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['facebook_version'])) echo $data['settings']['facebook_version']; else echo 'v2.5'; ?>" name="setting[facebook_version]">
	</div>
</div>