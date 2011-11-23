<?php
/*
**	A route consists of a key-value pair: the key is a regex pattern,
**	and the value is a function name.
**
**	When a pattern (key) is matched, the corresponding function (value)
**	is called, with the matched subpattern array passed as its
**	first argument. In this way, specifying named subpatterns allows
**	you to specify URL based parameters to the function.
**
**	$controller_file specifies a file in the controller_root directory which
**	contains the functions specified in routes
*/

class URLRoutes {
	public $controller_file;
	public $routes;	//array of routes

	function __construct($controller_file, $routes) {
		$this->controller_file = $controller_file;
		$this->routes = $routes;
	}
}

class URLRouter {
	private $url_routes;

	function __construct($url_routes) {
		$this->url_routes = $url_routes;
	}

	/* returns action as an array, with the function specifier as the first
	** element, and the function arguments (matched subpatterns) as the second
	*/
	public function get_action($request_url) {
		foreach($this->url_routes as $r) {
			$patterns = array_keys($r->routes);
			$args = array();
			foreach($patterns as $p) {
				if (preg_match($p, $request_url, $args)) {
					return array(
						'controller_file'	=> $r->controller_file
						,'function'			=> $r->routes[$p]
						, 'args'			=> $args
					);
				}
			}
		}
	}
}
?>
