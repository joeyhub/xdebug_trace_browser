<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__.'/../../inc/boot.inc.php');
$config = require(__DIR__.'/../../inc/config.inc.php');
$projects = $config['projects'];

if(!isset($_REQUEST['filename'])
|| !preg_match('/[.]php$/', $_REQUEST['filename']))
	throw new Exception('Bad 1');

$path = false;
$real_path = false;
foreach($projects as $k => $v)
	if(preg_match($p = '#^'.preg_quote($k).'/#', $_REQUEST['filename']))
	{
		$real_path = realpath(preg_replace($p, $v.'/', $_REQUEST['filename']));
		break;
	}

if($real_path === false)
	throw new Exception('Bad 2');

// Cross project access allowed.
foreach($projects as $file_path)
{
	if(strpos($real_path, $file_path.'/') !== 0)
		continue;

	$path = $real_path;
	break;
}

if($path === false)
		throw new Exception('Bad 3');

if(isset($_REQUEST['lines']))
{
	$lines = [];

	foreach(explode("\n", file_get_contents($path)) as $i => $line)
		$lines[] = ['line' => $i, 'code' => $line];

	echo json_encode(['lines' => $lines]);
}
else
	highlight_file($path);