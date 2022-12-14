<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
// $conditions = $data['fire'],4data['air'],$data['current'];
//u\echor($data,'data',STOP);
?>

<h4>Park Conditions</h4>
<div class='inleft2'>

	<div class='width45 inlineblock float'>
		<b>Fire Danger:</b><br />
&nbsp;&nbsp;&nbsp;<?=$fire['level']?>
	</div>

	<div class='no-print  inlineblock float'  >
	<!-- display:none when printed -->
	<b>Temperature at Twentynine Palms: </b><br />
	&nbsp;&nbsp;&nbsp;
	<?php echo
		$current['temp_f'] ." &deg;F (" . $current['temp_c'] . "&deg;C) at ";
		echo date('g:i a',$current['last_updated_epoch']);
	?>

	</div>

<div class='clear'><br /></div>

	<b>Air Quality:</b> <small>At Black Rock
		<?php echo date ('g:i a',$air['br']['observed_dt']);?>
		</small><br />
	<div class='inleft2'>
	<div class='inlineblock float '>

		<?php
			$aqi = $air['br']['aqi'];
			$aq_scale = $air['br']['aqi_scale'];
			$aq_warn = Defs::$airwarn[$aq_scale];
			#$aq_warn = Defs::$airwarn['Unhealthy'];
		?>
	<?=$aqi?> - <?=$aq_scale ?>
 	</div>
 	<div class='float inlineblock ' style='width:60%'>
	(Test example for unhealthy) <?= $aq_warn ?>
	</div>

	<div class='clear'></div>
	</div>
</div>
