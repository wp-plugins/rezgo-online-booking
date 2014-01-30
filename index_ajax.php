<?php 
	// this file needs to be handled separately since we fetch it via AJAX and don't 
	// want to include any of the wordpress header or footer content
	
	// include wp-blog-header.php to get access to everything
	require_once( '../../../wp-blog-header.php' );
	
	// send 200 response to prevent 404 ajax error (this is a wordpress quirk)
	header("HTTP/1.1 200 OK");
	
	// start a new instance of RezgoSite
	$site = new RezgoSite();
	
	// save the current search to a cookie so we can return to it
	if($site->requestStr('search') != 'restore') {
		$site->saveSearch();
	}
	
	// some code to handle the pagination
	if(!$site->requestNum('pg')) $_REQUEST['pg'] = 1;
	
	$start = ($site->requestNum('pg') - 1) * REZGO_RESULTS_PER_PAGE;
	
	// we only want 11 responses, starting at our page number times item number
	$site->setTourLimit(REZGO_RESULTS_PER_PAGE + 1, $start);
	
	echo $site->getTemplate('index_ajax');