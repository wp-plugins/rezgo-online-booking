<div id="cart_right">
	<h1>My Order</h1>
		
		<?
			$cart = $site->getCart();
			
			if(!$cart) {
				
				echo '<div class="cart_empty">There are no items in your order.</div>';
				
			} else {
		?>		
		
		<div id="cart_entered_sidebar" class="cart_entered_sidebar">
      <div class="cart_right_wrp">
      	<div class="cart_label"><?=count($cart).' item'.((count(cart) == 1) ? '' : 's')?> in your order</div>
	      <ul class="cart_s_items">
	        <?
	        	foreach($cart as $order) {
	          
	        		$site->readItem($order);
	        	?>	
	        	
	        		<li class="cart_s_name"><?=$order->name?><span><?=$site->formatCurrency($order->total)?></span></li>
	          
	          <?
	          	$this_order_total += $order->total;
		        }
	          ?>
	          
	      </ul>
	      <ul class="cart_s_total">
	          <li class="cart_s_name">Total:<span><?=$site->formatCurrency($this_order_total)?></span></li>
	      </ul> 
        <div class="cart_s_submit"><form action="<?=$site->base?>/order"><input class="submit_view_order" type=submit value="View Order"></form></div>
      </div>        
    </div>
  
  <?
    }
  ?>
  
</div>
