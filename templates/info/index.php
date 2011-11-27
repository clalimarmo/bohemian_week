<?php include(TEMPLATE_ROOT.'home.php'); ?>

<?php
	startblock('htmlhead');
	superblock();
?>
	<style type="text/css">
	a#info_link { background-color:#fff; color:#000; }
	</style>
<?php endblock(); ?>

<?php startblock('content'); ?>
	<h2>History</h2>
	<div class="section">
	<?php include(TEMPLATE_ROOT.'info/history.php'); ?>
	</div>

	<h2>How To</h2>
	<div class="section">
	<?php include(TEMPLATE_ROOT.'info/howto.php'); ?>
	</div>
<?php endblock(); ?>
