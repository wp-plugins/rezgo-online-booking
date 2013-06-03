<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.history.js"></script>

<div class="intro"><?=$site->getIntro()?></div>

<div id="rezgo" class="wrp_list">

<div id="left_panel">
	
	<form id="flight" onsubmit="tour_search($('#tour_start_date').val(), $('#tour_end_date').val()); return false;">
  	<label>
  		Start Date <br />  
   		<input type="date" name="start_date" id="tour_start_date" data-value="<?=(($_REQUEST['start_date']) ? date("Y-m-d", strtotime($_REQUEST['start_date'])) : 0)?>" value="<?=(($_REQUEST['start_date']) ? $_REQUEST['start_date'] : 'Today')?>" /> 
		</label>
   
		<label> 
   		End Date <br /> 
   		<input type="date" name="end_date" id="tour_end_date" data-value="<?=(($_REQUEST['end_date']) ? date("Y-m-d", strtotime($_REQUEST['end_date'])) : 1)?>" value="<?=(($_REQUEST['end_date']) ? $_REQUEST['end_date'] : 'Tomorrow')?>" /> 
		</label>
		
		<span class="date_apply"><input class="btn_search" type=submit value="apply"></span>
	</form>

	<script>
		$(function() {
			$(":date").dateinput({ trigger: true, format: 'mmmm dd, yyyy', min: -1 })
			
			// use the same callback for two different events. possible with bind
			$(":date").bind("onShow onHide", function()  {
				$(this).parent().toggleClass("active"); 
			});
			
			// when first date input is changed
			$(":date:first").data("dateinput").change(function() {
					
				// we use it's value for the seconds input min option
				$(":date:last").data("dateinput").setMin(this.getValue(), true);
			});
		});
	</script>
	
	
	<div id="rezgo_content_area"></div>
	
	
	<div id="rezgo_content_footer" style="clear:both; text-align:center; padding-top:15px;"></div>
	
 	
 	<script>
 		var start = 1;
		var search_start_date = '<?=$_REQUEST['start_date']?>';
		var search_end_date = '<?=$_REQUEST['end_date']?>';
		var search_tags = '<?=$_REQUEST['tags']?>';
		
		var search_in = '<?=$_REQUEST['search_in']?>';
		var search_for = '<?=$_REQUEST['search_for']?>';
		
		var cid = '<?=$_REQUEST['cid']?>';
		
		var load = '<?=$_REQUEST['pg']?>';
		
		function tour_search(start_date, end_date, tags, keywords) {
			
			search_start_date = start_date;
			search_end_date = end_date;
			
			$footer = $('#rezgo_content_footer'),
			
			opts = {
				offset: '100%'
			};
						
			$footer.waypoint('remove');
			
			var url_path = '';
			if(search_start_date) url_path += 'start_date=' + search_start_date;
			if(search_end_date) url_path += '&end_date=' + search_end_date;
			if(search_tags) url_path += '&tags=' + search_tags;
			if(search_in) url_path += '&search_in=' + search_in;
			if(search_for) url_path += '&search_for=' + search_for;
			if(cid) url_path += '&cid=' + cid;
			
			History.pushState(null, null, '<?=$site->base?>?' + url_path);
			
			start = 1;
			
			$('#rezgo_content_area').fadeOut('fast', function() {
			
				$('#rezgo_content_footer').html('<img src="<?=$site->path?>/images/load.gif">');
				
				$.ajax({
				  url: '<?=$site->base?>/index_ajax.php?load=' + load + '&pg=' + start + '&start_date=' + search_start_date + '&end_date=' + search_end_date + '&tags=' + search_tags + '&search_in=' + search_in + '&search_for=' + search_for + '&cid=' + cid,
				  context: document.body,
				  success: function(data) {
				  	
				  	$('#rezgo_content_footer').html('');
				  	
				  	var split = data.split('|||');
				  
				  	$('#rezgo_content_area').html(split[0]);
					  
					  $('#rezgo_content_area').fadeIn('slow');
					  
					  $('#rezgo_container_' + start).fadeIn('slow', function() {
						  
						  if(split[1] == 1) {
						  	start++;	
						  	$footer.waypoint(opts);
					  	}
					  	
					  });
					  
				  }
				});
				
			});
			
		}
 	
 	
 		$(function() {
 			
 			$footer = $('#rezgo_content_footer'),
			
			opts = {
				offset: '100%'
			};
						
			$footer.waypoint(function(event, direction) {
				
				$('#rezgo_content_footer').html('<img src="<?=$site->path?>/images/load.gif">');
			
				$footer.waypoint('remove');
				
				$.ajax({
				  url: '<?=$site->base?>/index_ajax.php?pg=' + start + '&start_date=' + search_start_date + '&end_date=' + search_end_date + '&tags=' + search_tags + '&search_in=' + search_in + '&search_for=' + search_for + '&cid=' + cid,
				  context: document.body,
				  success: function(data) {
				  	
				  	$('#rezgo_content_footer').html('');
				  	
				  	var split = data.split('|||');
				  	
				  	$('#rezgo_content_area').append(split[0]);
					  
					  $('#rezgo_container_' + start).fadeIn('slow', function() {
						  
						  if(split[1] == 1) {
						  	start++;	
						  	$footer.waypoint(opts);
					  	}
					  	
					  });
					  
				  }
				});
				
			}, opts);
			
 			
 			
 			
 		});
 	
 	
		function next_cal_page(date, id, totalPages) {
			var current_page = $('#page_' + id + '_' + date).html();
			if(current_page < totalPages) {
				$('#cal_page_' + id + '_' + date + '_' + current_page).hide();
				$('#cal_page_' + id + '_' + date + '_' + ++current_page).fadeIn();
				$('#page_' + id + '_' + date).html(current_page);
			}
		}
		
		function prev_cal_page(date, id, totalPages) {
			var current_page = $('#page_' + id + '_' + date).html();
			if(current_page > 1) {
				$('#cal_page_' + id + '_' + date + '_' + current_page).hide();
				$('#cal_page_' + id + '_' + date + '_' + --current_page).fadeIn();
				$('#page_' + id + '_' + date).html(current_page);
			}
		}
	</script>
 	
 	<? if($backButton) { ?>
	 	<div class="prev_results">
	 		<a href="?pg=<?=($_REQUEST['pg']-1)?><? if($_REQUEST['start_date']) { ?>&start_date=<?=$_REQUEST['start_date']?>&end_date=<?=$_REQUEST['end_date']?><? } ?>">previous page</a> | jump to page:  
	 		<? for($p=1; $p < $_REQUEST['pg']; $p++) { ?>
	 			<a href="?pg=<?=$p?><? if($_REQUEST['start_date']) { ?>&start_date=<?=$_REQUEST['start_date']?>&end_date=<?=$_REQUEST['end_date']?><? } ?>"><?=$p?></a>
	 		<? } ?>
	 	</div>
 	<? } ?>
 	
 	<? if($moreButton) { ?>
	 	<div class="more_results">
	 		<a href="?pg=<?=$nextPage?><? if($_REQUEST['start_date']) { ?>&start_date=<?=$_REQUEST['start_date']?>&end_date=<?=$_REQUEST['end_date']?><? } ?>">more results</a>
	 	</div>
 	<? } ?>
 	
</div>