<div class="span3">
	<div class="well sidebar-nav">
	<ul class="nav nav-list">
		<li class="nav-header">Sidebar</li>
		<?php foreach($TEMPLATE['index'] as $item){?>
			<li><a href="/PvW/wiki/<?php echo urlencode($item);?>/"> <?php echo urldecode($item);?></a></li>
		<?php }?>
	
	<li><a href="/PvW/wiki/new/"><i class="icon-pencil"></i>New Article</a></li>
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
	 			<a href="/PvW/wiki/" title="ende""><</a>
	 		<?php 
	 	}
	 	for ($i=$min;$i<=$max;$i++){
		if($TEMPLATE['paginatorstart']==$i){
		?>
			<a href="/PvW/wiki/<?php echo urlencode($i);?>/"><?php echo urlencode($i);?>-</a>|
		<?php }else{?>
			<a href="/PvW/wiki/<?php echo urlencode($i);?>/"><?php echo urlencode($i);?></a>|
		<?php }
	}
	if($max<$TEMPLATE['paginatornumber']){
		?>
			<a href="/PvW/wiki/<?php echo $TEMPLATE['paginatornumber']?>/" title="ende"">></a>
		<?php 
	}
	?>
	</div>
	<!--/.well -->
</div><!--/span-->