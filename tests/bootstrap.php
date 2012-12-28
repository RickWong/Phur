<?php
/**
 * Composer Autoloader
 */
require dirname(__FILE__) . "/../vendor/autoload.php";

/**
 * phpp Autoloader
 */
spl_autoload_register(function ($className)
{
	@include_once dirname(__FILE__)."/../".str_replace(array("_", "\\"), "/", $className).".php";
});