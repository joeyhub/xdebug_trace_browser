<?php
namespace Frork;

require_once('exception.inc.php');
require_once('autoloader.inc.php');
require_once('world.inc.php');

World::$BASE_PATH = realpath(__DIR__.'/..');
AutoLoader::initialise(World::$BASE_PATH);
AutoLoader::addNamespaceMapping('/inc/api');
World::$ENV = 'run';
World::$TPL = new MVC\View\HandlerFactory(World::$BASE_PATH.'/inc/tpl');
