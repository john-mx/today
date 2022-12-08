<?php
	use DigitalMx as u;
// $conditions = $data['fire'],4data['air'],$data['current'];
//u\echor($data,'data',STOP);
?>

<h4>Park Conditions</h4>
<div class='in2'>

	<div class='float '>
		<b>Fire Danger:</b>
<?=$fire['level']?>
	</div>
&nbsp;
	<div class='float '>
		<b>Air Quality: </b>
<?=$air['br']['aqi']?>
 - <?=$air['br']['aqi_scale'] ?><br />
<small>At Black Rock <?php echo date ('m/d g:i a',$air['br']['observed_dt']);?></small>
	</div>
&nbsp;
	<div class='float no-print'>
	<!-- display:none when printed -->
	<b>Temperature at Jumbo Rocks: </b>
	<?php
		echo
		$current['temp_f'] ." &deg;F"
		. "<br /><small>at "
		. date('m/d g:i a',$current['last_updated_epoch'])
		." </small>"
		. NL;
	?>
	</div>

<div class='clear'></div>
</div>
