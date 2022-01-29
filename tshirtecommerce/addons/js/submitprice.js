function saveDesign()
{	
	var datas = design.ajax.form();
	design.mask(true);
	jQuery.ajax({
		type: "POST",
		processData: false,
		dataType: "json",
		url: siteURL + "ajax.php?type=prices",
		data: JSON.stringify(datas),				
		contentType: "application/json; charset=utf-8",
	}).done(function( price ) {
	
		if(typeof price.sale != 'undefined')
		{
			var vectors	= JSON.stringify(design.exports.vector());		
			var image = design.output.front.toDataURL();
			
			var teams = JSON.stringify(design.teams);
			var productColor = design.exports.productColor();
			var data = {
				"image":image, 
				'vectors':vectors,			
				'teams':teams,
				'fonts': design.fonts,
				'product_id':product_id,
				'parent_id':parent_id,
				'design_id':design.design_id,
				'design_file':design.design_file,
				'designer_id':design.designer_id,
				'design_key':design.design_key,
				'product_color':productColor
			};
			
			jQuery(document).triggerHandler( "before.save.design", data);
			if (typeof design.platform !== 'undefined' && design.platform == 'prestashop') {
				jQuery(document).triggerHandler( "before.prestashop.quote", data);
			} else if (typeof design.platform !== 'undefined' && design.platform == 'opencart') {
				jQuery(document).triggerHandler( "before.opencart.quote", data);
			} else {
				jQuery.ajax({
					url: siteURL + "ajax.php?type=saveDesign",
					type: "POST",
					contentType: 'application/json',
					data: JSON.stringify(data),
				}).done(function( msg ) {
					var results = eval ("(" + msg + ")");
					
					if (results.error == 1)
					{
						alert(results.msg);
					}
					else
					{
						if(product_url != undefined)
							var linkEdit = product_url + results.content.user_id+':'+results.content.design_key+':'+product_id+':'+productColor+':'+parent_id;
						else
							var linkEdit = siteURL + 'sharing.php/'+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+productColor+':'+parent_id;

						var linkDownloads = {};
						var link = siteURL+'design.php?key='+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+productColor+':'+parent_id+'&view=';
						linkDownloads.front = link + 'front';
						if (jQuery('#view-back .product-design').html() != '' && jQuery('#view-back .product-design').find('img').length > 0)
							linkDownloads.back = link + 'back';
						if (jQuery('#view-left .product-design').html() != '' && jQuery('#view-left .product-design').find('img').length > 0)
							linkDownloads.left = link + 'left';
						if (jQuery('#view-right .product-design').html() != '' && jQuery('#view-right .product-design').find('img').length > 0)
							linkDownloads.right = link + 'right';
						sendEmailPrice(linkEdit, linkDownloads, price.sale, datas.attribute, datas.quantity, datas.product_id, results.content.design_file, productColor);	
					}
				});
			}
		}
	});
}

function submitPriceModal()
{
	var quantity = jQuery('#quantity').val();
		quantity = parseInt(quantity);
	if (quantity == NaN || quantity < 1)
	{
		alert(lang.designer.quantity);
		return false;
	}
	if (quantity < min_order){
		alert(lang.designer.quantityMin +' '+min_order+'. '+lang.designer.quantity);
		return false;
	}
	
	if (user_id == 0)
	{
		jQuery('#f-login').modal();
	}
	else
	{
		jQuery('.submit-price-modal').modal('show');
	}
}

function submitPrice()
{
	var check = checkForm();
	
	if(check == true)
	{
		design.design_id = 0;								
		design.design_key = '';
		design.design_file = '';
		design.svg.items('front', saveDesign);
	}
}

function sendEmailPrice(url, linkDownloads, price, attribute, quantity, product_id, image, productColor)
{
	var color_title = jQuery('#product-list-colors span.active').data('original-title');
	var data = {thumb: image, link: url, download: linkDownloads, price: price, attribute: attribute, quantity: quantity, product_id: product_id, color: productColor, color_title: color_title};
	var datas = jQuery('#fr-submit-price').serializeObject();
	jQuery.extend(datas, data);
	jQuery.ajax({
		url: siteURL + "submitprice.php",
		type: "POST",
		contentType: 'application/json',
		data: JSON.stringify(datas),
	}).done(function( msg ) {
		var results = eval ("(" + msg + ")");
		if (results.error == 1)
		{
			alert(results.msg);
		}else
		{
			jQuery('#dg-mask').css('display', 'none');
			jQuery('#dg-designer').css('opacity', '1');
			jQuery('.submit-price-modal').modal('hide');
			alert(results.msg);
		}
	});
}

