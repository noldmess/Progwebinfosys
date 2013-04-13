<div class="span3">
	<div class="well sidebar-nav">
	<ul class="nav nav-list">
		<li class="nav-header">Sidebar</li>
		<?php foreach($TEMPLATE['index'] as $item){?>
			<li><a href="wiki/<?php echo urlencode($item);?>/"> <?php echo urldecode($item);?></a></li>
		<?php }?>
	<li><a href="wiki/new/"><i class="icon-pencil"></i>Neuer Artikel</a></li>
	</ul>
	</div>
	<!--/.well -->
</div><!--/span-->