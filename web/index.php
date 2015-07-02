<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../inc/boot.inc.php');
$view = Frork\World::$TPL->getHandler('php');
$view->render('boot');
