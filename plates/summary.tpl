<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
use DigitalMx\jotr\Calendar;

$Cal = new Calendar();

?>
<style>
body {
	font-size:1.1rem;
}


</style>


<?php
$gupdated = '';
$wupdated = '';
if(empty($wgov )): echo "<p>No wgov Data</p>"; exit;
	else:
	$gupdated =  date('M d g:i a',$wgov['update']);
	endif;

if(empty($wapi)): echo "<p>No wapi Data</p>"; exit;
	else:
	$wupdated =  date('M d g:i a',$wapi['update']);
	endif;
	//u\echor($light);exit;
$daylight = $light['day'];
//u\echor($daylight, 'day',true);

$air = $air;
$uvday = <<<EOT
	UV: ${uvdata['uv']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:${uvdata['uvcolor']};">
				 ${uvdata['uvscale']}
			</div>
				&mdash; ${uvdata['uvwarn']}
			<br />
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

<h4>Next Two Days</h4>
		<?php
			$wspec = ['wslocs'=>['jr','cw'],'fcstart'=>'+1','wsdays'=>2];
			$this->insert('weather-one-line',$wspec);


		?>


<?php
	$this->insert('alerts');
?>

<?php
	$this->insert('notices',['notices' => $admin['notices']]);
?>

<h4>Today's Events</h4>
<?php
$calendar = $Cal->filter_calendar($calendar,1); #one day
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
//u\echor($calendar,'data-calendar',NOSTOP);

?>
<table class='inleft2 border'>

<?php foreach ($calendar as $event) :
		if ($event['suspended']){continue;} // dont display
		$eventdate = date('l, F j',$event['dt']);
		$eventtime = date('g:i a', $event['dt']);
		$reservation = $event['reservation'] ? ' (Reservation required)':'';
	?>
	<tr class='left no-border'>
	<td style='width:6em;'><b><?=$eventtime?> </b></td>
	<td class='lrpad'><b><?=$event['title']?></b></td>
 	<td><?=$event['duration']?>
  at <?=$event['location']?></td>
  <td class=lrpad><?=$reservation?></td>
  </tr>

<?php endforeach; ?>
</table>
<?php endif; ?>

<?php $this->insert('end'); ?>
