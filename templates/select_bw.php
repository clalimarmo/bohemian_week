<?php include(TEMPLATE_ROOT.'home.php'); ?>

<?php
	startblock('htmlhead');
	superblock();
?>
	<link href="<?=CSS_URL;?>view_bw.css" rel="stylesheet" type="text/css" />
	<script src="<?=STATIC_URL;?>scripts/jquery-1.7.1.min.js" rel="javascript" type="text/javascript" language="JavaScript"></script>
	<style type="text/css">
		div#seasons a#<?=$season.$year;?> { background-color:#fff; color:#000; }
	</style>
<?php endblock(); ?>
