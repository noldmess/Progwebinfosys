<div class="span9">
	<div class="hero-unit">
		<h1><?php echo urldecode($TEMPLATE['title'])?></h1>
		<p><?php echo $TEMPLATE['text']?></p>
		<a class="btn btn-danger" href="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>/wiki/<?php echo urlencode($TEMPLATE['title']);?>/remove"><i class="icon-remove"></i>remove</a>
		<a class="btn btn-primary" href="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>/wiki/<?php echo urlencode($TEMPLATE['title']);?>/change"><i class="icon-pencil"></i>change</a> 
	</div>
</div>
