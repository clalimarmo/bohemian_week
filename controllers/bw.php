<?php
require_once(APP_ROOT.'lib/phpFlickr/phpFlickr.php');
require_once(MODEL_ROOT.'contributor.php');
require_once(MODEL_ROOT.'story.php');

function view_bw($args) {
	$season = $args['season'];
	$year = $args['year'];
	$time_tag = $season.$year;

	/* flickr_api needs to be defined in settings/app.php, or elsewhere */
	$flickr_api = new phpFlickr(FLICKR_API_KEY);

	$results = $flickr_api->photos_search(array(
		'tags' => $time_tag
		,'tag_mode' => 'all'
		,'user_id' => '71051084@N04'
		,'extras' => 'description'
	));
	$bw_official_photos = $results['photo'];
	shuffle($bw_official_photos);
	unset($results);

	$story_mgr = Story::get_object_manager();
	$bw_stories = $story_mgr->retrieve(array(array(
		'season=' => $time_tag
	)));
	shuffle($bw_stories);

	include(TEMPLATE_ROOT.'bw_overview.php');
}

?>