function checkForm()
{
	var check = true;
	jQuery('#fr-submit-price').find('input, textarea').each(function()
	{
		var value = jQuery(this).val();
		if(check == true)
		{
			// check required
			if(value == '')
			{
				check = false;
			}
			
			// check Min length
			var minlength = jQuery(this).data('minlength');
			if(value.length < minlength){
				check = false;
			}
				
			// check Max length
			var maxlength = jQuery(this).data('maxlength');
			if(value.length > maxlength){
				check = false;
			}
			
			// check type
			var type = jQuery(this).data('type');
			switch(type){
				case 'email':
					if(email(value) == false)
						check = false;
					break;
				
				case 'number':
					if(number(value) == false)
						check = false;
					break;
			}
			
			if(check == false)
			{
				jQuery(this).css({'border':'1px solid #FF0000', 'color':'#FF0000'});
				
				var msg = jQuery(this).data('msg');
				if(typeof msg != 'undefined') alert(msg);
				if(typeof e != 'undefined')	e.preventDefault();
				
				return false;
			}else
			{
				jQuery(this).css({'border':'1px solid #cccccc', 'color':'#555555'});
				return true;
			}
		}
	});
	return check;
}

function email(value){
	filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(value)) {
		return true;
	}
	else{
		return false;
	}
}

function number(value){
	filter = /^[0-9]+$/;
	if (filter.test(value)) {
		return true;
	}
	else{
		return false;
	}
}

jQuery(document).on("change.product.design", function(event, product)
{
	if (typeof event.namespace == 'undefined' || event.namespace != 'design.product') return;
	
	var showprice = show_price;
	var btnprice = btn_price;
	var btnaddcart = btn_add_cart;
	if(typeof product.show_price != 'undefined')
		showprice = product.show_price;
	if(typeof product.btn_price != 'undefined')
		btnprice = product.btn_price;
	if(typeof product.btn_add_cart != 'undefined')
		btnaddcart = product.btn_add_cart;
	
	if(showprice != 1)
		jQuery('#product-price').hide();
	else
		jQuery('#product-price').show();
	
	if(btnprice != 1)
		jQuery('.btn-submit-price').hide();
	else
		jQuery('.btn-submit-price').show();
	
	if(btnaddcart != 1)
		jQuery('.btn-addcart').hide();
	else
		jQuery('.btn-addcart').show();
});

jQuery(document).on( "before.opencart.quote", function(event, qdata) {
	var datas = design.ajax.form();
	var _product_id_oc = window.parent._product_id_oc;
	var options_oc = jQuery('#oc_tool_cart').serializeObject();
	var datas_oc = jQuery.extend(datas, options_oc);
	datas_oc._product_id_oc = _product_id_oc;

	var str_option_oc = '{';
	if (datas_oc.option_oc != undefined && datas_oc.option_oc != '') {
		for (var rw in datas_oc.option_oc) {
			if (datas_oc.option_oc.hasOwnProperty(rw)) {
				str_option_oc += '"'+rw+'":"'+datas_oc.option_oc[rw]+'",';
			}
		}
	}
	str_option_oc += '}';
	datas_oc.option_oc = str_option_oc;

	jQuery.ajax({
		type: "POST",
		dataType: "json",
		url: mainURL + url_ajax_get_prices_oc,
		data: { 'dataoc' : JSON.stringify(datas_oc) }
	}).done(function(data_oc) {
		var price_option_obj = { 
			price_option_oc: data_oc.price, 
			price_old_oc: data_oc.price_old, 
			price_sale_oc: data_oc.price_sale, 
			price_taxes: data_oc.taxes, 
			price_eco: data_oc.eco
		};
		datas = jQuery.extend(datas, price_option_obj);
		var lable = jQuery('#product-price .product-price-title');
		var div = jQuery('#product-price .product-price-list');
		var title = '';
		lable.html('Updating...');
		jQuery.ajax({
			type: "POST",
			processData: false,
			dataType: "json",
			url: siteURL + "ajax.php?type=prices",
			data: JSON.stringify(datas),
			contentType: "application/json; charset=utf-8",
		}).done(function( res ) {
			if (res != '') {
				if (typeof res.sale != 'undefined') {
					var price = 0;
	                if (res != "") {
	                    if (typeof res.sale != "undefined") {
	                        price = res.sale;
	                    }
	                }

	                jQuery.ajax({
						url: siteURL + "ajax.php?type=saveDesign",
						type: "POST",
						contentType: 'application/json',
						data: JSON.stringify(qdata),
					}).done(function( msg ) {
						var results = eval ("(" + msg + ")");
						
						if (results.error == 1)
						{
							alert(results.msg);
						}
						else
						{
							var linkEdit = siteURL + 'sharing.php/'+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+qdata.product_color+':'+parent_id;
							var linkDownloads = {};
							var link = siteURL+'design.php?key='+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+qdata.product_color+':'+parent_id+'&view=';
							linkDownloads.front = link + 'front';
							if (jQuery('#view-back .product-design').html() != '' && jQuery('#view-back .product-design').find('img').length > 0)
								linkDownloads.back = link + 'back';
							if (jQuery('#view-left .product-design').html() != '' && jQuery('#view-left .product-design').find('img').length > 0)
								linkDownloads.left = link + 'left';
							if (jQuery('#view-right .product-design').html() != '' && jQuery('#view-right .product-design').find('img').length > 0)
								linkDownloads.right = link + 'right';
							sendEmailPrice(linkEdit, linkDownloads, price, datas.attribute, datas.quantity, datas.product_id, results.content.design_file, qdata.product_color);	
						}
					});
				}
			}
		});
	});
});

