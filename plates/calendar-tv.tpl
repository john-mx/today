<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

?>


<h3>Upcoming Events</h3>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
// get count of itmes by date
//U::echor($calendar );
foreach ($calendar as $event){
		$eventdate = date('l, F j',$event['dt']);
		if ($event['status']=='Suspended'){continue;}
		// count nbumbe of events on a day to get rowspan right
		$caldays[$eventdate] = (isset($caldays[$eventdate])) ?
			$caldays[$eventdate] + 1
			:
			1;
		if (0 && $cevent['note']) ++$caldays[$eventdate];
	}
//	U::echor($caldays,'caldays', NOSTOP);

?>

<table class='caltable auto' >

<tr><th>Date</th><th>Time </th><th>Program</th><th > Location</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	foreach ($calendar as $event) :
		if ($event['status']=='Suspended'){continue;} // dont display
		$eventdate = date('l, F j',$event['dt']);
		$eventtime = date('g:i a', $event['dt']);
		$eventshortdate = date('l, n/j',  $event['dt']);
		// $rowclass = (empty($event['note'])) ? 'border-bottom' : 'no-bottom';
	?>


	<?php if ($eventdate != $lasteventdate) : ?>
	<!--
<tr class='daterow '>
	<td colspan=4>
	<b><?=$eventdate ?> </b></td>
</tr>
 -->



	<tr class='tvdaterow'>
 	<td rowspan='<?=$caldays[$eventdate] ?>'>
 		<?=$eventshortdate ?></td>
 	<?php else: ?>
 	<tr class='eventrow'>
<?php endif; ?>

	<td class='center'><?=$eventtime?>
<?php if ($event['status']=='Cancelled'): ?> <br /><span class='red'><b> </b></span><?php endif; ?>

	</td>
	<td class='left'>
 	<b><?=$event['title']?></b><br/>
 	<?=$event['type']?>
 	(<?=$event['duration']?>)
	</td>

	<td class='left' >
	<?=$event['location']?>
	<?php if ($event['reservation'] ?? ''): ?>
 		<br /><span class='red'>Reservation Required</span>
	<?php endif; ?>
	</td>
	</tr>

	<?php if (0 && !empty($event['note'])) : ?>
	<tr class='noterow left'><td colspan='3' style='background-color:#CCC;'>
			<?=$event['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>
<div>
For events marked <span class='red'>"Reservation Required"</span>, go to recreation.gov or call <nobr>877-444-6777</nobr></div>
<?php endif; ?>
