var product_url = siteURL+'sharing.php/';
design.products.getURL = function()
{
	if (typeof design.platform === 'undefined' || design.platform == 'wordpress') {
		var url_ajax_product = 'wp-admin/admin-ajax.php?action=woo_product_url';
		var datas 	= {};
		datas.post_id = parent_id;
		if(typeof variation_id != 'undefined' && variation_id != 0)
		{
			datas.variation_id = variation_id;
		}
		jQuery.ajax({
			type: "GET",
			url: mainURL + url_ajax_product,
			data: datas
		}).done(function( data_url ) {
			if(typeof data_url != 'underline' && data_url != '')
			{
				product_url = data_url;
			}
		});
	}
	// TODO if platform is presta or opencart do nothing here
}

design.products.variations = {
	data: [],
	load: function(){
		jQuery('.variations').remove();
		if (typeof design.platform === 'undefined' || design.platform == 'wordpress') {
			var ajax_url = 'wp-admin/admin-ajax.php?action=p9f_product_variations';
			var datas 	= {};
			datas.product_id = parent_id;
			if(typeof variation_id != 'undefined' && variation_id != 0)
			{
				datas.variation_id = variation_id;
			}
			jQuery.ajax({
				type: "GET",
				url: mainURL + ajax_url,
				data: datas
			}).done(function( content ) {
				var div = document.createElement('div');
				div.innerHTML = content;
				jQuery(div).find('.variations').clone().prependTo('#product-attributes');
				
				if (jQuery(div).find('#woo-variations-data').length && jQuery(div).find('#woo-variations-data').html() != '') {
					design.products.variations.data = jQuery.parseJSON(jQuery(div).find('#woo-variations-data').html());
				} else {
					design.products.variations.data = [];
				}

				if(jQuery(div).find('.variations').length > 0)
				{
					design.products.variations.update();
				}
			});
		}
		// TODO if platform is presta or opencart do nothing here
	},
	update: function(){
		var attributes = [];
		if(typeof variation_attributes != 'undefined' && variation_attributes != '')
		{
			var str = variation_attributes.split(';');
			for(var i=0; i<str.length; i++)
			{
				var option = str[i].split('|');
				attributes[option[0]] = option[1];
			}
		}
		else if(variation_id != undefined && this.data[variation_id] != undefined)
		{
			var data = this.data[variation_id];
			jQuery.each(data.attributes, function(key, value) {
				attributes[key] = value;
			});
		}
		jQuery('.variations select').each(function(){
			if(jQuery(this).hasClass('form-control') == false)
			{
				jQuery(this).addClass('form-control dg-poduct-fields required');
			}
			var name = jQuery(this).attr('name');
			if(typeof attributes[name] != 'undefined')
			{
				jQuery(this).val(attributes[name]);
			}
			jQuery(this).on('change', function(){
				design.products.variations.getID();
			});
		});
		attribute_required();
		design.products.variations.getID();
	},
	getID: function(){
		var attributes = {};
		jQuery('.variations select').each(function(){
			var name 	= jQuery(this).attr('name');
			var value 	= jQuery(this).val();
			attributes[name] = value;
		});
		var woo_variation_active_id = '';
		var variations = this.data;
		jQuery.each(variations, function(v_id, options) {
			var check = true;
			jQuery.each(options.attributes, function(index, value) {
				if( typeof attributes[index] == 'undefined' || ( typeof attributes[index] != 'undefined' && attributes[index] != value) )
				{
					check = false;
				}
			});
			if(check == true)
			{
				woo_variation_active_id = v_id;
			}
		});
		if(woo_variation_active_id != '')
		{
			variation_id = woo_variation_active_id;
			variation_active_id = woo_variation_active_id;
			var str = '', title = '';
			jQuery.each(attributes, function(key, value) {
				if(str == '')
					str = key+'|'+value;
				else
					str = str +';'+ key+'|'+value;
				title = title +' - '+value;
			})
			variation_attributes = str;

			var woo_product = variations[variation_id];
			max_order = woo_product.max_qty;
			design.attribute.product(null, woo_product.design_id);
		}
		else
		{
			design.attribute.product(null);
		}
	},
}
var user_design_loaded = false;
design.user_design = function(){
	if(user_design_loaded == true) return;
	if(typeof design_idea_id == 'undefined') return;

	var url_ajax_product 	= 'wp-admin/admin-ajax.php?action=user_edit_design';
	user_design_loaded 	= true;
	jQuery.ajax({
		type: "GET",
		url: mainURL + url_ajax_product,
		dataType: 'json',
		data: { design_id: design_idea_id }
	}).done(function( data ) {
		if(typeof data.vectors != 'undefined')
		{
			design.item.unselect();
			design.tools.reset(null, false);
			design.imports.vector(JSON.stringify(data.vectors));
			setTimeout(function(){
				design.selectAll();
				design.fitToAreaDesign();
				design.item.updateSizes();
				if(typeof menu_options != 'undefined')
				{
					menu_options.show('layers');
				}
			}, 1000);
		}
	});
}

design.item.updateSizes = function(){
	design.item.unselect();
	jQuery('.labView.active .drag-item').each(function(){
		var e 	= jQuery(this);
		
		var text 	= e.find('text');
		if(typeof text[0] != 'undefined')
		{
			var width 	= e.outerWidth();
			var size 	= text[0].getBoundingClientRect();
			var change_size = width - size.width;
			if(change_size > 3)
			{
				var height 		= e.outerHeight();
				var position 	= e.position();

				var svg 	= e.find('svg');
				var viewBox = svg[0].getAttributeNS(null, 'viewBox');
				var options = viewBox.split(' ');
				var view_w 	= (size.width * options[2])/width;
				var view_h 	= (size.height * options[3])/height;

				var new_viewbox = options[0] +' '+ options[1] +' '+ view_w +' '+ view_h;
				svg[0].setAttributeNS(null, 'viewBox', new_viewbox);
				design.item.setSize(this, size.width, size.height);
			}
		}
	});
}

jQuery(document).on('done.imports.design', function(){
	design.user_design();
});

jQuery(document).on('before.product.change.design', function(event, product) {
	if(design.products.variations.data[variation_active_id] != undefined)
	{
		var data = design.products.variations.data[variation_active_id];
		product.sku = data.sku;
		product.min_order = data.min_qty;
		product.max_order = data.max_qty;
		product.price = data.price;
		if(product.image == '')
		{
			product.image = data.image;
		}
	}
});

jQuery(document).on('ini.design', function(){
	jQuery.ajax({
		url: siteURL+'platform.php',
		dataType: 'text',
		type: 'get',
		success: function(res) {
			design.platform =res;
			
			design.products.getURL();
			design.products.variations.load();
		}
	});
});
jQuery(document).on('product.change.design', function(event, product){
	if(typeof product.child_id == 'undefined')
	{
		design.products.variations.data = [];
		variation_active_id = 0;
		variation_id = 0;
		variation_attributes = '';
		design.products.getURL();
		design.products.variations.load();
	}
});