<? if(!$site->getCartState()) { ?>
	<? $site->sendTo($site->base.'/book'); ?>
<? } ?>

<div id="rezgo" class="wrp_book">
	<div id="panel_full">
		<div class="header">
			<h1>My Order</h1><!--<h2 onclick="stepBack();" id="step_1" class="on">{ 1. BOOK }</h2><h2 onclick="stepForward();" id="step_2" class="off">{ 2. PAY }</h2>-->
			<!--<div style="float:right; padding:9px 9px 0 0;">
				<script src="https://sealserver.trustwave.com/seal.js?style=normal" type="text/javascript"></script>
			</div>-->
		</div>
	  <div class="header_shadow"><img style="float:left" src="<?=$site->path?>/images/header_crumb_left.png"><img style="float:right;" src="<?=$site->path?>/images/header_crumb_right.png"></div>
	  
	  <!-- cart section -->
		<div id="rezgo_cart">
			<div class="tour_info">
	    	
	    	<?
					$cart = $site->getCart();
					
					if(!$cart) {
						
						echo '<div class="cart_empty">There are no items in your order.</div>';
						
					} else {
				?>
				
				<? foreach($cart as $order) { ?>
				  
				  <? $pax_edit_string = '?1=1'; ?>
				  
				  <? $site->readItem($order); ?>
				  
				  <div class="cart">
	          <div class="cart_name">
	          	<ol class="cart_detail">
	            	<li class="cart_tour_name">
	          			<h1 class="tour_title">
	          				<a href="<?=$site->base?>/details/<?=$order->com?>/<?=$site->seoEncode($order->name)?>">
	          					<?=$order->name?><span class="cart_option_name">&nbsp;(<?=$order->time?>)</span>
	          				</a>
	          			</h1>
	              </li>
	              <li class="cart_tour_date">
	              	<span>Booked For:&nbsp;</span><?=date("l M d, Y", $order->date)?>
	              </li>
	              <li class="cart_tour_qty">
                	<? foreach($order->pax as $label => $pax) { ?>
		          			<?=$order->labels->$label?> x <?=$pax?><br>
		          			<? $pax_edit_string .= '&'.$label.'_num='.$pax; ?>
		          		<? } ?>
		          		
		          		<? if($order->availability < $order->pax_count) { ?>							
									
										<div class="cart_status_box">
											<div class="cart_status cart_cancelled">NOT AVAILABLE</div>
										</div>
									
									<?  	
								  	} else {
								  		$cart_total += $order->total; 
								  	}
								  ?>
				          		
		          		
                </li>
	            </ol>
	            
	          </div>
	          <div class="cart_cost">
	          	<ol class="cart_detail">
	              	<li class="price"><?=$site->formatCurrency($order->total)?></li>
	                  <li class="cart_edit">
	                  	<input class="cart_edit" type="submit" onclick="document.location.href='<?=$site->base?>/details/<?=$order->com?>/<?=$site->seoEncode($order->name)?>/<?=$order->uid?>/<?=date("Y-m-d", $order->date)?><?=$pax_edit_string?>';" value="Make Changes">
	                  </li>
	                  <li class="cart_delete">
	                  	<input class="cart_delete" type="submit" onclick="document.location.href='?add[0][uid]=<?=$order->uid?>&add[0][date]=<?=date("Y-m-d", $order->date)?>&add[0][adult_num]=0';" value="Remove from Order">
	                  </li>
	              </ol>
	          </div>
	        </div>
				          
	      <? } ?>
	      	
      	<div class="cart_total">
          <ul>
          	<li class="cart_total_label">Current Order Total</li>
            <li class="cart_total"><span class="cart_total_currency"><?=$site->formatCurrency($cart_total)?></span></li>
          </ul>
        </div>
          
      <? } ?>
	    	  
      </div><!-- end of tour_info wrp -->
      
      <div class="cart_action">
    		<li class="cart_back">
    			<input class="submit" value="Continue Shopping" onclick="document.location.href='<?=$site->base?>/';" type="submit">
    		</li>
        <li class="cart_submit">
        	<form action="<?=$site->base?>/book">
         		
         		<? 
         			if(strpos($_SERVER[HTTP_HOST], '.rezgo.com') === false) { 
	         			
	         			
	         			foreach( $cart as $key => $order ) {
									echo '<input type="hidden" name="add['.$order->cartID.'][uid]" value="'.$order->uid.'">';
									echo '<input type="hidden" name="add['.$order->cartID.'][date]" value="'.date("Y-m-d", $order->date).'">';
									foreach($order->pax as $pax => $num) {	
										echo '<input type="hidden" name="add['.$order->cartID.']['.$pax.'_num]" value="'.$num.'">';
									}
								}
	         			
	         			
         			}
         		?>
         		
         		<? if($_COOKIE['rezgo_promo']) { ?><input type="hidden" name="promo" value="<?=$_COOKIE['rezgo_promo']?>"><? } ?>
						<? if($_COOKIE['rezgo_refid_val']) { ?><input type="hidden" name="refid" value="<?=$_COOKIE['rezgo_refid_val']?>"><? } ?>
         		
         		<input class="submit" value="Proceed to Check Out" type="submit">
         	
         	</form>
        </li>
      </div>
      
		  <!-- end of receipt section -->
		  
		</div>
		
		<?
			$this_link = 'http://'.$_SERVER[HTTP_HOST].$site->base.'/order/?order=clear';			
			foreach( $cart as $key => $order ) {
				$this_link .= '&add['.$order->cartID.'][uid]='.$order->uid.'&add['.$order->cartID.'][date]='.date("Y-m-d", $order->date);
				foreach($order->pax as $pax => $num) {	
					$this_link .= '&add['.$order->cartID.']['.$pax.'_num]='.$num;
				}
			}
		?>

		<div id="booking_link">
			<a href="javascript:void(0);" onclick="$('#booking_link_val').toggle('fade');">share this order</a>
			<input type=text id="booking_link_val" style="display:none;" onfocus="this.select();" value="<?=$this_link?>">
		</div>
				
  </div> <!-- end panel_full-->
</div>
