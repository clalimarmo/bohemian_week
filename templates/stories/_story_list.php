<?php if(count($_story_list['stories'])) { ?>
	<ul id="stories">
	<?php foreach($_story_list['stories'] as $s) {
		$_story['story'] = $s;
		echo '<li>';
		include(TEMPLATE_ROOT.'stories/_story.php');
		echo '</li>';
	} ?>
	</ul>
<?php } else { ?>
	<div>No stories have been submitted</div>
<?php }// endif (there are stories) ?>
<?php unset($_story_list); ?>
