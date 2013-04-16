<div class="span9">
	<div class="hero-unit">
<?php if(isset($TEMPLATE['title'])){?>
		<h1><?php echo urldecode($TEMPLATE['title'])?></h1>
		<p><?php echo $TEMPLATE['text']?></p>
		<a class="btn btn-danger" href="/wiki/<?php echo urlencode($TEMPLATE['title']);?>/remove"><i class="icon-remove"></i>remove</a>
		<a class="btn btn-primary" href="/wiki/<?php echo urlencode($TEMPLATE['title']);?>/change"><i class="icon-pencil"></i>change</a>
<?php } ?>    
	</div>
</div>