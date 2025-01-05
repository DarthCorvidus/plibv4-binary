<?php
namespace plibv4\binary;
interface Structable {
	function getStructure(): Structure;
	function onLoadClass(): void;
}