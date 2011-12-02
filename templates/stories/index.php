<?php include(TEMPLATE_ROOT.'select_bw.php'); ?>

<?php startblock('content'); ?>
	<h2>Stories</h2>
	<div class="section">
	<?php
	$_story_list['stories'] = $bw_stories;
	include(TEMPLATE_ROOT.'stories/_story_list.php');
	?>
	</div>
<?php endblock(); ?>
