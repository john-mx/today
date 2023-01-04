<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
use DigitalMx\jotr\Calendar;

$Cal = new Calendar();
$calendar = $Cal->filter_calendar($calendar,1); #one day
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
			$this->insert('weather-wapi-flat',$wspec);


		?>


<?php
	$this->insert('alerts');
?>

<?php
	$this->insert('notices',['notices' => $admin['notices']]);
?>

<h4>Today's Events</h4>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
//u\echor($calendar,'data-calendar',NOSTOP);
?>


<?php foreach ($calendar as $cal) :
		if ($cal['suspended']){continue;} // dont display
		$eventdate = date('l, F j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
	?>
	<div class='inleft2'>
	<b><?=$eventtime?> </b>:
	<b><?=$cal['title']?></b>
 	(<?=$cal['duration']?>)
  at <?=$cal['location']?>
</div>

<?php endforeach; ?>

<?php endif; ?>

<?php $this->insert('end'); ?>
