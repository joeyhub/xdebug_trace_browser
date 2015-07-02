<?php

namespace Frork\MVC\View;

class HandlerFactory
{
	private $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function getHandler($type)
	{
		$class = __NAMESPACE__.'\\'.strtoupper($type).'Handler';
		return new $class($this->path);
	}
}