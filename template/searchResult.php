 <div class="span9">
   <div class="hero-unit">
 	<h3>Search for:<?php echo $TEMPLATE['searchText'];?></h3>
	
			
			<?php
				if(sizeof($TEMPLATE['searchList'])<=0){
			?>
					<li>No Result</li>
			<?php 
				}else{
					foreach($TEMPLATE['searchList'] as $item){
			?>
					<li><a href="/wiki/<?php echo urlencode($item);?>/"> <?php echo urldecode($item);?></a></li>
			<?php 
					}
				}
				
	 $max=abs($TEMPLATE['searchPaginatorStart'])+4;
	 $min=$TEMPLATE['searchPaginatorStart']-4;
	 
	 if($max>$TEMPLATE['searchPaginatorNumber']){
	 	$min-=$max-$TEMPLATE['searchPaginatorNumber'];
	 	 $max=round($TEMPLATE['searchPaginatorNumber'], 0, PHP_ROUND_HALF_DOWN);
	 }
	 if($min<=0){
		
	 	$max+=abs($min);
	 	if($max>$TEMPLATE['searchPaginatorNumber'])
	 		$max=$TEMPLATE['searchPaginatorNumber'];
	 	$min=1;
	 }
	 
	 if($min>1){
	 	?>
	 			<a href="/PvW/wiki/search/0/<?php echo $TEMPLATE['searchText'];?>/" title="ende""><</a>
	 		<?php 
	 	}
	 	for ($i=$min-1;$i<=$max;$i++){
		if($TEMPLATE['searchPaginatorStart']==$i){
		?>
			<?php echo $i;?>|
		<?php }else{?>
			<a href="/PvW/wiki/search/<?php echo urlencode($i)."/".$TEMPLATE['searchText'];?>/"><?php echo urlencode($i);?></a>|
		<?php }
	}
	if($max<$TEMPLATE['searchPaginatorNumber']){
		?>
			<a href="/PvW/wiki/search/<?php echo $TEMPLATE['searchPaginatorNumber']."/".$TEMPLATE['searchText'];?>/" title="ende"">></a>
		<?php 
	}
	?>
	</div>
</div>