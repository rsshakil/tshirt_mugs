<?php
if( isset($_GET['product_id']) )
{
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$product_id 	= $_GET['product_id'];

	/* load prent product */
	$dg 			= new dg();
	$products 		= $dg->getProducts();

	$data = array();
	if( count($products) )
	{
		foreach ($products as $i => $product) {
			if($product->id == $product_id)
			{
				$data = $product;
				break;
			}
		}
	}
	if(!isset($data->id)) return '{"error":"1","msg":"Data not found!"}';

	$file 		= ROOT .DS. 'data' .DS. 'products_child.json';
	if( file_exists($file) )
	{
		$content 	= file_get_contents($file);
		if( $content !== false && $content != '' )
		{
			$products = json_decode($content, true);
			if( isset($products[$product_id]) )
			{
				$product_childs = $products[$product_id];
			}
		}
	}
	$product 	= json_decode( json_encode($data), true );

	$products 	= array();
	$products[$product_id.'-0'] = $product;

	if(isset($product_childs))
	{
		foreach ($product_childs as $child_id => $options)
		{
			$products[$product_id.'-'.$child_id] = $product;

			foreach($options as $key => $value)
			{
				if( isset($products[$product_id.'-'.$child_id][$key]) )
				{
					$products[$product_id.'-'.$child_id][$key] = $value;
				}
			}
		}
	}
	echo json_encode($products);
	exit;
}
?>
