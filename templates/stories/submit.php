<?php include(TEMPLATE_ROOT.'select_bw.php'); ?>

<?php startblock('content'); ?>
	<h2>Submit a Story (<?=strtoupper($time_tag);?>)</h2>
	<div class="section">
	<?php include(TEMPLATE_ROOT.'stories/form.php'); ?>
	</div>
<?php endblock(); ?>
