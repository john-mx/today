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

	$anlist = explode("\n",$admin['advice']);
	$fixed = $anlist[array_rand($anlist)];

?>

<?php if ($admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>

<?php $this->insert('light',[$light]); ?>

<?php $this->insert('alerts'); ?>

<!--
<h4>Next Two Days</h4>
		<?php
			$wspec = ['wslocs'=>['jr','cw'],'fcstart'=>'1','wsdays'=>2];
			// locations, start date, number of days in forecast
			$this->insert('weather-one-line',$wspec);


		?>
<br />
 -->
<h3>Hike Safely</h3>
	<div class='indent2 left'>
	<ul>
		<li><?= $fixed ?>
	</ul>
</div>


<?php
	$this->insert('notices',['notices' => $admin['notices']]);
?>

<h3>Today's Events</h3>
<?php
$calendar['cal1day'] = true;
$this->insert('calendar',['calendar'=>$calendar]);

?>
<hr>

