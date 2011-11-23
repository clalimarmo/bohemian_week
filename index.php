<?php
/*
**	This file routes all requests to the publication-manager application.
**	Don't modify this file unless you know what you're doing.
**	The application is designed to be maintained by modifying the files
**	in the models/ , templates/ , and controllers/ subdirectories, as well
**	as the routes.php file.
**
**	See README for additional info.
*/

require_once("settings/app.php");	//include this first!
require_once("url_router.php");
require_once("routes.php");

session_start();

// route the request to the appropriate action
$router = new URLRouter($URL_ROUTES);
$action = $router->get_action($_SERVER['REQUEST_URI']);

require_once(CONTROLLER_ROOT . $action['controller_file']);
if(is_callable($action['function'])) {
	call_user_func($action['function'], $action['args']);
}
else {
	require_once(CONTROLLER_ROOT . '404.php');
	call_user_func('not_found', $action['args']);
}

if(isset($_SESSION['session_messages_seen'])) {
	unset($_SESSION['notice']);
	unset($_SESSION['error']);
	unset($_SESSION['session_messages_seen']);
}

if(isset($_SESSION['notice']) || isset($_SESSION['error']))
	$_SESSION['session_messages_seen'] = 1;

?>
