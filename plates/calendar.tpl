<?php
if(empty($calendar)) : echo "No Calendar (calendar.tpl)"; else:
?>
<h3>Upcoming Events</h3>
<table class='caltable'>
<tr><th>Date and Time</th><th>Program</th><th>Duration</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	foreach ($calendar as $cal) :
		$eventdate = date('l M j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
		$rowclass = (empty($cal['note'])) ? 'border-bottom' : 'no-bottom';
	?>
	<?php if ($eventdate != $lasteventdate) :?>

	<tr class="border-bottom" style='background-color:#F3984D';>
	<td colspan=4><b><?=$eventdate ?> </b></td>
</tr>
	<?php endif; ?>
	<tr>
	<td><?=$eventtime?><br />
	<?=$cal['type']?>
	</td>
 	<td class='left'>
 	<b><?=$cal['title']?></b> <br />
 	 &nbsp;&nbsp;&nbsp;at <?=$cal['location']?>
 	<?php if (!empty($cal['note'])) : ?>
			<br /><i><?=$cal['note'] ?? '' ?></i>
 	<?php endif; ?>
	</td>
	<td><?=$cal['duration']?> </td>
	</tr>

	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
