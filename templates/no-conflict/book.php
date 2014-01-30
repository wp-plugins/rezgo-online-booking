<?	
	// handle old-style booking requests
	if($_REQUEST[uid] && $_REQUEST[date]) {	
		$for_array = array('adult', 'child', 'senior', 'price4', 'price5', 'price6', 'price7', 'price8', 'price9');
		$new_header = '/book?order=clear&add[0][uid]='.$_REQUEST[uid].'&add[0][date]='.$_REQUEST[date];
		foreach($for_array as $v) {
			if($_REQUEST[$v.'_num']) $new_header .= '&add[0]['.$v.'_num]='.$_REQUEST[$v.'_num'];
		}
		$site->sendTo($new_header);
	}
?>

<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.scrollTo.min.js"></script>
<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.form.js"></script>
<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.selectboxes.pack.js"></script>
<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.validate.min.js"></script>

<div id="rezgo" class="wrp_book">

<div class="modal" id="prompt">
	<h2>Your booking is being completed</h2>
	
	<center>
		<img src="<?=$site->path?>/images/booking_load.gif">
		Please wait a moment...
	</center>
</div>

<div id="panel_full">
	<div class="breadcrumb"><a href="<?=$site->base?>/?search=restore" class="back"><span><<</span> Back to Results</a></div>
	<div class="header">
		<h1>Your Booking Details</h1><h2 class="on" id="step_1" onclick="stepBack();">{ 1. BOOK }</h2><h2 class="off" id="step_2" onclick="stepForward();">{ 2. PAY }</h2>
		<div style="float:right; padding:9px 9px 0 0;">
			<script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?style=normal"></script>
		</div>
	</div>

  <div class="header_shadow"><img src="<?=$site->path?>/images/header_crumb_left.png" style="float:left" /><img src="<?=$site->path?>/images/header_crumb_right.png" style="float:right;" /></div>
	
	
	<script>
		var elements = new Array();
		
		var split_total = new Array();
		var overall_total = '0';
		
		var form_symbol = '$';
		var form_decimals = '2';
		var form_separator = ',';
		
		// money formatting
		Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
	    var n = this,
	    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? form_decimals : decPlaces,
	    decSeparator = decSeparator == undefined ? "." : decSeparator,
	    thouSeparator = thouSeparator == undefined ? form_separator : thouSeparator,
	    sign = n < 0 ? "-" : "",
	    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
	    j = (j = i.length) > 3 ? j % 3 : 0;
	    
	    var dec;
	    var out = sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator);
	    if(decPlaces) dec = Math.abs(n - i).toFixed(decPlaces).slice(2);
	    if(dec) out += decSeparator + dec;
	    return out;
		};
		
		function clean_money_string(str) {
			// convert to str in case it has strange characters (like a ,)
			str += '';
			// clean (except . and -) and convert back to float
			return parseFloat(str.replace(/[^0-9.-]/, ""));
		}
		
		function add_element(id, name, price, order_num) {
			
			// ensure our array has an array for the actual elements
			if(!elements[order_num]) elements[order_num] = new Array();
			
			var num = add_price = clean_money_string(price);
			if(elements[order_num][id]) num = num + clean_money_string(elements[order_num][id]);
			
			var price = num.formatMoney();

			var display_price = form_symbol + price;
			
			name = name.replace("\\'", "'");
			
			if(!elements[order_num][id]) {
				var content = '<li class="info" id="element_' + order_num + '_' + id + '"><label class="extra">' + name + '</label><span class="extra price_neg" id="val_' + order_num + '_' + id + '">' + display_price + '</span></li>';
				jQuery("#fee_box_" + order_num).html( jQuery("#fee_box_" + order_num).html() + content );
			} else {
				if(document.getElementById('element_' + order_num + '_' + id).style.display == 'none') document.getElementById('element_' + order_num + '_' + id).style.display = '';
				jQuery("#val_" + order_num + "_" + id).html(display_price);
			}	
			elements[order_num][id] = price;
			
			// add to total amount
			var total = split_total[order_num];
			total = clean_money_string(total) + add_price;
			total = total.formatMoney();
			split_total[order_num] = total;
			
			// set the total for this item
			jQuery("#total_value_" + order_num).html(form_symbol + total);
			
			// set the order total if this item doesn't have a deposit set
			if(!jQuery("#deposit_value_" + order_num).html()) {
				overall_total = clean_money_string(overall_total) + add_price;
				overall_total = overall_total.formatMoney();
				
				jQuery("#total_value").html(form_symbol + overall_total);
			}
			
			// if total is greater than 0 then appear payment section
			if(overall_total > 0) document.getElementById('payment_info').style.display = '';
		}
		
		function sub_element(id, price, order_num) {
		
			// ensure our array has an array for the actual elements
			if(!elements[order_num]) elements[order_num] = new Array();
		
			if(!elements[order_num][id] || elements[order_num][id] == 0) return false;
		
			var num = sub_price = clean_money_string(price);
			num = clean_money_string(elements[order_num][id]) - num;
			
			var price = num.formatMoney();
			if(price < 0) price = 0;
			
			var display_price = form_symbol + price;
			
			if(price == 0) {	
				document.getElementById('element_' + order_num + '_' + id).style.display = 'none';
			} else {
				document.getElementById('val_' + order_num + '_' + id).innerHTML = display_price;
			}	
			elements[order_num][id] = price;
			
			// sub from total amount
			var total = split_total[order_num];
			total = clean_money_string(total) - sub_price;
			total = total.formatMoney();
			split_total[order_num] = total;
			
			// set the total for this item
			jQuery("#total_value_" + order_num).html(form_symbol + total);
			
			// set the order total if this item doesn't have a deposit set
			if(!jQuery("#deposit_value_" + order_num).html()) {
				overall_total = clean_money_string(overall_total) - sub_price;
				overall_total = overall_total.formatMoney();
				
				jQuery("#total_value").html(form_symbol + overall_total);
			}
		
			// if total is 0 then hide payment section
			if(overall_total <= 0) document.getElementById('payment_info').style.display = 'none';
			
		}
	</script>

	<form method="post" id="book">  
  
  <div id="content_1">
  
  <? 
		$c = 0;
		$cart = $site->getCart(1); // get the cart, remove any dead entries
		
		if(!count($cart)) {
			$site->sendTo('/'.$site->base);
		} 
	
		// --------------------------------------------
		//
		// Start Cart Loop - For each tour in the order
		//
		// --------------------------------------------
		
		foreach( $cart as $order ) {
		
			$c++;
			
			$pax_string = '';
			foreach($order->pax as $label => $pax) {
				$pax_string .= '&'.$label.'_num='.$pax;
			}
	
	?>
	
	<? foreach( $site->getTours('t=uid&q='.$order->uid.'&d='.date("Y-m-d", $order->date).$pax_string) as $item ) { ?>
	
		<? $site->readItem($item) ?>
		
		<script>
			split_total[<?=$c?>] = <?=$item->overall_total?>;
		</script>
		
		<input type="hidden" name="booking[<?=$c?>][book]" value="<?=$order->uid?>"> 
	  <input type="hidden" name="booking[<?=$c?>][date]" value="<?=date("Y-m-d", $order->date)?>">
	  
	  <input type="hidden" name="booking[<?=$c?>][adult_num]" value="<?=$order->adult_num?>">
	  <input type="hidden" name="booking[<?=$c?>][child_num]" value="<?=$order->child_num?>">
	  <input type="hidden" name="booking[<?=$c?>][senior_num]" value="<?=$order->senior_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price4_num]" value="<?=$order->price4_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price5_num]" value="<?=$order->price5_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price6_num]" value="<?=$order->price6_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price7_num]" value="<?=$order->price7_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price8_num]" value="<?=$order->price8_num?>">
	  <input type="hidden" name="booking[<?=$c?>][price9_num]" value="<?=$order->price9_num?>">
	  
	  <!-- cart section -->
	  <br>
	  <div class="tour_info">
		  <h2>Booking <?=$c?> of <?=count($cart)?></h2>
		  <fieldset>
		  <ol class="tour_receipt">
		  	<li class="info"><label>You are booking</label><span><?=$item->name?><br /><?=$item->time?></span></li>
		
		    <li class="info"><label>Date</label><span><?=date("F d, Y", $order->date)?></span></li>
		    <li class="info"><label>Duration</label><span><?=$item->duration?></span></li>
		   	<? if($_COOKIE['rezgo_promo']) { ?>
		   	<li class="info"><label>Promotional Code</label><span><?=$_COOKIE['rezgo_promo']?></span></li>
		   	<? } ?>
		    <li class="info last"><label>Price</label>
		    	<ol class="price">
		      	<li class="info">
		          <label class="type">type</label>
		
		          <label class="qty">qty</label>
		          <label class="cost">cost</label>
		          <label class="line_total">total</label>
		        </li>
		        
		        <? foreach( $site->getTourPrices() as $price ) { ?>
		        	<? if($order->{$price->name.'_num'}) { ?>
			        	<li class="info">
				        	<span class="type"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$price->label?></span>
				        	<span class="qty"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$order->{$price->name.'_num'}?></span>
				        	<span class="cost">
				        		<? if($site->exists($price->base)) { ?><span class="discount"><?=$site->formatCurrency($price->base)?></span><? } ?>
				        		<?=$site->formatCurrency($price->price)?>
				        	</span>
				        	<span class="line_total"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$site->formatCurrency($price->total)?></span>
				        </li>
		        	<? } ?>
		        <? } ?>
		      	
						<li class="info"><label class="subtotal">Subtotal</label><span class="subtotal"><?=$site->formatCurrency($item->sub_total)?></span></li>
					        
		      </ol>
		    </li>
		  </ol>
		  </fieldset>
	  </div>
	  <!-- end of receipt section -->
	
		<? if($item->group != 'hide') { ?>
		
		<!-- start passenger information --> <!-- use class=negative for negative price value -->
	  <p class="notation">To finish your booking, please complete the following form. Please note that fields marked with <em>*</em> are required.</p>
	  
	  <div class="booking_info">
	  
	  <? foreach( $site->getTourPrices($item) as $price ) { ?>	  	
	  	<? foreach( $site->getTourPriceNum($price, $order) as $num ) { ?>
	  
			  <h2 class="title"><?=$price->label?> <?=$num?></h2>
			  <fieldset>
			  	<ol>
			
			      <li class="half"><label for="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_first_name">First Name<? if($item->group == 'require') { ?><em>*</em><? } ?></label><input type="text" id="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_first_name" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][first_name]" value=""<? if($item->group == 'require') { ?> class="required"<? } ?> /></li>
			      <li class="half"><label for="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_last_name">Last Name<? if($item->group == 'require') { ?><em>*</em><? } ?></label><input type="text" id="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_last_name" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][last_name]" value=""<? if($item->group == 'require') { ?> class="required"<? } ?> /></li>
			      <li class="half"><label for="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_phone">Phone Number</label><input type="text" id="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_phone" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][phone]" value="" /></li>
			      <li class="half"><label for="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_email">Email Address</label><input type="email" id="fname_<?=$c?>_<?=$price->name?>_<?=$num?>_email" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][email]" value="" /></li>
			  		
			  		<? foreach( $site->getTourForms(group) as $form ) { ?>
	  					
	  					<? if($form->type == 'text') { ?>
	  						<li class="hr"></li>
	  						<li>
	  							<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
	  							<span><?=$form->comments?></span>
			    				<input type="text" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][forms][<?=$form->id?>]"<? if($form->require) { ?> class="required"<? } ?> />
			    			</li>
	  					<? } ?>
	  					
	  					<? if($form->type == 'select') { ?>
	  						<li class="hr"></li>
	  						<li>
	  							<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
	  							<span><?=$form->comments?></span>
			    				<select name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][forms][<?=$form->id?>]"<? if($form->require) { ?> class="required"<? } ?>>
							    	<? foreach($form->options as $option) { ?>
							    		<option><?=$option?></option>
							    	<? } ?>
							    </select>
			    			</li>
	  					<? } ?>
	  					
	  					<? if($form->type == 'multiselect') { ?>
	  						<li class="hr"></li>
	  						<li>
	  							<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
	  							<span><?=$form->comments?></span>
			    				<select multiple="multiple" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][forms][<?=$form->id?>][]"<? if($form->require) { ?> class="required"<? } ?>>
							    	<? foreach($form->options as $option) { ?>
							    		<option><?=$option?></option>
							    	<? } ?>
							    </select>
			    			</li>
	  					<? } ?>
	  					
	  					<? if($form->type == 'textarea') { ?>
	  						<li class="hr"></li>
	  						<li>
	  							<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
	  							<span><?=$form->comments?></span>
			    				<textarea name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][forms][<?=$form->id?>]" cols="40" rows="4"<? if($form->require) { ?> class="required"<? } ?>></textarea>
			    			</li>
	  					<? } ?>
	  					
	  					<? if($form->type == 'checkbox') { ?>
	  						<li class="hr"></li>
	  						<li>
	  							<input type="checkbox" class="checkbox<? if($form->require) { ?> required<? } ?>" id="<?=$form->id?>|<?=addslashes($form->label)?>|<?=$form->price?>|<?=$c?>|<?=$price->name?>|<?=$num?>" name="booking[<?=$c?>][tour_group][<?=$price->name?>][<?=$num?>][forms][<?=$form->id?>]" <? if($form->price) { ?>onclick="if(this.checked) { add_element('<?=$form->id?>', '<?=addslashes(htmlentities($form->label))?>', '<? if($form->price_mod == '-') { ?><?=$form->price_mod?><? } ?><?=$form->price?>', '<?=$c?>'); } else { sub_element('<?=$form->id?>', '<? if($form->price_mod == '-') { ?><?=$form->price_mod?><? } ?><?=$form->price?>', '<?=$c?>'); }"<? } ?> />
	  							<label class="checkbox" for="<?=$form->id?>|<?=addslashes($form->label)?>|<?=$form->price?>|<?=$c?>|<?=$price->name?>|<?=$num?>"><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?><? if($form->price) { ?> <em><?=$form->price_mod?> <?=$site->formatCurrency($form->price)?></em><? } ?> 
	  								<span class="checkbox"><?=$form->comments?></span>
	  							</label>
	  						</li>
	  					<? } ?>

						<? } ?>

			   	</ol>
			 	</fieldset>
			   
			<? } ?>
		<? } ?>
	
	  </div> 
	  <!-- end of passnger info-->
	  
		<? } ?>
	
		<? if($site->getTourForms(primary)) { ?>
		  
		<!---- additional info ---->
		  <div class="booking_info">
		  <h2 class="title">Additional Information</h2>
		<!-- start extra form field -->
		  <fieldset>
				<ol>
					<? foreach( $site->getTourForms(primary) as $form ) { ?>
		  					
		  			<? if($first_line) { ?><li class="hr"></li><? } else { $first_line = 1; } ?>
		  					
						<? if($form->type == 'text') { ?>
							<li>
								<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
								<span><?=$form->comments?></span>
		    				<input type="text" name="booking[<?=$c?>][tour_forms][<?=$form->id?>]"<? if($form->require) { ?> class="required"<? } ?> />
		    			</li>
						<? } ?>
						
						<? if($form->type == 'select') { ?>
							<li>
								<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
								<span><?=$form->comments?></span>
		    				<select name="booking[<?=$c?>][tour_forms][<?=$form->id?>]"<? if($form->require) { ?> class="required"<? } ?>>
		    					<? foreach($form->options as $option) { ?>
						    		<option><?=$option?></option>
						    	<? } ?>
						    </select>
		    			</li>
						<? } ?>
						
						<? if($form->type == 'multiselect') { ?>
							<li>
								<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
								<span><?=$form->comments?></span>
								<select multiple="multiple" name="booking[<?=$c?>][tour_forms][<?=$form->id?>][]"<? if($form->require) { ?> class="required"<? } ?>>
						    	<? foreach($form->options as $option) { ?>
						    		<option><?=$option?></option>
						    	<? } ?>
						    </select>
		    			</li>
						<? } ?>
						
						<? if($form->type == 'textarea') { ?>
							<li>
								<label><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?></label>
								<span><?=$form->comments?></span>
		    				<textarea name="booking[<?=$c?>][tour_forms][<?=$form->id?>]"<? if($form->require) { ?> class="required"<? } ?> cols="40" rows="4"></textarea>
		    			</li>
						<? } ?>
						
						<? if($form->type == 'checkbox') { ?>
							<li>
								<input type="checkbox" class="checkbox<? if($form->require) { ?> required<? } ?>" id="<?=$form->id?>|<?=addslashes($form->label)?>|<?=$form->price?>" name="booking[<?=$c?>][tour_forms][<?=$form->id?>]" <? if($form->price) { ?>onclick="if(this.checked) { add_element('<?=$form->id?>', '<?=addslashes(htmlentities($form->label))?>', '<? if($form->price_mod == '-') { ?><?=$form->price_mod?><? } ?><?=$form->price?>', '<?=$c?>'); } else { sub_element('<?=$form->id?>', '<? if($form->price_mod == '-') { ?><?=$form->price_mod?><? } ?><?=$form->price?>', '<?=$c?>'); }"<? } ?> />
								<label class="checkbox" for="<?=$form->id?>|<?=addslashes($form->label)?>|<?=$form->price?>"><?=$form->label?><? if($form->require) { ?><em>*</em><? } ?><? if($form->price) { ?> <em><?=$form->price_mod?> <?=$site->formatCurrency($form->price)?></em><? } ?> 
									<span class="checkbox"><?=$form->comments?></span>
								</label>
							</li>
						<? } ?>
		
					<? } ?>
				</ol>
			</fieldset>
		  </div>
			<!-- end of additional info -->
		  
		  <? } ?>
		  
		<? } ?>
		
	<? } /* end shopping cart loop */ ?>
  	
	<!----- submit button ----->
	
		<div>
			<? if($site->getCartState()) { ?>
				<input class="previous" type="button" value="Back to Order" onclick="document.location.href='<?=$site->base?>/order'; return false;">
			<? } ?>
			<input class="submit" type="button" value="Next Step" onclick="stepForward(); return false;">
		</div>
	
	</div>

	<div id="content_2" style="display:none;">
		
		<?
			$c = 0;
			
			// --------------------------------------------
			//
			// Start Cart Loop - For each tour in the order
			//
			// --------------------------------------------
			
			foreach( $cart as $order ) {
			$c++;
			
			$pax_string = '';
			foreach($order->pax as $label => $pax) {
				$pax_string .= '&'.$label.'_num='.$pax;
			}
	
		?>
	
		<? foreach( $site->getTours('t=uid&q='.$order->uid.'&d='.date("Y-m-d", $order->date).$pax_string) as $item ) { ?>
	
			<? $site->readItem($item) ?>
		
			
			<!-- cart section -->
		  <div class="tour_info">
			  <h2>Booking <?=$c?> of <?=count($cart)?></h2>
			  <fieldset>
			  <ol class="tour_receipt">
			  	<li class="info"><label>You are booking</label><span><?=$item->name?><br /><?=$item->time?></span></li>
			
			    <li class="info"><label>Date</label><span><?=date("F d, Y", $order->date)?></span></li>
			    <li class="info"><label>Duration</label><span><?=$item->duration?></span></li>
			    <? if($_COOKIE['rezgo_promo']) { ?>
			   	<li class="info"><label>Promotional Code</label><span><?=$_COOKIE['rezgo_promo']?></span></li>
			   	<? } ?>
			    <li class="info last"><label>Price</label>
			    	<ol class="price">
			      	<li class="info">
			          <label class="type">type</label>
			
			          <label class="qty">qty</label>
			          <label class="cost">cost</label>
			          <label class="line_total">total</label>
			        </li>
			        
			        <? foreach( $site->getTourPrices($item) as $price ) { ?>
			        	<? if($order->{$price->name.'_num'}) { ?>
				        	<li class="info">
					        	<span class="type"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$price->label?></span>
					        	<span class="qty"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$order->{$price->name.'_num'}?></span>
					        	<span class="cost">
					        		<? if($site->exists($price->base)) { ?><span class="discount"><?=$site->formatCurrency($price->base)?></span><? } ?>
					        		<?=$site->formatCurrency($price->price)?>
					        	</span>
					        	<span class="line_total"><? if($site->exists($price->base)) { ?><span class="discount"></span><? } ?><?=$site->formatCurrency($price->total)?></span>
					        </li>
			        	<? } ?>
			        <? } ?>
			      	
							<li class="info"><label class="subtotal">Subtotal</label><span class="subtotal"><?=$site->formatCurrency($item->sub_total)?></span></li>
							
							<? foreach( $site->getTourLineItems() as $line ) { ?>
								<?
									unset($label_add);
									if($site->exists($line->percent) || $site->exists($line->multi)) {
										$label_add = ' (';
											
											if($site->exists($line->percent)) $label_add .= $line->percent.'%';
											if($site->exists($line->multi)) {
												if(!$site->exists($line->percent)) $label_add .= $site->formatCurrency($line->multi);
												$label_add .= ' x '.$item->pax;
													
											}
											
										$label_add .= ')';	
									}
								?>
							
						  	<li class="info">
						  		<label class="subtotal"><?=$line->label?><?=$label_add?></label>
									<span class="tax_fees price_pos"><?=$site->formatCurrency($line->amount)?></span>
								</li>
							<? } ?>
							
							<div id="fee_box_<?=$c?>">
		
							</div>
							
							<li class="info"><label class="total">TOTAL FOR THIS BOOKING</label><span class="total" id="total_value_<?=$c?>"><?=$site->formatCurrency($item->overall_total)?></span></li>
							
							<? if($site->exists($item->deposit)) { ?>
							<li class="info"><label class="total">Deposit to Pay Now</label><span class="total" id="deposit_value_<?=$c?>"><?=$site->formatCurrency($item->deposit_value)?></span></li>
			        <? 
			        		$complete_booking_total += (float) $item->deposit_value;
			        	} else {
				      		$complete_booking_total += (float) $item->overall_total;
			        	} 
			        ?>
			      </ol>
			    </li>
			  </ol>
			  </fieldset>
		  </div>
		  <!-- end of receipt section -->
		  
		  <? } ?>
		  	
		<? } ?>
		
		<script>
			overall_total = '<?=$complete_booking_total?>';
			
			form_decimals = '<?=$item->currency_decimals?>';
			form_symbol = '<?=$item->currency_symbol?>';
			form_separator = '<?=$item->currency_separator?>';
		</script>
		
		<div class="tour_total">
		  <fieldset>
			  <ol class="tour_receipt">
			      <li class="info"><label class="total_pay">TOTAL TO PAY NOW</label><span class="total_pay_now" id="total_value"><?=$site->formatCurrency($complete_booking_total)?></span></li>
			  </ol>
		  </fieldset>
	  </div>

		<p class="notation">To finish your booking, please complete the following form. Please note that fields marked with "<em>*</em>" are required.</p>
	
	  <div class="billing_info">
	  <h2>Billing / Primary Contact Information</h2>
	  <fieldset>
	  	<ol>
	    <li class="half"><label for="tour_first_name">First Name<em>*</em></label><input id="tour_first_name" name="tour_first_name" type="text" value="" /></li>
	    <li class="half"><label for="tour_last_name">Last Name<em>*</em></label><input id="tour_last_name" name="tour_last_name" type="text" value="" /></li>
	
	    <li class="half"><label for="tour_address_1">Address<em>*</em></label><input id="tour_address_1" name="tour_address_1" type="text" value="" /></li>
	    <li class="half"><label for="tour_address_2">Address 2</label><input id="tour_address_2" name="tour_address_2" type="text" value="" /></li>
	    
	    <li class="half"><label for="tour_city">City<em>*</em></label><input id="tour_city" name="tour_city" type="text" value="" /></li>
	    <li class="half"><label for="tour_postal_code">Zip/Postal Code<em>*</em></label><input id="tour_postal_code" name="tour_postal_code" type="text" value="" /></li>
			
	    <li class="half"><label for="tour_country">Country<em>*</em></label>
	    	<? $companyCountry = $site->getCompanyCountry(); ?>
				<select name="tour_country" id="tour_country" />
	    		<? foreach( $site->getRegionList() as $iso => $name ) { ?>
	    			<option value="<?=$iso?>" <?=(($iso == $companyCountry) ? 'selected' : '')?>><?=ucwords($name)?></option>
	    		<? } ?>
	    	</select>
	    </li>
	    <li class="half"><label for="tour_stateprov">State/Province</label>
	    	<select id="tour_stateprov" style="display:<?=(($companyCountry != 'ca' && $companyCountry != 'us' && $companyCountry != 'au') ? 'none' : '')?>;"></select>
	    	<input id="tour_stateprov_txt" name="tour_stateprov" type="text" value="" style="display:<?=(($companyCountry != 'ca' && $companyCountry != 'us' && $companyCountry != 'au') ? '' : 'none')?>;" />
      </li>
	    
	    <li class="half"><label for="tour_phone_number">Phone Number<em>*</em></label><input id="tour_phone_number" name="tour_phone_number" type="text" value="" /></li>
	    <li class="half"><label for="tour_email_address">Email Address<em>*</em></label><input id="tour_email_address" name="tour_email_address" type="email" value="" /></li>
	    </ol>
	   </fieldset>
		</div>
		
	
		<div class="payment_info" id="payment_info" style="<?=(($complete_booking_total > 0) ? '' : 'display:none;')?>">
		<h2>Select Your Payment Method</h2>
			<fieldset>
				<ol>
					<li>
						
						<div style="float:left;">
							
							<table border="0" cellspacing="0" cellpadding="0" width="100%">
								
							<? 
								foreach( $site->getPaymentMethods() as $pay ) {
								
									if($pay[name] == 'Credit Cards') {
										echo '<tr><td><input type="radio" name="payment_method" id="payment_method_credit" value="Credit Cards" checked onclick="toggleCard();">&nbsp;&nbsp;</td><td style="height:42px;">
											<label for="payment_method_credit">';
										
											foreach( $site->getPaymentCards() as $card ) {
												echo '<img src="'.$site->path.'/images/logos/'.$card.'.png" style="margin:0px;">';
												
											}
										
										echo '</label>
										<input type="hidden" name="tour_card_token" id="tour_card_token" value="">
										<script>
											jQuery(\'#tour_card_token\').val(\'\');
											setTimeout(function() {
												jQuery(\'#payment_method_credit\').attr(\'checked\', true);
											}, 600);
										</script>
										</td></tr>';
										
									} elseif($pay[name] == 'PayPal' && !$site->exists($site->getCompanyPaypal())) {
									
										echo '<tr><td><input type="radio" name="payment_method" id="payment_method_paypal" value="PayPal" onclick="getPaypalToken(); toggleCard();">&nbsp;&nbsp;</td><td style="height:42px;">
											<label for="payment_method_paypal"><img src="'.$site->path.'/images/logos/paypal.png" style="margin:0px;"></label>
											<input type="hidden" name="paypal_token" id="paypal_token" value="">
											<input type="hidden" name="paypal_payer_id" id="paypal_payer_id" value="">
										</td></tr>';
										
									} else {
										$pmc++;
										$set_name = ($pay[name] == 'PayPal') ? '<img src="'.$site->path.'/images/logos/paypal.png" style="margin:0px;">' : $pay[name];
										echo '<tr><td><input type="radio" name="payment_method" id="payment_method_'.$pmc.'" value="'.$pay[name].'" onclick="toggleCard();">&nbsp;&nbsp;</td>
											<td style="font-size:18px; font-weight:bold; color:#666; margin-bottom:10px; height:35px;">
												<label for="payment_method_'.$pmc.'">'.$set_name.'</label>
											</td>
										</tr>';
									}		
								}
								
							?>
							
							</table>
						</div>
						<div style="width:48%; float:right;">
							
							<? foreach( $site->getPaymentMethods() as $pay ) { ?>
								
								<? if($pay[name] == 'Credit Cards') { ?>
									
									<div id="payment_cards">
										<iframe style="height:170px; width:345px; border:0px;" scrolling="no" frameborder="0" name="tour_payment" id="tour_payment" src="<?=$site->base?>/booking_payment.php">
										
										</iframe>
									</div>
									
								<? } elseif($pay[name] == 'PayPal' && !$site->exists($site->getCompanyPaypal())) { ?>
									
									<div id="payment_paypal" style="font-weight:bold; color:#666; text-align:center; display:none;">
										<br><br><img src="<?=$site->path?>/images/booking_load.gif">
									</div>	
									
								<? } else { ?>
									
									<? $pmdc++; ?>
										
									<div id="payment_method_<?=$pmdc?>_box" style="font-weight:bold; color:#666; text-align:center; display:none;">
										
										<? if($pay[add]) { ?>
											
											<div class="payment_info" id="payment_method_<?=$pmdc?>_container" style="width:310px; padding:8px 0 8px 0;">
												<?=$pay[add]?><br>
												<input type="text" id="payment_method_<?=$pmdc?>_field" name="payment_method_add" style="width:85%; margin-top:5px;" value="" disabled="disabled" />										
											</div>
													
										<? } ?>
										
									</div>
									
								<? } ?>
									
							<? } ?>		
							
						</div>
						
					</li>
				</ol>
			
			</fieldset>
		</div>
		
	
		<!--  make a lightbox popup for terms and conditions -->
	  <div class="terms">
	  <h2>Terms and Conditions</h2>
	  <fieldset>
	  <ol>
	  	<li><input type="checkbox" class="checkbox" id="agree_terms" name="agree_terms" value="1" \>&nbsp;&nbsp;<label for="agree_terms">I agree to the</label> <a href="javascript:void(0);" onclick="javascript:window.open('<?=$site->base?>/terms_popup.php', 'mywindow', 'menubar=1,resizable=1,scrollbars=1,width=800,height=600');">Terms and Conditions</a></li>
	  	<li class="payment_terms">
	  		<div id="terms_credit_card" style="display:<? if(!$site->getPaymentMethods('Credit Cards')) { ?>none<? } ?>;">
	  			<? if($site->getGateway() OR $site->isVendor()) { ?>
	  				<? if($item->overall_total > 0) { ?>Please note that your credit card will be charged. <? } ?>If you are satisfied with your entries, please click the "Complete Booking" button.
	  			<? } else { ?> 
	  				<? if($item->overall_total > 0) { ?>Please note that your credit card will not be charged now. Your transaction information will be stored until your payment is processed. Please see the Terms and Conditions for more information. <? } ?>If you are satisfied with your entries, please click the "Complete Booking" button.
	  			<? } ?>
	  		</div>
	  		<div id="terms_other" style="display:<? if($site->getPaymentMethods('Credit Cards')) { ?>none<? } ?>;">
	  			If you are satisfied with your entries, please click the "Complete Booking" button.
	  		</div>
	  	</li>
		</ol>
	  </fieldset>
	  </div>
	
	<input class="previous" type="submit" value="Previous Step" onclick="stepBack(); return false;">
	
	<input class="submit" type="submit" value="Complete Booking">
	
</div>

<div id="errors">Some required fields are missing.<br>Fields marked with a * are required.</div>

</form>

<script>
	var toComplete = 0;
	var response; // needs to be global to work in timeout
	var paypalAccount = 0;

	var ca_states = <?= json_encode( $site->getRegionList('ca') ); ?>;
	var us_states = <?= json_encode( $site->getRegionList('us') ); ?>;
	var au_states = <?= json_encode( $site->getRegionList('au') ); ?>;	
	
	// catch form submissions
	jQuery('#book').submit(function (evt) {
		evt.preventDefault();
		submit_booking();
	});
	
	jQuery('#tour_country').change(function() {
		var country = jQuery(this).attr('value');
		jQuery('#tour_stateprov').removeOption(/.*/);
		switch (country) {
			case 'ca':
				jQuery('#tour_stateprov_txt').hide();
				jQuery('#tour_stateprov').addOption(ca_states, false).show();
				jQuery('#tour_stateprov_txt').val(jQuery('#tour_stateprov').val());
				break;
			case 'us':
				jQuery('#tour_stateprov_txt').hide();
				jQuery('#tour_stateprov').addOption(us_states, false).show();
				jQuery('#tour_stateprov_txt').val(jQuery('#tour_stateprov').val());
				break;
			case 'au':
				jQuery('#tour_stateprov_txt').hide();
				jQuery('#tour_stateprov').addOption(au_states, false).show();
				jQuery('#tour_stateprov_txt').val(jQuery('#tour_stateprov').val());
				break;		
			default:
				jQuery('#tour_stateprov').hide();
				jQuery('#tour_stateprov_txt').val('');
				jQuery('#tour_stateprov_txt').show();
				break;			
		}
	});
	
	jQuery('#tour_stateprov').change(function() {
		var state = jQuery(this).attr('value');
		jQuery('#tour_stateprov_txt').val(state);
	});		
	
	<? if (in_array($site->getCompanyCountry(), array('ca', 'us', 'au'))) { ?>
		jQuery('#tour_stateprov').addOption(<?=$site->getCompanyCountry();?>_states, false);
		jQuery('#tour_stateprov_txt').val(jQuery('#tour_stateprov').val());
	<? } ?>
	
	function close_modal() {
		jQuery('#prompt').data("overlay").close();
	}
	
	if (typeof String.prototype.trim != 'function') { // detect native implementation
	  String.prototype.trim = function () {
	    return this.replace(/^\s+/, '').replace(/\s+$/, '');
	  };
	}
	
	// change the modal dialog box or pass the user to the receipt depending on the response
	function show_response()  {
		
		response = response.trim();
		
		if(response == '2') {
			var body = '<h2>No Availability Left</h2>Sorry, there is not enough availability left for this item on this date.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
		} else if(response == '3') {
			var body = '<h2>Payment Error</h2>Sorry, your payment could not be completed. Please verify your card details and try again.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
		} else if(response == '4') {
			var body = '<h2>Booking Error</h2>Sorry, there has been an error with your booking and it can not be completed at this time.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
		} else if(response == '5') {
			// this error should only come up in preview mode without a valid payment method set
			var body = '<h2>Booking Error</h2>Sorry, you must have a payment method attached to your Rezgo Account in order to complete a booking.<br><br>Please go to "Settings > My Rezgo Account" to attach a payment method.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
		} else {
			
			// this section is mostly for debug handling
			if(response.indexOf('STOP::') != -1) {	
				var split = response.split('<br><br>');
				if(split[1] == '2' || split[1] == '3' || split[1] == '4') {
					split[1] = '<br><br>Error Code: ' + split[1] + '<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
				} else {
					split[1] = '<br><br>BOOKING COMPLETED WITHOUT ERRORS<br><br><button type="button" class="close" onclick="close_modal();">Close This</button><br><br><button type="button" class="close" onclick="window.location.replace(\'<?=$site->base?>/complete/' + split[1] + '\');">Contine to Receipt</button>';
				}
			
				var body = 'DEBUG-STOP ENCOUNTERED<br><br>' + split[0] + split[1];
			} else {
				// send the user to the receipt page
				window.location.replace("<?=$site->base?>/complete/" + response);
				return true; // stop the html replace
			}
		}
		
		jQuery('#prompt').html(body);
	}
	
	// this function delays the output so we see the loading graphic
	function delay_response(responseText) {
		response = responseText;
		setTimeout('show_response();', 800);
	}
	
	function validate_form() {
		
		var valid = jQuery("#book").valid();
		
		return valid;
	}
	
	function error_booking() {
		jQuery('#errors').fadeIn();
		jQuery.scrollTo('#errors');
		setTimeout("jQuery('#errors').fadeOut();", 4000);
		return false;
	}
	
	function submit_booking() {
	
		// do nothing if we are on step 1
		if(toComplete == 0) return false;
		
		var validate_check = validate_form();
		
		// only activate on actual form submission, check payment info
		if(toComplete == 1 && overall_total != 0) {
		
			var force_error = 0;
			
			var payment_method = jQuery('input:radio[name=payment_method]:checked').val();				
			
			if(payment_method == 'Credit Cards') {
			
				
				if(!jQuery('#tour_payment').contents().find('#payment').valid()) {
					force_error = 1;
				}
				
			} else {
				// other payment methods need their additional fiends filled
				var id = jQuery('input:radio[name=payment_method]:checked').attr('id');
				if(jQuery('#' + id + '_field').length != 0 && !jQuery('#' + id + '_field').val()) { // this payment method has additional data that is empty
					force_error = 1;
					jQuery('#' + id + '_container').css('border-color', '#990000');
				}
			}
		}
					
		if(force_error || !validate_check) {
			return error_booking();
		} else {
			if(toComplete == 1) {
				
				jQuery('#prompt').html('<h2>Your booking is being completed</h2><br><center><img src="<?=$site->path?>/images/booking_load.gif"><br><br>Please wait a moment...</center>');
			
				jQuery('#prompt').overlay({
					mask: {
						color: '#FFFFFF',
						loadSpeed: 200,
						opacity: 0.75
					},
					closeOnEsc : false, 
					closeOnClick: false
				});
				
				// open the overlay this way, rather than load:true in the overlay itself
				// so that it will be forced to open again even if it already exists
				jQuery('#prompt').data("overlay").load();
				
				var payment_method = jQuery('input:radio[name=payment_method]:checked').val();
				
				if(payment_method == 'Credit Cards' && overall_total != 0) {
					// clear the existing credit card token, just in case one has been set from a previous attempt
					jQuery('#tour_card_token').val('');
					
					// submit the card token request and wait for a response
					jQuery('#tour_payment').contents().find('#payment').submit();
					
					// wait until the card token is set before continuing (with throttling)
					
					function check_card_token() {
						var card_token = jQuery('#tour_card_token').val();
						if(card_token == '') {
							// card token has not been set yet, wait and try again
							setTimeout(function() {
								check_card_token();
							}, 200);
						} else {
							
							// the field is present? submit normally								
							jQuery('#book').ajaxSubmit({ 
								url: '<?=$site->base?>/book_ajax.php', 
								data: { rezgoAction: 'book' }, 
								success: delay_response, 
								error: function() {
									var body = '<h2>Booking Error</h2><br>Sorry, the system has suffered an error that it can not recover from.<br><br>Please try again later.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
									jQuery('#prompt').html(body);
								}
							});
							
						}
					}
					
					check_card_token();	
				} else {
										
					// not a credit card payment (or $0) and everything checked out, submit via ajaxSubmit (jquery.form.js)					
					jQuery('#book').ajaxSubmit({ 
						url: '<?=$site->base?>/book_ajax.php', 
						data: { rezgoAction: 'book' }, 
						success: delay_response, 
						error: function() {
							var body = '<h2>Booking Error</h2><br>Sorry, the system has suffered an error that it can not recover from.<br><br>Please try again later.<br><br><button type="button" class="close" onclick="close_modal();">Close This</button>';
							jQuery('#prompt').html(body);
						}
					});

				}
				
				// return false to prevent normal browser submit and page navigation 
				return false; 
				
			}
		}
		
	}
	
	
	function stepForward() {
	
		if(!validate_form()) return error_booking();
	
		toComplete = 1;
				
		jQuery('#errors').fadeOut();
	
		document.getElementById("step_1").setAttribute("class", "off");
		document.getElementById("step_2").setAttribute("class", "on");
		
		jQuery('#content_1').hide();
		jQuery('#content_2').fadeIn();
		
		jQuery.scrollTo('#panel_full');
		
		jQuery("#tour_first_name").addClass("required");
		jQuery("#tour_last_name").addClass("required");
		
		jQuery("#tour_address_1").addClass("required");
		jQuery("#tour_city").addClass("required");
		jQuery("#tour_country").addClass("required");
		jQuery("#tour_postal_code").addClass("required");
		
		jQuery("#tour_phone_number").addClass("required");
		jQuery("#tour_email_address").addClass("required");
		
		jQuery("#agree_terms").addClass("required");
		
	}
	
	function stepBack() {
		toComplete = 0;
		
		document.getElementById("step_2").setAttribute("class", "off");
		document.getElementById("step_1").setAttribute("class", "on");
		
		<? if($site->getPaymentMethods('PayPal')) { ?>
			paypalAccount = 0; // set to 0 to let the page know we need an account
		
			jQuery('#payment_paypal').fadeOut();
			jQuery('#payment_method_paypal').attr('checked', false);
			<? if(!$site->exists($site->getCompanyPaypal())) { ?>
			jQuery('#payment_paypal').html('<br><br><img src="<?=$site->path?>/images/booking_load.gif">');
			<? } ?>
			
			jQuery('#paypal_token').val('');
			jQuery('#paypal_payer_id').val('');		
		<? } ?>
		
		jQuery('#content_2').hide();
		jQuery('#content_1').fadeIn();
		
		jQuery("#tour_first_name").removeClass("required");
		jQuery("#tour_last_name").removeClass("required");
		
		jQuery("#tour_address_1").removeClass("required");
		jQuery("#tour_city").removeClass("required");
		jQuery("#tour_country").removeClass("required");
		jQuery("#tour_postal_code").removeClass("required");
		
		jQuery("#tour_phone_number").removeClass("required");
		jQuery("#tour_email_address").removeClass("required");
		
		jQuery("#agree_terms").removeClass("required");
	}
	
	function toggleCard() {
		if(jQuery('input[name=payment_method]:checked').val() == 'Credit Cards') {
			<? $pmn = 0; ?>
			<? foreach( $site->getPaymentMethods() as $pay ) { ?>	
				<? if($pay[name] == 'Credit Cards') { ?>
				<? } elseif($pay[name] == 'PayPal' && !$site->exists($site->getCompanyPaypal())) { ?>
					jQuery('#payment_paypal').fadeOut();
				<? } else { ?>
					<? $pmn++; ?>
					jQuery('#payment_method_<?=$pmn?>_box').fadeOut();
					jQuery('#payment_method_<?=$pmn?>_field').attr('disabled', 'disabled');
				<? } ?>
			<? } ?>	
			
			setTimeout(function() {
				jQuery('#payment_cards').fadeIn();
			}, 450);
			
			document.getElementById("terms_other").style.display = 'none';
			document.getElementById("terms_credit_card").style.display = '';			
		} else if(jQuery('input[name=payment_method]:checked').val() == 'PayPal') {
			<? $pmn = 0; ?>
			<? foreach( $site->getPaymentMethods() as $pay ) { ?>	
				<? if($pay[name] == 'Credit Cards') { ?>
					jQuery('#payment_cards').fadeOut();
				<? } elseif($pay[name] == 'PayPal' && !$site->exists($site->getCompanyPaypal())) { ?>
				<? } else { ?>
					<? $pmn++; ?>
					jQuery('#payment_method_<?=$pmn?>_box').fadeOut();
					jQuery('#payment_method_<?=$pmn?>_field').attr('disabled', 'disabled');
				<? } ?>
			<? } ?>	
			
			setTimeout(function() {
				jQuery('#payment_paypal').fadeIn();
			}, 450);
			
			document.getElementById("terms_credit_card").style.display = 'none';
			document.getElementById("terms_other").style.display = '';
			
		} else {
			<? $pmn = 0; ?>
			<? foreach( $site->getPaymentMethods() as $pay ) { ?>	
				<? if($pay[name] == 'Credit Cards') { ?>
					jQuery('#payment_cards').fadeOut();
				<? } elseif($pay[name] == 'PayPal' && !$site->exists($site->getCompanyPaypal())) { ?>
					jQuery('#payment_paypal').fadeOut();
				<? } else { ?>
					<? $pmn++; ?>
					jQuery('#payment_method_<?=$pmn?>_box').fadeOut();
					jQuery('#payment_method_<?=$pmn?>_field').attr('disabled', 'disabled');
				<? } ?>
			<? } ?>	
			
			setTimeout(function() {
				var id = jQuery('input[name=payment_method]:checked').attr('id');
				jQuery('#' + id + '_box').fadeIn();
				jQuery('#' + id + '_field').attr('disabled', false);
			}, 450);
			
			document.getElementById("terms_credit_card").style.display = 'none';
			document.getElementById("terms_other").style.display = '';
		
		}
		
	}
	
	// these functions do a soft-commit when you click on the paypal option so they
	// can get an express payment token from the paypal API via the XML gateway
	function getPaypalToken(force) {
		
		// if we aren't forcing it, don't load if we already have an id
		if(!force && paypalAccount == 1) {
			// an account is set, don't re-open the box
			return false;
		}
		
		jQuery('#book').ajaxSubmit({
			url: '<?=$site->base?>/book_ajax.php',
			data: { rezgoAction: 'get_paypal_token' }, 
			success: function(token) {
				jQuery('#payment_paypal').fadeOut();
				
				// this section is mostly for debug handling
				if(token.indexOf('STOP::') != -1) {
					var split = token.split('<br><br>');
					
					if(split[1] == '0') {
						alert('The system encountered an error with PayPal. Please try again in a few minutes or select another payment method.');
						return false;
					}
					
					token = split[1];
				}
				
				dg.startFlow("https://www.paypal.com/incontext?token=" + token.trim());
				
			}
		});
		
	}
	
	function paypalCancel() {
		// the paypal transaction was cancelled, uncheck the radio and close the box
		dg.closeFlow();
		jQuery('#payment_method_paypal').attr('checked', false);
	}
	
	function paypalConfirm(token, payerid, name, email) {
		// the paypal transaction was completed, show us the details and fade in the box
		dg.closeFlow();
		
		if(token == 0) {
			token = '';
			payerid = '';
			var string = 'There appears to have been an error with your transaction<br>Please try again.';
		} else {	
			var string = '<div class="payment_info" style="width:280px; padding:8px 0 8px 0;">Using PayPal Account: <span style="color:#000;">' + name + '<br>' + email + '</span><br><br><a href="javascript:void(0);" onclick="getPaypalToken(1);">Use a different account to pay</a></div>';	
			paypalAccount = 1; // set to 1 to let the page know we have an account on file
		}
			
		jQuery('#payment_paypal').html(string);
		jQuery('#payment_paypal').fadeIn();
		
		jQuery('#paypal_token').val(token);
		jQuery('#paypal_payer_id').val(payerid);
	}
	
	function creditConfirm(token) {
		// the credit card transaction was completed, give us the token
		jQuery('#tour_card_token').val(token);
	}
	
	// this function checks through each element on the form, if that element is
	// a checkbox and has a price value and is checked (thanks to browser form retention)
	// then we go ahead and add that to the total like it was clicked
	function saveForm(form) {
	  jQuery(':input', form).each(function() {
	    if (this.type == 'checkbox' && this.checked == true) {
	    	var split = this.id.split("|");
	    	// if the ID contains a price value then add it
	    	if(split[2]) add_element(split[0], split[1], split[2], split[3]);
	    }
	   });
	};
	
	saveForm('#book');
</script>

</div><!-- end of panel_full--> 
<div class="clear"></div> <!-- do not take this out -->

<div id="rezgo_footer">

<!-- Rezgo logo -->
<div id="rezgo_logo"><a href="http://www.rezgo.com" target="_blank" title="powered by rezgo">powered by<img src="<?=$site->path?>/images/logo_rezgo.gif" border="0" alt="Rezgo" /></a></div>
<!-- Rezgo logo -->

</div>

</div><!--end rezgo wrp-->

<script>
	navigator.__defineGetter__('userAgent', function(){
		return( "Full Client" );
	});
	var navigator = new Object; 
	navigator.userAgent = 'Full Client';
</script>

<script src="https://www.paypalobjects.com/js/external/dg.js"></script>
<script>var dg = new PAYPAL.apps.DGFlow();</script>


