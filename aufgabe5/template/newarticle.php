<div class="span9">
	<div class="hero-unit">
		<form  class="form-horizontal" action="<?php str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>wiki/" method="post" enctype="multipart/form-data">
			<fieldset>
                <input name="id" type="hidden" value="<?php if($_GET['action']==="change"){echo $TEMPLATE['id'];}?>" />
                <span class="help-block">Title</span>
                <input name="title" type="text" placeholder="Type something…" value="<?php if(isset($_GET['title'])){echo $TEMPLATE['title'];}?>">
                <span class="help-block">Description</span>
                <label>
                    <textarea class="article" name="text" rows="5"><?php if($_GET['action']==="change" && isset($_GET['title'])){echo $TEMPLATE['text'];}?></textarea>
                </label>
                <label>Image:</label>
                <input name="image" type="file" />
                <label>Alignment:
                <select name="align">
                	<option value="left">left</option>
                    <option value="right">right</option>
               	</select>
                </label>
                <label>
               		<button type="submit" class="btn">Submit</button>
                </label>
			</fieldset>
		</form>
	</div>
</div>