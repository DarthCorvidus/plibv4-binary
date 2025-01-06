<?php
require_once __DIR__."/../vendor/autoload.php";
$dir = dirname(__DIR__)."/tests/TimeKeeping";
foreach(glob($dir."/*.php") as $value) {
	require_once $value;
}
