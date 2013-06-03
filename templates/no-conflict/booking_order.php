<?
	$trans_num = $site->decode($_REQUEST['trans_num']);

	// send the user home if they shoulden't be here
	if(!$trans_num) $site->sendTo($site->base."/order-not-found:empty");
	
	// start a session so we can grab the analytics code
	session_start();
	
	$order_bookings = $site->getBookings('t=order_code&q='.$trans_num);
	
	if(!$order_bookings) { $site->sendTo("/order-not-found:".$_REQUEST['trans_num']); }
	
	// check and see if we want to be here or on the individual item
	// if we only have 1 item and the cart is off, forward them through
	if(!$site->getCartState() && count($order_bookings) == 1) {
		$site->sendTo($site->base.'/complete/'.$site->encode($order_bookings[0]->trans_num));
	}
?>

<? if($_SESSION['REZGO_CONVERSION_ANALYTICS']) { ?>
	<?=$_SESSION['REZGO_CONVERSION_ANALYTICS']?>
			
	<?
	/*
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
	</script>

	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("<?=(($site->isVendor()) ? 'UA-1943654-6' : 'UA-1943654-4')?>");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>
	*/
	?>
<? } ?>

<div id="rezgo" class="wrp_book">
	
<div id="panel_full">
	
	<!--
		XML Dump for TripIt
		<rezgo> 
			
			<?=$site->get?>
			
		</rezgo>
	-->
	
	<? if($_SESSION['REZGO_CONVERSION_ANALYTICS']) { ?>
	<div class="message_green">
		<span style="font-size:18px;">YOUR BOOKING HAS BEEN ADDED</span>
	</div>
	<? unset($_SESSION['REZGO_CONVERSION_ANALYTICS']); ?>
	<? } ?>
	
	
	<div class="message">
		<span style="font-size:20px;">ORDER INFORMATION</span>
		<br>
		<br>
		Order ID <?=$trans_num?>
		<br>
		<br>
		<?=count($order_bookings)?> Booking<?=((count($order_bookings) != 1) ? 's' : '')?> in this order<br>
		Click on a booking to view it's details
	</div>
	
	
	<div id="rezgo_cart">
			<div class="tour_info">
	
	<? foreach( $order_bookings as $booking ) { ?>
	
		<? $item = $site->getTours('t=uid&q='.$booking->item_id, 0); ?>
		
		<? $site->readItem($booking); ?>
				  
	  <div class="cart" onclick="document.location.href='<?=$site->base?>/complete/<?=$site->encode($booking->trans_num)?>'" style="cursor:pointer;">
    	<div class="cart_img">
      	<img border="0" src="https://images.rezgo.com/items/<?=$item->cid?>-<?=$item->com?>.jpg">
      </div>
      <div class="cart_name">
      	<ol class="cart_detail">
        	<li class="cart_tour_name">
      			<h1 class="tour_title">
      				<a href="<?=$site->base?>/complete/<?=$site->encode($booking->trans_num)?>">
      					<?=$booking->tour_name?><span class="cart_option_name">&nbsp;(<?=$booking->option_name?>)</span>
      				</a>
      			</h1>
          </li>
          <li class="cart_tour_date">
          	<span>Booked For:&nbsp;</span><?=date("l M d, Y", (string)$booking->date)?>
          </li>
          <li class="cart_tour_qty">
          
          	Transaction # <?=$booking->trans_num?>
          	
          	<div class="cart_status_box">
	          	
	          	<? if($booking->status == 1 OR $booking->status == 4) { ?>
								<div class="cart_status cart_complete">BOOKING COMPLETE</div>
							<? } ?>
							<? if($booking->status == 2) { ?>
								<div class="cart_status cart_pending">BOOKING PENDING</div>
							<? } ?>
							<? if($booking->status == 3) { ?>
								<div class="cart_status cart_cancelled">BOOKING CANCELLED</div>
							<? } ?>
	          	
          	</div>
          	
          </li>
        </ol>
        
        
      </div>
     <div class="cart_cost">
	   	<ol class="cart_detail">
      	<li class="price">
      		<?=$site->formatCurrency((float)$booking->overall_total)?>
      	</li>
        <li class="cart_delete">
          <input class="cart_delete" type="submit" onclick="document.location.href='<?=$site->base?>/complete/<?=$site->encode($booking->trans_num)?>'" value="View Booking">
        </li>
          
          <? if($site->exists($booking->paypal_owed)) { ?>
				 	<div class="paypal_button">
				 	
				 		<? $company_paypal = $site->getCompanyPaypal(); ?>
				 		
				 		<form method="post" action="<?=REZGO_DIR?>/php_paypal/process.php">		
							<input type="hidden" name="firstname" id="firstname" value="<?=$booking->first_name?>">
							<input type="hidden" name="lastname" id="lastname" value="<?=$booking->last_name?>">
							<input type="hidden" name="address1" id="address1" value="<?=$booking->address_1?>"> 
							<input type="hidden" name="address2" id="address2" value="<?=$booking->address_2?>">
							<input type="hidden" name="city" value="<?=$booking->city?>">
							<input type="hidden" name="state" value="<?=$booking->stateprov?>">
							<input type="hidden" name="country" value="<?=$site->countryName($booking->country)?>">
							<input type="hidden" name="zip" value="<?=$booking->postal_code?>">
							<input type="hidden" name="email" id="email" value="<?=$booking->email_address?>">
							<input type="hidden" name="phone" id="phone" value="<?=$booking->phone_number?>">
							
							<input type="hidden" name="item_name" id="item_name" value="<?=$booking->tour_name?> - <?=$booking->option_name?>">
							<input type="hidden" name="encoded_transaction_id" id="encoded_transaction_id" value="<?=$site->encode($booking->trans_num)?>">
							<input type="hidden" name="item_number" id="item_number" value="<?=$booking->trans_num?>">
							<input type="hidden" name="amount" id="amount" value="<?=$booking->paypal_owed?>">
							<input type="hidden" name="quantity" id="quantity" value="1">	
							<input type="hidden" name="business" value="<?=$company_paypal?>">
							<input type="hidden" name="currency_code" value="<?=$site->getBookingCurrency()?>">
							<input type="hidden" name="domain" value="<?=$site->getDomain()?>.rezgo.com">
						
							<input type="hidden" name="cid" value="<?=REZGO_CID?>">
							<input type="hidden" name="paypal_signature" value="">
							<input type="hidden" name="base_url" value="rezgo.com">
							<input type="hidden" name="cancel_return" value="http://<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>">
						
							<input type="image"  class="paypal_button" name="submit_image" src="<?=$site->path?>/images/paypal_pay.png" />
						</form>
				 	
				 	</div>
				 	<? } ?>
          
          
      </ol>
    </div>
    </div>
	          
	  <? $cart_total += ((float)$booking->overall_total); ?>
	  	
	<? } ?>
	
	<div class="cart_total">
    <ul>
    	<li class="cart_total_label">Order Total</li>
      <li class="cart_total"><span class="cart_total_currency"><?=$site->formatCurrency($cart_total)?></span></li>
    </ul>
  </div>
  
	</div>
	</div>

</div><!-- end of panel_full--> 
<div class="clear"></div> <!-- do not take this out -->
<!-- Rezgo logo DO NOT DELETE -->
<div id="rezgo_logo"><a href="http://www.rezgo.com" target="_blank" title="powered by rezgo">powered by<img src="<?=$site->path?>/images/logo_rezgo.gif" border="0" alt="Rezgo" /></a></div>
<!-- Rezgo logo DO NOT DELETE -->
</div><!--end rezgo wrp-->