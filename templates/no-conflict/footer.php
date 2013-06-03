<?php
	// this is your footer template, you can either grab the Rezgo footer from XML or create your own here
	
	echo $site->getAnalytics();
?>


<? if(!$site->config('REZGO_HIDE_HEADERS')) { ?>
	<?=$site->getFooter()?>
<? } ?>

<? 
	if (
	$_SERVER['SCRIPT_NAME'] == '/book.php' || 
	$_SERVER['REQUEST_URI'] == '/?search=restore'
	) {
		$mobile_url = $_SERVER['REQUEST_URI'].'&show_standard=off';
	} elseif (
	$_SERVER['SCRIPT_NAME'] == '/about.php' || 
	$_SERVER['SCRIPT_NAME'] == '/contact.php' || 
	$_SERVER['REQUEST_URI'] == '/?show_standard=off' || 
	($_SERVER['SCRIPT_NAME'] == '/index.php' && $_SERVER['REQUEST_URI'] == '/')
	) {
		$mobile_url = '/?show_standard=off';
	} else {
		$mobile_url = str_replace('/?show_standard=on', '', $_SERVER['REQUEST_URI']).'/?show_standard=off';
	}
	
?>
<? if($_COOKIE['show_standard']) { ?>
	  <div style="text-align:center"><br /><a href="<?=$mobile_url?>" rel="external">view mobile site</a><br /><br /><!--<?=$mobile_url?>--></div>
<? } ?>