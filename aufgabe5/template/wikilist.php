<div class="span3">
	<div class="well sidebar-nav">
	<ul class="nav nav-list">
		<li class="nav-header">Sidebar</li>
		<?php  foreach($TEMPLATE['index'] as $item){?>
				<li><a href="<?php echo $item['id']."/".preg_replace( '/\s+/', '_', $item['title']);?>/"> <?php echo urldecode($item['title']);?></a></li>
		<?php }?>
	
	
	<?php if(isset($TEMPLATE['user_id']) && $TEMPLATE['user_id'] !== false){?>
		<li><a href="new/"><i class="icon-pencil"></i>New Article</a></li>
		<li><a href="../logout/"><button class="btn btn-successs">Logout</button></a></li>
	<?php }else{?>
			<li><a href="../login/"><button class="btn btn-successs">Login</button></a></li>
	<?php }?>
	</ul>
	<?php 
	 $max=abs($TEMPLATE['paginatorstart'])+4;
	 $min=$TEMPLATE['paginatorstart']-4;
	 
	 if($max>$TEMPLATE['paginatornumber']){
	 	$min-=$max-$TEMPLATE['paginatornumber'];
	 	 $max=round($TEMPLATE['paginatornumber'], 0, PHP_ROUND_HALF_DOWN);
	 }
	 if($min<=0){
		
	 	$max+=abs($min);
	 	if($max>$TEMPLATE['paginatornumber'])
	 		$max=$TEMPLATE['paginatornumber'];
	 	$min=1;
	 }
	 
	 if($min>1){
	 	?>
	 			<a href="wiki/" title="ende""><</a>
	 		<?php 
	 	}
	 	for ($i=$min-1;$i<=$max;$i++){
		if($TEMPLATE['paginatorstart']==$i){
		?>
			<?php echo urlencode($i);?>|
		<?php }else{?>
			<a href="<?php echo urlencode($i);?>/"><?php echo urlencode($i);?></a>|
		<?php }
	}
	if($max<$TEMPLATE['paginatornumber']){
		?>
			<a href="<?php echo round($TEMPLATE['paginatornumber'], 0, PHP_ROUND_HALF_DOWN);?>/" title="ende"">></a>
		<?php 
	}
	?>
	</div>
	<!--/.well -->
</div><!--/span-->