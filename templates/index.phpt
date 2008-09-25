<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Browser Games Hub</title>
		
		<link href="<?=BASE_URL?>css/dirtylicious.css" rel="stylesheet" type="text/css" />
		<link href="<?=BASE_URL?>css/style.css" rel="stylesheet" type="text/css" />
		
		<link rel="stylesheet" href="<?=BASE_URL?>css/lightbox.css" type="text/css" media="screen" />
		
		<script type="text/javascript">
			const BASE_URL = '<?=BASE_URL?>';
		</script>
	
		<script src="<?=BASE_URL?>js/prototype.js" type="text/javascript"></script>
		<script src="<?=BASE_URL?>js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
		<script src="<?=BASE_URL?>js/lightbox.js" type="text/javascript"></script>
		
		<meta name="verify-v1" content="To8H6ax30bEBO6IfgQn/lByRc8Ua2KiWowemwsRA83A=" />
		
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		var pageTracker = _gat._getTracker("UA-459768-15");
		pageTracker._trackPageview();
		</script>
	</head>
	
	<body>
		<div class="outer-container">
		<div class="inner-container">
	
			<div class="header">
				<div class="title">
					<span class="sitename"><a href="<?=BASE_URL?>">Browser Games Hub</a></span>
					<div class="slogan">Join the semantic browser games web</div>
				</div>
			</div>
			
			<div class="main">		
		
				<div class="content">
					<?=$content?>
				</div>
				
				<div class="navigation">
					<?=$navigation?>
				</div>
				<div class="clearer">&nbsp;</div>
				
			</div>
			
			<div class="footer">

				<span class="left">
					&nbsp;
				</span>

				<span class="right">Design by <a href="http://arcsin.se/">Arcsin</a> <a href="http://templates.arcsin.se/">Web Templates</a></span>

				<div class="clearer"></div>

			</div>
		
		</div>
		</div>
	</body>
</html>
