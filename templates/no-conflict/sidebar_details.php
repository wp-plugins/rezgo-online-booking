<!--right side -->
<div id="right_panel">
	
	<? if($site->getCartState()) { ?>
		<?=$site->getTemplate('sidebar_order')?>
	<? } ?>
	
	<? if($site->getTriggerState()) { ?>
		<div id="promo">
			<h1>Have a promotional code?</h1>
			<? if($_SESSION['rezgo_promo']) { ?>
			<div id="promo_entered_sidebar" class="promo_entered_sidebar">
				<?=$_SESSION['rezgo_promo']?>
				<a href="javascript:void(0);" onclick="jQuery('#promo_entered_sidebar').hide(); jQuery('#promo_hidden_sidebar').fadeIn();">[change]</a>
			</div>
			<? } ?>
			
			<div id="promo_hidden_sidebar" class="promo_hidden_sidebar" <? if($_SESSION['rezgo_promo']) { ?>style="display:none;"<? } ?>>
				<form class="item" onsubmit="document.location.href = '<?=$_SERVER['REQUEST_URI']?><?=((strpos($_SERVER['REQUEST_URI'], '?') !== false) ? '&' : '?')?>promo=' + jQuery('#promo_sidebar').val(); return false;">
		  		<input type="text" class="promo_input_sidebar" name="promo" id="promo_sidebar" value="<?=$_SESSION['rezgo_promo']?>">
					<input type="submit" class="promo_submit_sidebar" value="apply">
				</form>
			</div>
		</div>
	<? } ?>
		
	<!-- calendar start -->
	<div id="calendar">
		<h1>Click a date to book</h1>
		
		<div class="legend">
			<div class="legend_item"><div class="legend_available"><span>Available</span></div></div>
			<div class="legend_item"><div class="legend_unavailable"><span>Unavailable</span></div></div>
			<div class="legend_item"><div class="legend_full"><span>Full</span></div></div>
		</div>
		
		<div class="legend_memo" id="legend_memo"></div>
		
		<p class="item" id="calendar_content">
		
		</p>
		
		<? if(!$_REQUEST['option'] && !$_REQUEST['date']) { ?><div id="calendar_marker" style="display:none;" onclick="remove_arrow();"></div><? } ?>
	   
	  <script>
			function change_cal(url) {
				close_overlay();
				
				// this it the loading graphic
				jQuery('#calendar_content').html('<table border=0 cellspacing=0 cellpadding=0 id="calendar_container" class="calendar_load"><tr><td align=center valign=center><img src="<?=$site->path?>/images/loader.gif"></td></tr></table>');
				
				jQuery('#calendar_content').load('<?=$site->base?>/calendar.php?' + url);
			}
			
			function close_overlay() {
				// this function closes all overlays attached to a[rel] in #calendar_container
				jQuery("#calendar_container a[rel]").each(function() {
					jQuery(this).overlay().close();
				});
			
				// it also closes the "click to book" arrow, the fadein below acts after it
				// so it will only close the arrow on any close_overlay calls that follow
				remove_arrow();
			}
			
			function remove_arrow() {
				jQuery('#calendar_marker').fadeOut();
			}
		
			function next_cal_page(date, totalPages) {
				var current_page = jQuery('#page_' + date).html();
				if(current_page < totalPages) {
					jQuery('#cal_page_' + date + '_' + current_page).hide();
					jQuery('#cal_page_' + date + '_' + ++current_page).fadeIn();
					jQuery('#page_' + date).html(current_page);
				}
			}
			
			function prev_cal_page(date, totalPages) {
				var current_page = jQuery('#page_' + date).html();
				if(current_page > 1) {
					jQuery('#cal_page_' + date + '_' + current_page).hide();
					jQuery('#cal_page_' + date + '_' + --current_page).fadeIn();
					jQuery('#page_' + date).html(current_page);
				}
			}	
			
			change_cal('item_id=<?=$item->uid?>&date=<?=$site->requestStr('date')?>');	
		
			jQuery('#calendar_marker').delay(800).fadeIn();
		</script>
	  
	</div>
	<!-- calendar end-->
		
		<? 
		$gallery_count = $item->image_gallery->attributes()->value + $item->video_gallery->attributes()->value;
		$g = 0;
		if($gallery_count > 0) { ?>

		<!-- gallery start -->
		<div id="carousel">

			<h1>View images and videos</h1>
		
			<a class="prev browse left"<? if($gallery_count < 5) { ?> style="visibility:hidden;" <? } ?>></a>
    	
    	<div class="scrollable">
   
			  <!-- root element for the items -->
				<div class="items">
					
					<div>
			   		<? foreach( $site->getTourMedia($item) as $media ): ?>
			   			
			   			<? if($g == 4) { ?></div><div><? $g = 1; } else { $g++; } ?> 
			   			
			   			<? if($media->type == 'image') { ?>
			   				<a href="<?=$media->path?>" rel="gallery[gal]" title="<?=$media->caption?>">
			   					<img src="<?=$media->path?>">
			   				</a>
			        <? } ?> 
			         
			   		<? endforeach; ?>
			   	</div>
			   		
			   </div>
			   
			</div>
			
			<a class="next browse right"<? if($gallery_count < 5) { ?> style="visibility:hidden;" <? } ?>></a>  
    
    </div>
		<!-- gallery end--> 
    
  <? } ?>

	<? if($site->exists($item->lat)) { ?>

	<!-- map start -->
	<div id="map">
		<? if($site->exists($item->lat)) { ?>
    <h1>Location map</h1>
    <div style="width:90%; border:2px solid #CCC; margin:5px auto;">
      <? if (!$site->exists($item->zoom)) { $map_zoom = 8; } else { $map_zoom = $item->zoom; } ?>
        <iframe width="100%" height="300" frameborder="0" style="border:0;margin-bottom:0;" src="https://www.google.com/maps/embed/v1/view?key=AIzaSyCqFNdI5b319sgzE3WH3Bw97fBl4kRVzWw&center=<?=$item->lat?>,<?=$item->lon?>&zoom=<?=$map_zoom?>"></iframe></div>
    <? } ?>
	</div>
	<!-- map end-->

	<? } ?>

</div><!--end right panel-->

<div class="clear"></div>

<? if($_COOKIE['rezgo_refid_val']) { ?>
	<div id="refid">
		RefID: <?=$_COOKIE['rezgo_refid_val']?>
	</div>
<? } ?>

<!-- Rezgo logo DO NOT DELETE -->
<div id="rezgo_logo"><a href="http://www.rezgo.com" target="_blank" title="powered by rezgo">powered by<img src="<?=$site->path?>/images/logo_rezgo.gif" border="0" alt="Rezgo" /></a></div>
<!-- Rezgo logo DO NOT DELETE -->
</div><!--end rezgo wrp-->