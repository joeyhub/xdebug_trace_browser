<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '2G');
require_once(__DIR__.'/../../inc/boot.inc.php');
$config = require(__DIR__.'/../../inc/config.inc.php');
$trace_path = $config['trace_path'];
$projects = array_flip($config['projects']);

if(!isset($_GET['filename'])
|| !preg_match('/[.]xt$/', $_GET['filename']))
	throw new Exception('Bad');

$real_path = realpath($trace_path.'/'.$_GET['filename']);

if($real_path === false)
	throw new Exception('Bad');

if(strpos($real_path, $trace_path) !== 0)
	throw new Exception('Bad');

$lines = explode("\n", file_get_contents($real_path));

if(empty($lines)
|| !preg_match('/^TRACE START/', $lines[0]))
	throw new Exception('Bad');

$symbols = [
	'->' => 'call',
	'=>' => 'assign',
	'>=>' => 'ret'
];

$root = (object)[
	'expanded' => true,
	'id' => 0,
	'children' => [],
	'info' => [
		'type' => 'trace',
		'lines' => count($lines),
		'file' => $_GET['filename']
	]
];
$stack = [$root];

for($i = 1; $i < count($lines); $i++)
{
	$line = $lines[$i];

	if(preg_match('/^TRACE END/', $line))
		break;

	if(preg_match('/^\s+(?P<time>[0-9]+[.][0-9]+)\s+(?P<memory>[0-9]+)$/', $line, $matches))
	{
		$stack[0]->info['time'] = $matches['time'];
		$stack[0]->info['memory'] = $matches['memory'];
		break;
	}

	if(!preg_match('/^(?P<stats>[ 0-9.]{24,24})(?P<depth>\s*)(?P<symbol>->|=>|>=>)(?P<remainder>.*)$/', $line, $matches))
		throw new Exception('Bad '.$line);

	$stats = $matches['stats'];
	$depth = $matches['depth'];
	$symbol = $matches['symbol'];
	$remainder = $matches['remainder'];

	if(preg_match('/^\s+(?P<time>[0-9]+[.][0-9]+)\s+(?P<memory>[0-9]+)\s+$/', $stats, $matches))
	{
		$time = $matches['time'];
		$memory = $matches['memory'];
	}

	switch($symbols[$symbol])
	{
		case 'call':
			if(!preg_match('/ (?P<function>[^(]+)[(](?P<params>.*?)[)] (?P<file>[^ :]+):(?P<line>[0-9]+)$/', $remainder, $matches))
				throw new Exception('Bad');

			$function = $matches['function'];
			$params = $matches['params'];
			$file = $matches['file'];
			$line = $matches['line'] ? $matches['line'] - 1 : 0;

			foreach($projects as $path => $name)
			{
				$oldfile = $file;
				$file = preg_replace('/^'.preg_quote($path, '/').'/', $name, $file);

				if($oldfile !== $file)
					break;
			}

			$node = (object)[
				'text' => $function,
				'id' => $i,
				'children' => [],
				'info' => [
					'type' => $symbols[$symbol],
					'line' => $line,
					'file' => $file,
					'params' => $params,
					'time' => $time,
					'memory' => $memory,
					'return' => null
				]
			];

			$d = strlen($depth);
			$d /= 2;
			$stack[$d]->children[] = $node;
			$d++;

			if($d < count($stack))
				$stack = array_slice($stack, 0, $d);

			$stack[] = $node;
			break;
		case 'assign':
			if(!preg_match('/^ (?P<variable>[^ ]+) (?P<operator>[^ ]+) (?P<value>.*?) (?P<file>[^ :]+):(?P<line>[0-9]+)$/', $remainder, $matches)
			&& !preg_match('/^ (?P<variable>[^ ]+)(?P<operator>[+][+]|--) (?P<file>[^ :]+):(?P<line>[0-9]+)$/', $remainder, $matches)
			&& !preg_match('/^ (?P<operator>[+][+]|--)(?P<variable>[^ ]+) (?P<file>[^ :]+):(?P<line>[0-9]+)$/', $remainder, $matches))
				throw new Exception('Bad '.$remainder);

			$variable = $matches['variable'];

			$operator = null;

			$operator = $matches['operator'];

			$value = isset($matches['value']) ? $matches['value'] : null;
			$file = $matches['file'];
			$line = $matches['line'] ? $matches['line'] - 1 : 0;

			foreach($projects as $path => $name)
			{
				$oldfile = $file;
				$file = preg_replace('/^'.preg_quote($path, '/').'/', $name, $file);

				if($oldfile !== $file)
					break;
			}

			$node = (object)[
				'text' => $variable,
				'id' => $i,
				'leaf' => true,
				'info' => [
					'type' => $symbols[$symbol],
					'line' => $line,
					'file' => $file,
					'operator' => $operator,
					'value' => $value,
					'time' => $time,
					'memory' => $memory
				]
			];

			$d = strlen($depth) - 1;
			$d /= 2;

			if(!$stack[$d]->id)var_dump($d, count($stack), $i,$depth,$remainder);
			$stack[$d]->children[] = $node;
			break;
		case 'ret':
			if(!preg_match('/^ (?P<return>.*?)$/', $remainder, $matches))
				throw new Exception('Bad '.$remainder);

			$return = $matches['return'];

			$d = strlen($depth) - 1;
			$d /= 2;
			$d++;
			$stack[$d]->info['return'] = $return;
			break;
	}
}

echo json_encode(['children' => $root]);