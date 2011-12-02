<?php
	$_story['contributor'] = $_story['story']->contributor();
?>
<p class="story-title"><?=$_story['story']->get_field('title');?></p>
<p class="story-story"><?=$_story['story']->get_field('story');?></p>
<p class="story-contributor indent">
	<?=$_story['contributor']->get_field('name');?> 
	<?php if($_story['story']->get_field('location') != "") { ?>
	<span class="story-location">
		in <?=$_story['story']->get_field('location');?>
	</span>
	<?php }//endif (location known) ?>
</p>
<?php unset($story_div); ?>
