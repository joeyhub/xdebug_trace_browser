<?php

namespace Frork\MVC\View;

abstract class Handler
{
	protected $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

	public abstract function render($name, $params = null);
}