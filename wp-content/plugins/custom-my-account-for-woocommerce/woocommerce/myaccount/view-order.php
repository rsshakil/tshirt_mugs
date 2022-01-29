<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version   2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>
<div id="phoen-wcmap-wrap" class="phoen-wcmap woocommerce">
<div class="phoen-wcmap-row">
<div class="phoen-myaccount-menu <?php if($row1['menu_style']!='sidebar'){?> pho-horizontal<?php } ?>">
<ul class="myaccount-menu">
<li><a href="<?php echo get_site_url().'/my-account'?>?temp=dashboard">Dashboard</a></li>
<li><a href="<?php echo get_site_url().'/my-account'?>?temp=downloads">My Downloads</a></li>
<li><a href="<?php echo get_site_url().'/my-account'?>?temp=orders">My Orders</a></li>
<li><a href="<?php echo get_site_url().'/my-account'?>?temp=edit_account">Edit Account</a></li>
<li><a href="<?php echo get_site_url().'/my-account'?>?temp=my_address">Edit Address</a></li>
</ul>
</div>
<div class="phoen-myaccount-content <?php if($row1['menu_style']!='sidebar'){?> pho-horizontal<?php } ?>">
<p class="order-info"><?php printf( __( '注文番号 #<mark class="order-number">%s</mark>は <mark class="order-date">%s</mark> に発注され 現在 <mark class="order-status">%s</mark>.', 'woocommerce' ), $order->get_order_number(), date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ), wc_get_order_status_name( $order->get_status() ) ); ?></p>

<?php if ( $notes = $order->get_customer_order_notes() ) :
	?>
	<h2><?php _e( 'Order Updates', 'woocommerce' ); ?></h2>
	<ol class="commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="comment note">
			<div class="comment_container">
				<div class="comment-text">
					<p class="meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
					<div class="description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
					</div>
	  				<div class="clear"></div>
	  			</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
	<?php
endif;

do_action( 'woocommerce_view_order', $order_id );
?>
</div>
</div>
</div>
