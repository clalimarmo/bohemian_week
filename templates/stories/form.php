<form method="post" id="storyform" action="<?=ROOT_URL;?>/bw/<?=$time_tag;?>/stories/submit/">
	<fieldset id="contributor">
		<p>
			<label for="contributor[name]">Your name*</label><br />
			<input id="contributor[name]" name="contributor[name]" type="text" />
			</p>
		<p>
			<label for="contributor[email]">Your email</label><br />
			<input id="contributor[email]" name="contributor[email]" type="text" />
			</p>
	</fieldset>
	<fieldset id="story">
		<p>
			<label for="story[title]">Title*</label><br />
			<input id="story[title]" name="story[title]" type="text" />
			</p>
		<p>
			<label for="story[location]">Location</label><br />
			<input id="story[location]" name="story[location]" type="text" />
			</p>
		<p>
			<label for="story[story]">Your Bohemian Tale*</label><br />
			<textarea cols="60" rows="20" id="story[story]" name="story[story]" type="text"></textarea>
			</p>
	</fieldset>
	<p>
		<input type="hidden" value="<?=$time_tag;?>" />
		<input type="submit" value="Submit" />
		</p>
	<p>* indicates a required field</p>
</form>
