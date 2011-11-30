<?php
include(APP_ROOT.'lib/phpFlickr/phpFlickr.php');

function view_bw($args) {
	$season = $args['season'];
	$year = $args['year'];
	$time_tag = $season.$year;

	/* flickr_api needs to be defined in settings/app.php, or elsewhere */
	$flickr_api = new phpFlickr(FLICKR_API);

	$results = $flickr_api->photos_search(array(
		'tags' => 'Bohemian Week,'.$time_tag
		,'user_id' => '71051084@N04'
		,'extras' => 'description'
	));
	$bw_official_photos = $results['photo'];
	unset($results);

	include(TEMPLATE_ROOT.'view_bw.php');
}

?>
