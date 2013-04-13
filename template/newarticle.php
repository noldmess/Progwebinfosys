<div class="span9">
	<div class="hero-unit">
		<form  class="form-horizontal" action="/wiki/<?php echo (isset($_GET['title'])?urlencode($_GET['title'])."/":""); ?>" method="post">
			<fieldset>
				<label>Title</label>
					<input  name="title" type="text" placeholder="Type something…" value="<?php echo (isset($_GET['title'])?$_GET['title']:""); ?>">
					<span class="help-block">Description</span>
					<label class="checkbox">
						<textarea name="text" rows="3"><?php if($_GET['action']==="change"){echo $TEMPLATE['text'];}?></textarea>
					</label>
					<button type="submit" class="btn">Submit</button>
			</fieldset>
		</form>
	</div>
</div>