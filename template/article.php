<div class="span9">
	<div class="hero-unit">
		<h2><?php echo urldecode($TEMPLATE['title'])?></h2>
		<p><?php echo $TEMPLATE['parsedText']?></p>
		<a class="btn btn-danger" href="/wiki/<?php echo urlencode($TEMPLATE['title']);?>/remove"><i class="icon-remove"></i>remove</a>
		<a class="btn btn-primary" href="/wiki/<?php echo urlencode($TEMPLATE['title']);?>/change"><i class="icon-pencil"></i>change</a>
		<?php $first = true; 
			foreach($TEMPLATE['linklist'] as $item){
			if($first){
				$first = false;
				echo "Links:";
			}?>
			<li><a href="/wiki/<?php echo urlencode($item);?>/"> <?php echo $item;?></a></li>
		<?php }?> 
	</div>
</div>
