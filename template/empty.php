<div class="span9">
	<div class="hero-unit">
    <p> Select an article from the list or <?php if($TEMPLATE['user_id'] !== false){?>write a <a href="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>wiki/new/"><i class="icon-pencil"></i>new one</a>!<?php }else{?><a href="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>login/">log in</a> to write, edit and remove an article!<?php }?>
    </p>
	</div>
</div>