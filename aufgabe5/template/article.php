<div class="span9">
	<div class="hero-unit">
		<h2><?php echo urldecode($TEMPLATE['title'])?></h2>
		<p><?php if(isset($TEMPLATE['image']) && $TEMPLATE['image'] !== 'NOIMG'){ echo '<img src="'.str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])).'/art_images/'.$TEMPLATE['id'].'.png" style="float:'.$TEMPLATE['align'].'">';}echo $TEMPLATE['parsedText'];?></p>
		<?php if($TEMPLATE['user_id'] !== false){?>
		<a class="btn btn-danger" href="<?php echo $TEMPLATE['id']."/".preg_replace( '/\s+/', '_', $item['title']);?>/remove"><i class="icon-remove"></i>remove</a>
		<a class="btn btn-primary" href="<?php echo $TEMPLATE['id']."/".preg_replace( '/\s+/', '_', $item['title']);?>/change"><i class="icon-pencil"></i>change</a><br>
		<?php }
			 $first = true; 
			foreach($TEMPLATE['linklist'] as $item){
			if($first){
				$first = false;
				echo "<br>Linked by:";
			}?>
			<li><a href="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>/wiki/<?php echo $item['id']."/".preg_replace('/\s+/', '_', $item['title']);?>/"> <?php echo $item['title'];?></a></li>
		<?php }?> 
        <br>
		<p>Creator: <?php echo $TEMPLATE['UserCreate']?><br />
        Modified by: <?php echo $TEMPLATE['UserModified']?><br />
        Last Modification: <?php echo $TEMPLATE['dateMod']?>
        </p>
	</div>
</div>
