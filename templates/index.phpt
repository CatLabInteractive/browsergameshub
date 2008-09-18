<html>
	<head>
		<title>Browser Games Hub</title>
		<link href="<?=BASE_URL?>css/style.css" rel="stylesheet" type="text/css" />
		
		<link rel="stylesheet" href="<?=BASE_URL?>css/lightbox.css" type="text/css" media="screen" />
		
		<script type="text/javascript">
			const BASE_URL = '<?=BASE_URL?>';
		</script>
	
		<script src="<?=BASE_URL?>js/prototype.js" type="text/javascript"></script>
		<script src="<?=BASE_URL?>js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
		<script src="<?=BASE_URL?>js/lightbox.js" type="text/javascript"></script>
	</head>
	
	<body>
		<div id="container">
			<h1>Browser Games Hub</h1>
			<div id="navigation"><?=$navigation?></div>
			<div id="content"><?=$content?></div>
			
			<div style="clear: both;"></div>
		</div>
	</body>
</html>
