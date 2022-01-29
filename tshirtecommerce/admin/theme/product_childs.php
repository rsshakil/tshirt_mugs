<div class="modal fade" id="products-child">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Product Variation</h4>
			</div>
			<div class="modal-body">
				<?php if( count($child) ){?>
					<div class="row">
					<?php foreach($child as $row){ ?>
						<div class="col-md-3 col-sm-4 col-xs-2 text-center">
							<a href="javascript:void(0);" class="img-thumbnail" onclick="add_product_variable(this, <?php echo $row['id']; ?>);">
								<img src="<?php echo imageURL($row['image']); ?>" alt="<?php echo $row['title']; ?>" class="img-responsive" width="150">
								<br />
								<center><?php echo $row['title']; ?></center>
							</a>
						</div>
					<?php } ?>
					</div>
				<?php }else{ ?>
				<p>Product not found</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>