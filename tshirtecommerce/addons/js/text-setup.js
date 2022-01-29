jQuery(document).on('before.add.text.design', function(event, txt) {
	if(event.namespace != 'add.design.text')
	{
		return false;
	}

	txt.text  = txt_text_default;
	txt.color = '#' + txt_color_default.replace('#', '');
});

jQuery(document).on('product.change.design', function(event, p) {
	if(event.namespace == 'change.design');
	{
		txt_text_default = txt_text_global;
		txt_color_default = txt_color_global;

		if (typeof p.textdefault_attribute !== 'undefined') {
			txt_text_default = p.textdefault_attribute;
		}
		if (typeof p.colordefault_attribute !== 'undefined') {
			txt_color_default = p.colordefault_attribute;
		}
	}	
});