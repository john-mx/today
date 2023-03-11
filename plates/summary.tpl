<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


use DigitalMx\jotr\Calendar;

//$Cal = new Calendar();

?>


<?php

$air = $air;
$uvday = <<<EOT


	<p><b>UV:</b> {$uv['uvFC']}
			<span  style="padding-left:2em;padding-right:2em;background-color:{$uv['uvFCcolor']};">
				 {$uv['uvFCscale']}
			</span>
				&mdash; {$uv['uvFCwarn']}
			</p>

EOT;
		$aircolor = $air['jr']['aqi_color'];
$aqday = <<<EOT
		Air Quality:
			{$air['jr']['aqi']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}
				</div>
				&mdash; {$air['jr']['airwarn']}
			<br />
EOT;



?>

<?php if ($admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>

<?php $this->insert('light',[$light]); ?>
<?=$uvday?>
<!--
<h4>Next Two Days</h4>
		<?php
			$wspec = ['wslocs'=>['jr','cw'],'fcstart'=>'1','wsdays'=>2];
			// locations, start date, number of days in forecast
			$this->insert('weather-one-line',$wspec);


		?>
<br />
 -->
<?php $this->insert('advice')?>
<?php
	$this->insert('alerts');
?>

<?php
	$this->insert('notices',['notices' => $admin['notices']]);
?>

<h3>Today's Events</h3>
<?php
$calendar['events'] = Calendar::filter_events($calendar['events'],1); #one day
// U::echor($calendar,'filtered for summary');
if(empty($calendar['events'])) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
//Utilities::echor($calendar,'data-calendar',NOSTOP);

?>
<table class='inleft2  no-border'>

<?php foreach ($calendar ['events']as $event) :
		if ($event['status']=='Suspended'){continue;} // dont display
		$eventdate = date('l, F j',$event['dt']);
		$eventtime = date('g:i a', $event['dt']);
		$reservation = $event['reservation'] ? ' (Reservation required)':'';
	?>
	<tr class='left border-gray no-cols'>
	<td ><b><?=$eventtime?> </b>

	</td>
	<td class='lrpad'><b><?=$event['title']?></b>
	</td>
 	<td><?=$event['duration']?> <?=$event['type']?>
  at <?=$event['location']?>
  </td><td>

  <?php if ($event['status']=='Cancelled'): ?><span class='red'>Cancelled!</span><?php endif; ?>
	</td>
  </tr>

<?php endforeach; ?>
</table>
<?php endif; ?>


<?php
$this->insert('sig'); ?>
