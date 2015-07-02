<?php
$config = require(__DIR__.'/../../inc/config.inc.php');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Xdebug</title>
		<link rel="stylesheet" type="text/css" href="inc/css/desktop.css" />
		<script type="text/javascript" src="inc/js/include-ext.js"></script>
		<script type="text/javascript" src="inc/js/options-toolbar.js"></script>

		<script type="text/javascript">
Ext.Loader.setPath({
	'Ext.ux.desktop': 'inc/js/dt',
	MyDesktop: 'inc/js/md'
});

Ext.require('MyDesktop.App');

var myDesktopApp;
Ext.onReady(function () {
	myDesktopApp = new MyDesktop.App();
});
		</script>
		<style type="text/css"><?= $config['css'] ?></style>
	</head>

	<body>
	</body>
</html>