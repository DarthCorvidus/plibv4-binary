<?php
require_once __DIR__."/../vendor/autoload.php";
$dir = dirname(__DIR__)."/tests/TimeKeeping";
foreach(glob($dir."/*.php") as $value) {
	/**
	 * @psalm-suppress UnresolvableInclude
	 */
	require_once $value;
}
$examples = dirname(__DIR__)."/tests/StructExamples";
foreach(glob($examples."/*.php") as $value) {
	/**
	 * @psalm-suppress UnresolvableInclude
	 */
	require_once $value;
}
