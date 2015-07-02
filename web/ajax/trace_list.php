<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__.'/../../inc/boot.inc.php');
$config = require(__DIR__.'/../../inc/config.inc.php');
$trace_path = $config['trace_path'];

$traces = [];
$dh = opendir($trace_path);

while(($entry = readdir($dh)) !== false)
{
	$full_path = $trace_path.'/'.$entry;

	if(is_dir($full_path)
	|| !preg_match('/\.xt$/', $entry))
		continue;

	$traces[] = [
		'name' => $entry,
		'mtime' => date('Y-m-d\TH:i:s', filemtime($full_path)),
		'size' => filesize($full_path)
	];
}

closedir($dh);

echo json_encode(['traces' => $traces]);