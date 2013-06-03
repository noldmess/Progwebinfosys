<div class="span9">
	<div class="hero-unit">
		<form  class="form-horizontal" action="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>/wiki/" method="post">
			<fieldset>
				<label>Title</label>
                    <input name="id" type="hidden" value="<?php if($_GET['action']==="change"){echo $TEMPLATE['id'];}?>" />
					<input name="title" type="text" placeholder="Type something…" value="<?php echo (isset($TEMPLATE['title'])?$TEMPLATE['title']:$_GET['title']); ?>">
					<span class="help-block">Description</span>
					<label class="checkbox">
						<textarea name="text" rows="3"><?php if($_GET['action']==="change"){echo $TEMPLATE['text'];}?></textarea>
					</label>
					<button type="submit" class="btn">Submit</button>
			</fieldset>
		</form>
	</div>
</div>