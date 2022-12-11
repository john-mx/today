<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
// $conditions = $data['fire'],4data['air'],$data['current'];
//u\echor($data,'data',STOP);
?>

<h4>Park Conditions</h4>
<div class='in2'>

	<div class='float '>
		<b>Fire Danger:</b><br />
&nbsp;&nbsp;&nbsp;<?=$fire['level']?>
	</div>


	<div class='float no-print'>
	<!-- display:none when printed -->
	<b>Temperature at Twentynine Palms: </b>
	<small> at
		<?php echo date('m/d g:i a',$current['last_updated_epoch']) ?>
	</small><br>
	&nbsp;&nbsp;&nbsp; <?php
		echo
		$current['temp_f'] ." &deg;F/" . $current['temp_c'] . "&deg;C"
		. "<br />"
		. NL;
	?>
	</div>

<div class='clear'><br /></div>
	<div >
		<b>Air Quality: </b><small>At Black Rock <?php echo date ('m/d g:i a',$air['br']['observed_dt']);?></small><br />
		<?php
			$aqi = $air['br']['aqi'];
			$aq_scale = $air['br']['aqi_scale'];
			$aq_warn = Defs::$airwarn[$aq_scale];
			$aq_warn = Defs::$airwarn['Unhealthy'];

		?>
<?=$aqi?>
 - <?=$aq_scale ?><br />
<br />
(Test example for unhealthy) <?= $aq_warn ?>
	</div>
&nbsp;
</div>
