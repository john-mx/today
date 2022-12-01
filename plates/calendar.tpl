

<?php
if(empty($calendar)) : echo "No Calendar (calendar.tpl)"; else:
?>
<h4>Upcoming Events</h4>
<table class='caltable'>
<colgroup>
        <col style="width: 15%;">
        <col style="width: 40%;">
        <col >
</colgroup>
<tr><th>Time </th><th>Program</th><th>Topic</th></tr>
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
	<td colspan=3><b><?=$eventdate ?> </b></td>
</tr>
	<?php endif; ?>
	<tr>
	<td><?=$eventtime?><br />

	</td>
 	<td class='left'>
 	<?=$cal['type']?>
 	 at <?=$cal['location']?>
 	<?php if (!empty($cal['note'])) : ?>
			<br /><i><?=$cal['note'] ?? '' ?></i>
 	<?php endif; ?>
	</td><td>
 	<b><?=$cal['title']?></b>
 	( <?=$cal['duration']?>)

	</td>

	</tr>

	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
