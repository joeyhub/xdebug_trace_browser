<?php

namespace Frork;

class AutoLoader
{
	private static $ns_map = array();
	private static $dir_map = array();
	private static $path;

	public static function load($name)
	{
		$file = self::resolveName($name);

		if($file === false)
			return;

		require_once($file);
	}

	public static function initialise($path)
	{
		self::$path = $path;
		spl_autoload_register(array('\\'.__NAMESPACE__.'\AutoLoader', 'load'));
	}

	public static function addNameSpaceMapping($directory)
	{
		self::$ns_map[] = $directory;
	}

	public static function addDirMapping($directory)
	{
		self::$dir_map[] = $directory;
	}

	public static function resolveName($name)
	{
		if(self::$path === null)
			throw new Exception('Must initialise!');

		$fs_delim = str_replace('\\', DIRECTORY_SEPARATOR, $name);

		foreach(self::$ns_map as $directory)
		{
			$fullname = self::$path.$directory.DIRECTORY_SEPARATOR.$fs_delim.'.php';

			if(file_exists($fullname))
				return $fullname;
		}

		$dir_conv = str_replace('_', DIRECTORY_SEPARATOR, $fs_delim);

		foreach(self::$dir_map as $directory)
		{
			$fullname = self::$path.$directory.DIRECTORY_SEPARATOR.$dir_conv.'.php';

			if(file_exists($fullname))
				return $fullname;
		}

		return false;
	}
}