<?php
require_once __DIR__."/../vendor/autoload.php";
$dir = dirname(__DIR__)."/tests/TimeKeeping";
$glob = glob($dir."/*.php");
if($glob === false) {
	throw new \RuntimeException("unable to iterate over ".$dir);
}
foreach($glob as $value) {
	/**
	 * @psalm-suppress UnresolvableInclude
	 */
	require_once $value;
}
$examples = dirname(__DIR__)."/tests/StructExamples";
$globExamples = glob($examples."/*.php");
if($globExamples === false) {
	throw new \RuntimeException("unable to iterate over ".$examples);
}
foreach($globExamples as $value) {
	/**
	 * @psalm-suppress UnresolvableInclude
	 */
	require_once $value;
}