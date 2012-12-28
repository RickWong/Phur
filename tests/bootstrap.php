<?php
/**
 * @author Rick Wong <rick@webambition.nl>
 * @package phpp
 * @version $Format:%h%
 */
namespace phpp;

$composer = dirname(__FILE__) . "/../vendor/";
require $composer."autoload.php";

spl_autoload_register(function ($className)
{
	@include_once dirname(__FILE__)."/../".str_replace(array("_", "\\"), "/", $className).".php";
});
