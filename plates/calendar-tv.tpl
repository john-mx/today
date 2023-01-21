<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

?>


<h4>Upcoming Events</h4>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
// get count of itmes by date

foreach ($calendar as $cal){
		$eventdate = date('l, F j',$cal['dt']);
		$caldays[$eventdate] = (isset($caldays[$eventdate])) ?
			$caldays[$eventdate] + 1
			:
			1;
		if (0 && $cevent['note']) ++$caldays[$eventdate];
	}
	U::echor($caldays,'caldays', NOSTOP);

?>

<table class='caltable  width100' >

<tr><th>Date</th><th>Time </th><th>Program</th><th>Type</th><th > Location</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	foreach ($calendar as $cal) :
		if ($cal['suspended']){continue;} // dont display
		$eventdate = date('l, F j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
		$eventshortdate = date('l, n/j',  $cal['dt']);
		// $rowclass = (empty($cal['note'])) ? 'border-bottom' : 'no-bottom';
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

	<td><?=$eventtime?><br />

	</td>
	<td class='left'>
 	<b><?=$cal['title']?></b>
 	</td>
 	<td class='left'>
 	<?=$cal['duration']?>
 	<?=$cal['type']?>
	<?php if ($cal['reservation'] ?? ''): ?>
 		<br /><span class='red'>Reservation Req'd on rec.gov</span>
	<?php endif; ?>

	</td>
	<td class='left' >
	at <?=$cal['location']?>
	</td>
	</tr>

	<?php if (0 && !empty($cal['note'])) : ?>
	<tr class='noterow left'><td colspan='4' style='background-color:#CCC;'>
			<?=$cal['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
