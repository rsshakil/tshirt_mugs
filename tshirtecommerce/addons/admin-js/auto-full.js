jQuery(document).on('after.create.item.design', function(event, span) {
	if(art_full_area == 1)
	{
		if(span.item == undefined) return;
		var item = span.item;
		if(item.type == 'clipart' && item.upload == 1)
		{
			design.tools.fullPage();
		}
	}
});
jQuery(document).on('product.change.design', function(event, product){
	if(product.art_full_area == 1)
	{
		art_full_area = 1;
	}
	else
	{
		art_full_area = 0;
	}
});