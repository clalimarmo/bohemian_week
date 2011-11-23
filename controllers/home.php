<?php

function home($args) {
	include(TEMPLATE_ROOT.'home.php');
}

function info($args) {
	include(TEMPLATE_ROOT.'info/index.php');
}

function founders($args) {
	include(TEMPLATE_ROOT.'info/founders/index.php');
}

function under_construction($args) {
	include(TEMPLATE_ROOT.'under-construction.php');
}

?>
