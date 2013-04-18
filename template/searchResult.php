 <div class="span9">
   <div class="hero-unit">
 	<h3>Search for:<?php echo $TEMPLATE['searchTest'];?></h3>
	
			
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
			?>
			
	</div>
</div>