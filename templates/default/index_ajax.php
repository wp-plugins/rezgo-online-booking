<div id="rezgo_container_<?=$site->requestNum('pg')?>" style="display:none;">

<!-- div id="rezgo" class="wrp_list" >

<div id="left_panel"-->
	
	
	<? if($_REQUEST['pg'] == 1) { ?>
		
		<div class="breadcrumb">	
			<? if($_REQUEST['search_for'] OR $_REQUEST['start_date'] OR $_REQUEST['end_date'] OR $_REQUEST['tags'] OR $_REQUEST['cid']) { ?>
				<h1 class="header">
				Results
				<? if($_REQUEST['search_for']) { ?> for keyword <a href="<?=$site->base?>/?start_date=<?=$site->requestStr('start_date')?>&end_date=<?=$site->requestStr('end_date')?>">"<?=stripslashes($_REQUEST['search_for'])?>"</a><? } ?>
				<? if($_REQUEST['tags']) { ?> tagged with <a href="<?=$site->base?>/?start_date=<?=$site->requestStr('start_date')?>&end_date=<?=$site->requestStr('end_date')?>">"<?=$site->requestStr('tags')?>"</a><? } ?>
				<? if($_REQUEST['cid']) { ?> supplied by <a href="<?=$site->base?>/?start_date=<?=$site->requestStr('start_date')?>&end_date=<?=$site->requestStr('end_date')?>">"<?=$site->getCompanyName($_REQUEST['cid'])?>"</a><? } ?>
				<? if($_REQUEST['start_date'] AND $_REQUEST['end_date']) { ?>
				 between <a href="<?=$site->base?>/?search_in=<?=$site->requestStr('search_in')?>&search_for=<?=$site->requestStr('search_for')?>&tags=<?=$site->requestStr('tags')?>"><?=$site->requestStr('start_date')?> and <?=$site->requestStr('end_date')?></a>
				<? } elseif($_REQUEST['start_date']) { ?>
				 on <a href="<?=$site->base?>/?search_in=<?=$site->requestStr('search_in')?>&search_for=<?=$site->requestStr('search_for')?>&tags=<?=$site->requestStr('tags')?>"><?=$site->requestStr('start_date')?></a>
				<? } elseif($_REQUEST['end_date']) { ?>
				 on <a href="<?=$site->base?>/?search_in=<?=$site->requestStr('search_in')?>&search_for=<?=$site->requestStr('search_for')?>&tags=<?=$site->requestStr('tags')?>"><?=$site->requestStr('end_date')?></a>
				<? } ?>
				</h1>
				<a href="<?=$site->base?>/" class="clear_search">clear</a>
			<? } else { ?>
				<h1 class="header">All Results</h1>
			<? } ?>
		</div>
		
	<? } else { ?>
	
		<fieldset style="clear:both; text-align:center; color:#999; border:0px; border-top:1px solid #999;">
			<legend align=center style="font-size:16px;">&nbsp;Page <?=$site->requestStr('pg')?>&nbsp;</legend>
		</fieldset>
		
	<? } ?>
	
	<? if(!$site->getTours()) { ?>
		<div class="item">Sorry, there were no results for your search.</div>
	<? } ?>
	
	<?		
		$tourList = $site->getTours();
		if($tourList[REZGO_RESULTS_PER_PAGE]) {
			$moreButton = 1;	
			unset($tourList[REZGO_RESULTS_PER_PAGE]);
		} else { $moreButton = 0; }
	?>

	<? foreach( $tourList as $item ): ?>
	
	<? $site->readItem($item) ?>

	<? $unavailable_class = ($site->requestStr('start_date') AND count($site->getTourAvailability($item)) == 0) ? 1 : 0; ?>

	<div class="item<?=(($unavailable_class) ? ' inventory_unavailable' : '')?>">
    <div class="image_new">
    	<a href="<?=$site->base?>/details/<?=$item->com?>/<?=$site->seoEncode($item->name)?>">
        
        <? if ($item->image_gallery->image[0]) { ?>
            <img src="<?=$item->image_gallery->image[0]->path?>" class="new_img" />
				<? } elseif(is_array(getimagesize('http://images.rezgo.com/items/'.$item->cid.'-'.$item->com.'.jpg'))) { ?>
            <img src="http://images.rezgo.com/items/<?=$item->cid?>-<?=$item->com?>.jpg" class="old_img" />
        <? } else { ?>
            <img src="<?=$site->path?>/images/no-image.png" class="no_img" />
        <? } ?>
        
    	</a>
    </div>
    <h1 class="tour_title"><a href="<?=$site->base?>/details/<?=$item->com?>/<?=$site->seoEncode($item->name)?>"><?=$item->name?></a></h1>
    <div class="location">
    	<span>Location:&nbsp;</span>
    	<?
    		unset($loc);
    		if($site->exists($item->city)) $loc[] = $item->city;
    		if($site->exists($item->state)) $loc[] = $item->state;
    		if($site->exists($item->country)) $loc[] = ucwords($site->countryName($item->country));
    	
    		if($loc) echo implode(', ', $loc);
    	?>
    </div>
    <div class="intro"><?=$item->details->introduction?></div>
    
    <? if($site->exists($item->starting)) { ?>
    	<div class="price"><span>Starting From:&nbsp;</span><?=$site->formatCurrency($item->starting)?></div>
    <? } ?>
    <div class="btn_details"><a href="<?=$site->base?>/details/<?=$item->com?>/<?=$site->seoEncode($item->name)?>">details</a></div>
    
    <? if( $_REQUEST['start_date'] AND count($site->getTourAvailability($item)) == 0 ) { ?>
    
    	<div id="date_search_result"><span class="unavail_option">no available options during this date range</span></div>
    
    <? } else if( $_REQUEST['start_date'] AND $site->getTourAvailability($item) ) { ?>
  		<!-- slider for searched items -->
		 	<div id="date_search_result"><span class="avail_option">available options</span>
				<div class="searched_item">
				
	    		<? foreach( $site->getTourAvailability($item) as $day ): ?>
	    		
	    			<?
							// some php for handling pagination of the options
							$c = $p = 1;
							$totalPages = ceil(count($day->items) / 7);
						?>
	    		
	    			<div class="search_date" id="day_<?=$day->id?>">
	    				<a href="#" class="day_select" rel="#content_<?=$day->id?>">
			     			<span class="date_box"><?=date("M d, Y", $day->date)?></span>
			     			<span class="avail">available</span>
			     			<span class="select">select</span>
		     			</a>
		     			<div class="overlay" id="content_<?=$day->id?>">
		     			
								<div id="rezgo_popup_book">
									<div class="header">
								  	<label>Availability for</label>
								    <h3 class="popup_title"><?=date("F d, Y", $day->date)?></h3>
										<h1 class="popup_title"><?=$item->name?></h1>
								  </div>
								  <div class="wrp">
								  	<div class="modal_titles">
								  		<div class="title_01">Option</div>
								  		<div class="title_02">Availability</div>
								  		<div class="title_03">&nbsp;</div>
										</div>
								
										<div id="cal_page_<?=$day->id?>_<?=$day->date?>_<?=$p++?>">
											<? foreach( $day->items as $option ): ?>
											
												<? if($c == 8) { ?>
													<? $c = 2; ?>
													</div>
													<div style="display:none;" id="cal_page_<?=$day->id?>_<?=$day->date?>_<?=$p++?>">
												<? } else { ?>
													<? $c++; ?>
												<? } ?>
											
										  	<div class="result_01"><?=$option->name?></div><div class="result_02"><? if($option->availability == 0) { ?>full<? } elseif($option->availability == '9999' OR $option->hide_availability == 1) { ?>available<? } else { ?><?=$option->availability?><? } ?></div><div class="result_03"><span><a href="<?=$site->base?>/details/<?=$item->com?>/<?=$site->seoEncode($item->name)?>/<?=$option->uid?>/<?=date("Y-m-d", $day->date)?>">book now</a></span></div>
									  		
									  	<? endforeach; ?>
							  		</div>
							  		
								  </div>
								  <div class="paging"><a href="javascript:void(0);" onclick="prev_cal_page('<?=$day->date?>', '<?=$day->id?>', <?=$totalPages?>);"><img src="<?=$site->path?>/images/arrow_left.png" border="0" /></a>page <span id="page_<?=$day->id?>_<?=$day->date?>">1</span> of <?=$totalPages?><a href="javascript:void(0);" onclick="next_cal_page('<?=$day->date?>', '<?=$day->id?>', <?=$totalPages?>);"><img src="<?=$site->path?>/images/arrow_right.png" border="0" /></a></div>
								</div>
							
							</div>

		     		</div>
		     		
		     		<script>
		     			
		     		
		     			if($.browser.msie && $.browser.version < 8) {
		     				$("#day_<?=$day->id?> a[rel]").overlay({closeOnClick: true});
		     			} else {
		     				$("#day_<?=$day->id?> a[rel]").overlay({effect: 'apple', closeOnClick: true});
		     			}
		     		</script>
							
					<? endforeach; ?>
					
				</div><!-- <?=__LINE__?> -->
			</div> <!-- <?=__LINE__?> -->
		<? } ?>
	
 	</div><!-- <?=__LINE__?> -->
 	
 	<? endforeach; ?>

|||<?=$moreButton?>