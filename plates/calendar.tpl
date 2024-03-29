<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

//set cal1day to minimize output - no title, no sig, no date
$cal1day = $calendar['cal1day'] ??  false;

?>

<div style='break-inside:avoid;'>
<?php if (!$cal1day): ?><h3>Upcoming Events</h3><?php endif;?>
<?php
if(empty($calendar['events'])) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
// U::echor($calendar);
?>


<table class='caltable auto ' >

<tr><th>Time </th><th>Program</th><th>Type</th><th style='width:33%'> Location</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	$now = time();
	foreach ($calendar['events'] as $cal) :
		if ($cal['status'] == 'Suspended'){continue;} // dont display
		$eventdate = date('l, F j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
		$rowclass = (empty($cal['note'])) ? 'border-bottom' : 'no-bottom';
		//U::echor($cal);
	?>
	<?php if (!$cal1day && ($eventdate != $lasteventdate)) :?>

	<tr class='daterow '>
	<td colspan=4>
	<b><?=$eventdate ?> </b>
	</td>
</tr>

	<?php endif; ?>

	<tr class='eventrow'>
	<td><?=$eventtime?>
<?php if ($cal['status']=='Cancelled') : ?><br /><span class='red'>Cancelled!</span><?php endif; ?>
	</td>
	<td class='left'>
 	<b><?=$cal['title']?></b>

 	</td>
 	<td>
 	<?=$cal['type']?>
 	&bull;
 	<?=$cal['duration']?>

 	<?php if (empty($cal['npsid'])): echo '#';endif;?>
 	<?php if ($cal['reservation'] ?? ''): ?>
 		<br /><span class='red'>Reservation Required</span>
	<?php endif; ?>


	</td>
	<td class='left' >
	<?php echo $this->e($cal['location'])?>
	</td>
	</tr>

	<?php if (!$cal1day && !empty($cal['note'])) : ?>
	<tr  ><td style='border-top:0;'></td><td colspan='3' class='noterow left'>
			<?=$cal['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>
<?php if (!$cal1day): ?>
<div>
For events marked <span class='red'>"Reservation Required"</span>, go to recreation.gov or call <nobr>877-444-6777</nobr><br />
# local items not on nps calendar.
</div>
<?php endif; ?>
<?php endif; ?>
</div>