jQuery(document).on( "before.prestashop.quote", function(event, qdata) {
	var datas = design.ajax.form();
    var options_ps = jQuery("#tool_cart_ps").serializeObject();
    var datas_ps = jQuery.extend(datas, options_ps);
    jQuery.ajax({
        url: window.parent.baseDir + "modules/tshirtecommerce/ajax.php",
        type: "POST",
        data: {
            method: "prices",
            id: window.parent.ps_product_id,
            lang: window.parent.ps_language_id,
            shop: window.parent.ps_shop_id,
            attributes: datas_ps,
            id_customer: window.parent.ps_id_customer,
            id_currency: window.parent.ps_id_currency,
            id_country: window.parent.ps_id_country,
            id_group: window.parent.ps_id_group,
            quantity: jQuery("#quantity").val(),
        },
        dataType: "json",
        success: function(json) {
            datas.ps_price_sale = json.price;
            datas.ps_price_old = json.price;
            datas.ps_taxes = json.taxes;
            datas.ps_round_mode = json.round_mode;
            min_order = json.min_order;
            max_order = json.max_order;

            jQuery("#idCombination").val(json.ipa);
            //datas.ps_taxes = json.taxes;
            jQuery.ajax({
                type: "POST",
                processData: false,
                dataType: "json",
                url: siteURL + "ajax.php?type=prices",
                data: JSON.stringify(datas),
                contentType: "application/json; charset=utf-8"
            }).done(function(res) {
            	var price = 0;
                if (res != "") {
                    if (typeof res.sale != "undefined") {
                        price = res.sale;
                    }
                }

                jQuery.ajax({
					url: siteURL + "ajax.php?type=saveDesign",
					type: "POST",
					contentType: 'application/json',
					data: JSON.stringify(qdata),
				}).done(function( msg ) {
					var results = eval ("(" + msg + ")");
					
					if (results.error == 1)
					{
						alert(results.msg);
					}
					else
					{
						var linkEdit = siteURL + 'sharing.php/'+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+qdata.product_color+':'+parent_id;
						var linkDownloads = {};
						var link = siteURL+'design.php?key='+results.content.user_id+':'+results.content.design_key+':'+product_id+':'+qdata.product_color+':'+parent_id+'&view=';
						linkDownloads.front = link + 'front';
						if (jQuery('#view-back .product-design').html() != '' && jQuery('#view-back .product-design').find('img').length > 0)
							linkDownloads.back = link + 'back';
						if (jQuery('#view-left .product-design').html() != '' && jQuery('#view-left .product-design').find('img').length > 0)
							linkDownloads.left = link + 'left';
						if (jQuery('#view-right .product-design').html() != '' && jQuery('#view-right .product-design').find('img').length > 0)
							linkDownloads.right = link + 'right';
						sendEmailPrice(linkEdit, linkDownloads, price, datas.attribute, datas.quantity, datas.product_id, results.content.design_file, qdata.product_color);	
					}
				});
            });
        }
    });
});