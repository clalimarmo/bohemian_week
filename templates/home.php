<?php include(TEMPLATE_ROOT.'base.php'); ?>

<?php startblock('htmlhead'); ?>
<link href="http://fonts.googleapis.com/css?family=Varela" rel="stylesheet" type="text/css" />
<link href="<?=CSS_URL;?>base.css" rel="stylesheet" type="text/css" />
<?php endblock(); ?>

<?php startblock('body'); ?>
	<h1 class="root"><a href="/">BOHEMIAN WEEK</a></h1>

	<div id="seasons" class="root">
		<ol>
			<li><a id="info_link" href="<?=ROOT_URL;?>/info">INFO</a></li>
			<li><a id="fa11" href="<?=ROOT_URL;?>/bw/fa11">FA11</a></li>
			<li><a id="sp11" href="<?=ROOT_URL;?>/bw/sp11">SP11</a></li>
			<li><a id="fa10" href="<?=ROOT_URL;?>/bw/fa10">FA10</a></li>
			<li><a id="sp10" href="<?=ROOT_URL;?>/bw/sp10">SP10</a></li>
			<li><a id="archive_link" href="<?=ROOT_URL;?>/archive/">PAST</a></li>
		</ol>
	</div>

	<div id="notice"><?=$_SESSION['notice'];?></div>

	<div class="root" id="content">
	<?php startblock('content'); ?>
	<?php endblock(); ?>
	</div>

<?php endblock(); ?>
