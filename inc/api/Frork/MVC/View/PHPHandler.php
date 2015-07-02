<?php

namespace Frork\MVC\View;

class PHPHandler extends Handler
{
	public function render($name, $params = null)
	{
		if(is_array($params))
			extract($params, EXTR_PREFIX_ALL, 'v_');

		require($this->path.'/'.$name.'.php');
	}
}