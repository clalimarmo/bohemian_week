<?php
require_once(MODEL_ROOT.'story.php');
require_once(MODEL_ROOT.'contributor.php');

function create($args) {
	$season = $args['season'];
	$year = $args['year'];
	$time_tag = $season.$year;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$contributor_data = $_POST['contributor'];
		$story_data = $_POST['story'];

		$contributor = new Contributor($contributor_data);
		$success = $contributor->save();
		$cid = $contributor->get_field('id');
		if($cid) {
			$story_data['contributor'] = $cid;
			$story_data['season'] = $time_tag;
			$story = new Story($story_data);
			$success = $story->save();
		}

		if($success)
			$_SESSION['notice'] = 'Thanks! Your story has been submitted';
		else
			$_SESSION['notice'] = 'Sorry, your story failed to submit';

		header('Location: '.ROOT_URL.'/bw/'.$time_tag.'/stories/');
	}
	else {
		include(TEMPLATE_ROOT.'stories/submit.php');
	}
}

function index($args) {
	$season = $args['season'];
	$year = $args['year'];
	$time_tag = $season.$year;

	$story_mgr = Story::get_object_manager();
	$bw_stories = $story_mgr->retrieve(
		array(array(
			'season=' => $time_tag))
		,$offset = 0
		,$limit = 0
		,$sortby = 'id desc'
	);

	include(TEMPLATE_ROOT.'stories/index.php');
}
?>
