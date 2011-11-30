<?php include(TEMPLATE_ROOT.'home.php'); ?>

<?php
	startblock('htmlhead');
	superblock();
?>
	<link href="<?=CSS_URL;?>view_bw.css" rel="stylesheet" type="text/css" />
	<script src="<?=STATIC_URL;?>scripts/jquery-1.7.1.min.js" rel="javascript" type="text/javascript" language="JavaScript"></script>
	<style type="text/css">
		div#seasons a#<?=$season.$year;?> { background-color:#fff; color:#000; }
		.image_container {
			background-image:url('<?=IMAGES_URL;?>spinner.gif');
			}
	</style>
	<script type="text/javascript">
		var photo_urls = new Array();
		var photo_titles = new Array();
		var photo_descriptions = new Array();
		<?php foreach($bw_official_photos as $p) { ?>
			photo_urls.push('<?=$flickr_api->buildPhotoURL($p);?>');
			photo_titles.push('<?=htmlentities($p['title'], ENT_QUOTES);?>');
			photo_descriptions.push('<?=htmlentities($p['description'], ENT_QUOTES);?>');
		<?php }//end foreach photos ?>
		var current_image = 0;

		function show_image(image_container, title_container, description_container, img_url) {
			image_container.css('background-image' , 'url('+img_url+')');
			show_current_info(title_container, description_container);
		};
		function show_next_image(div, title_container, description_container) {
			if(current_image < photo_urls.length - 1) {
				current_image++;
				show_image(div, title_container, description_container, photo_urls[current_image]);
			}
		};
		function show_prev_image(div, title_container, description_container) {
			if(current_image > 0) {
				current_image--;
				show_image(div, title_container, description_container, photo_urls[current_image]);
			}
		};
		function show_current_info(title, description) {
			t = $('<div/>').html(photo_titles[current_image]).text();
			d = $('<div/>').html(photo_descriptions[current_image]).text();
			title.html(t);
			description.html(d);
		}
		$(document).ready(function () {
			var image = $('#image_container');
			var title_cont = $('#title');
			var desc_cont = $('#description');
			show_image(image, title_cont, desc_cont, photo_urls[current_image]);
		});
	</script>
<?php endblock(); ?>

<?php startblock('content'); ?>
	<h2>Photos</h2>
	<div class="image_container left_float" id="image_container">
		<div class="photo_action" id="prev_photo" onclick="show_prev_image($('#image_container'), $('#title'), $('#description'));">&laquo;</div>
		<div class="photo_action" id="next_photo" onclick="show_next_image($('#image_container'), $('#title'), $('#description'));">&raquo;</div>
	</div>
	<div id="info">
		<h3 id="title"></h3>
		<p id="description"></p>
	</div>
	<div class="clearfix"></div>
<?php endblock(); ?>
