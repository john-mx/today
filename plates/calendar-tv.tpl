


<h4>Upcoming Events</h4>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
// get count of itmes by date
foreach ($calendar as $cevent){
		$cdate = $cevent['date'];
		$caldays[$cdate] = (isset($caldays[$cdate])) ?
			$caldays[$cdate] + 1
			:
			1;
		if ($cevent['note']) ++$caldays[$cdate];

	}
?>

<table class='caltable  width100' >

<tr><th>Date</th><th>Time </th><th>Program</th><th>Type</th><th style='width:33%'> Location</th></tr>
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
 	<td rowspan='<?=$caldays[$cal['date']] ?>'>
 		<?=$eventshortdate ?></td>
 	<?php else: ?>
 	<tr class='eventrow'>
<?php endif; ?>

	<td><?=$eventtime?><br />

	</td>
	<td class='left'>
 	<b><?=$cal['title']?></b>
 	</td>
 	<td>
 	<?=$cal['duration']?>
 	<?=$cal['type']?>


	</td>
	<td class='left' >
	at <?=$cal['location']?>
	</td>
	</tr>

	<?php if (!empty($cal['note'])) : ?>
	<tr class='noterow'><td colspan='4' >
			<?=$cal['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>