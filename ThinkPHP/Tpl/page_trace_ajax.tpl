<pre>
	<h1>ThinkPHP Trace run time:<?php echo G('beginTime','viewEndTime').'s ';?></h1>
	<h3>URL: <?php echo getLocalUrl()?></h1>
	<h3>ACTION: <?php echo __ACTION__?>"</h1>
	<?php foreach($trace as $key => $value){ ?>
	<pre><h2 ><?php echo $key ?></h2><hr /><?php 
		if(is_array($value)){
			foreach ($value as $k=>$val){
			echo "<span>".(is_numeric($k) ? '' : $k.' : ') . htmlentities($val,ENT_COMPAT,'utf-8') ."</span>\n";
			}
		}
	?>
	</pre><?php } ?>		
</pre>