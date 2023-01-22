<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

?>


<h3>Upcoming Events</h3>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
?>

<table class='caltable center indent2' >

<tr><th>Time </th><th>Program</th><th>Type</th><th style='width:33%'> Location</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	foreach ($calendar as $cal) :
		if ($cal['suspended']){continue;} // dont display
		$eventdate = date('l, F j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
		$rowclass = (empty($cal['note'])) ? 'border-bottom' : 'no-bottom';
	?>
	<?php if ($eventdate != $lasteventdate) :?>
	<tr class='daterow '>
	<td colspan=4>
	<b><?=$eventdate ?> </b></td>
</tr>
	<?php endif; ?>

	<tr class='eventrow'>
	<td><?=$eventtime?><br />

	</td>
	<td class='left'>
 	<b><?=$cal['title']?></b>
 	</td>
 	<td>
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

	<?php if (!empty($cal['note'])) : ?>
	<tr  ><td style='border-top:0;'></td><td colspan='3' class='noterow left'>
			<?=$cal['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
