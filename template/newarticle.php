<div class="span9">
	<div class="hero-unit">
		<form  class="form-horizontal" action="<?php echo $_SERVER['HTTP_REFERER'];?> /wiki/<?php echo (isset($_GET['title'])?urlencode($_GET['title'])."/":""); ?>" method="post" enctype="multipart/form-data">
			<fieldset>
				<label>Title</label>
                <input name="id" type="hidden" value="<?php if($_GET['action']==="change"){echo $TEMPLATE['id'];}?>" />
                <input name="title" type="text" placeholder="Type something…" value="<?php echo $TEMPLATE['title']; ?>">
                <span class="help-block">Description</span>
                <label class="checkbox">
                    <textarea name="text" rows="3"><?php if($_GET['action']==="change"){echo $TEMPLATE['text'];}?></textarea>
                </label>
                <label>Image</label>
                <input name="image" type="file" />
                <select name="align">
                	<option>left</option>
                    <option>right</option>
               	</select>
                <button type="submit" class="btn">Submit</button>
			</fieldset>
		</form>
	</div>
</div>