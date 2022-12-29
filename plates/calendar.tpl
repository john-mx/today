


<h4>Upcoming Events</h4>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
?>
<table class='caltable width100 '>

<tr><th>Time </th><th>Program</th><th> Location</th></tr>
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
	<td colspan=3>
	<b><?=$eventdate ?> </b></td>
</tr>
	<?php endif; ?>
	<tr class='eventrow'>
	<td><?=$eventtime?><br />

	</td>
	<td class='left'>
 	<b><?=$cal['title']?></b>
 	( <?=$cal['duration']?>)<br />
 	<?=$cal['type']?>


	</td>
	<td class='left'>
	at <?=$cal['location']?>
	</td>
	</tr>

	<?php if (!empty($cal['note'])) : ?>
	<tr class='noterow'><td></td><td colspan='2' >
			<?=$cal['note'] ?? '' ?>
			</td></tr>
 	<?php endif; ?>


	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
