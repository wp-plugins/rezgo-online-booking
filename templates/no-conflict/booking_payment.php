<?
	if($_REQUEST['rezgoAction'] == 'return') {
		echo '<script>parent.creditConfirm("'.$site->requestStr('token').'");</script>';
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<link media="all" href="<?=$this->path?>/header.css" type="text/css" rel="stylesheet">
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="<?=$this->path?>/header_ie.css" />
<![endif]-->

<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?=$site->path?>/javascript/jquery.validate.min.js"></script>

<? if($site->exists($site->getStyles())) { ?>
<style>
<!--

	<?=$site->getStyles()?>

-->
</style>
<? } ?>

</head>
<body style="margin:0px; padding:0px; background:#ffffff;">
	
	<script>
		function check_valid() {
		
			var valid = jQuery("#payment").valid();
			
			return valid;
		}
	</script>
	
	<form method="post" id="payment" action="https://process.rezgo.com/form">
	
	<input type="hidden" name="return" value="https://<?=$_SERVER['HTTP_HOST'].REZGO_URL_BASE?>/booking_payment.php?rezgoAction=return&">
	
	<div id="rezgo" style="width:310px; margin:0px; padding:0px; border-radius:0px; background:#FFF;">
	
	<div class="payment_info" id="payment_card_info" style="margin:0px; width:100%; padding:5px;">
					
		<div id="payment_card_content">
			<ol>
				
				<li><label class="left">Cardholder Name<em></em></label><input type="text" id="name" name="name" value="" style="width:150px;" required="required" /></li>
				<li><label class="left">Card Number<em></em></label><input type="text" id="pan" name="pan" value="" style="width:150px;" required="required" /></li>
				<li>
					<label class="left">Card Expiry<em></em></label>
					<select name="exp_month" id="exp_month" style="position:relative; top:-5px;">
						<option value="01">01</option>
						<option value="02">02</option>
						<option value="03">03</option>
						<option value="04">04</option>
						<option value="05">05</option>
						<option value="06">06</option>
						<option value="07">07</option>
						<option value="08">08</option>
						<option value="09">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
					<select name="exp_year" id="exp_year" style="position:relative; top:-5px;">
						<? for($d=date("Y"); $d <= date("Y")+12; $d++): ?>
							<option value="<?=substr($d, -2)?>"><?=$d?></option>
						<? endfor; ?>
					</select>	
				</li>
		
				<? if($site->getCVV()) { ?>
				<li><label class="left" style="width:45%;">CVV Number<em></em></label><input type="text" name="cvv" id="cvv" value="" style="width:50px;"  required="required" />&nbsp;<a href="javascript:void(0);" onclick="javascript:window.open('<?=$site->path?>/images/cv_card.jpg',null,'width=600,height=300,status=no,toolbar=no,menubar=no,location=no');">what is this ?</a></li>
				<? } ?>
			</ol>
			
		</div>
			
		</div>
	
	</div>
	
	</form>

</body>
</html>