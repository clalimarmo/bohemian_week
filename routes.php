<?php
/*
**	configure URL routing here:
**
**	$URL_ROUTES is an array of URLRoutes instances. See url_router.php
**	for details.
*/

$URL_ROUTES = array();	//don't change the name of this

$URL_ROUTES[] = new URLRoutes('home.php', array(
    '#^/?$#' => 'home'
		,'#^/info/?$#' => 'info'
		,'#^/info/founders/?$#' => 'founders'
		,'#^/archive/?$#' => 'under_construction'
		,'#^/bw/(?<week_name>(fa|sp)\d{2})/?$#' => 'under_construction'
    )
);

/*
** THIS SHOULD ALWAYS BE LAST!! fallback pattern: if no other patterns matched
** this pattern will match everything
*/
$URL_ROUTES[] = new URLRoutes('404.php', array(
	'#.*#' => 'not_found'
	)
);
?>
