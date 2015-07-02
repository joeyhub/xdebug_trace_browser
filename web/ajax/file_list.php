<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '1G');
require_once(__DIR__.'/../../inc/boot.inc.php');
$config = require(__DIR__.'/../../inc/config.inc.php');
$projects = $config['projects'];

$root = ['id' => 'Projects', 'text' => 'Projects', 'expanded' => true, 'children' => []];

function scan_dir($path, $project)
{
	global $projects;
	$children = [];
	$dh = opendir($path);

	while(($entry = readdir($dh)) !== false)
	{
		if(preg_match('/^[.]/', $entry))
			continue;

		$entry_path = $path.'/'.$entry;

		if(is_dir($entry_path))
		{
			$entry_children = scan_dir($entry_path, $project);

			if(!empty($entry_children))
				$children[] = ['text' => $entry, 'id' => $entry_path, 'lines' => [], 'children' => $entry_children];
		}
		elseif(preg_match('/[.]php$/', $entry))
		{
			$lines = [];

			foreach(explode("\n", file_get_contents($entry_path)) as $i => $line)
				$lines[] = ['line' => $i, 'code' => $line];

			$children[] = ['leaf' => true, 'id' => preg_replace('/^'.preg_quote($projects[$project], '/').'/', $project, $entry_path), 'text' => $entry, 'lines' => $lines];
		}
	}

	closedir($dh);
	return $children;
}

foreach($projects as $name => $path)
{
	$node = ['text' => $name];
	$children = scan_dir($path, $name);

	if(!empty($children))
		$node['children'] = $children;

	$root['children'][] = $node;
}

echo json_encode($root);