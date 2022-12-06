

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
<tr><th>Time </th><th>Topic</th><th>Information</th></tr>
<tbody>

<?php
	$lasteventdate = '';
	foreach ($calendar as $cal) :
		if ($cal['suspended']){continue;} // dont display
		$eventdate = date('l M j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
		$rowclass = (empty($cal['note'])) ? 'border-bottom' : 'no-bottom';
	?>
	<?php if ($eventdate != $lasteventdate) :?>
	<tr class="border-bottom" style='background-color:#F3984D; '>
	<td colspan=3
	style='font-size:0.8em;padding-bottom:3px;padding-top:3px;'>
	<b><?=$eventdate ?> </b></td>
</tr>
	<?php endif; ?>
	<tr>
	<td><?=$eventtime?><br />

	</td>
	</td><td class='left'>
 	<b><?=$cal['title']?></b>
 	( <?=$cal['duration']?>)<br />
 	<?=$cal['type']?>
 	 at <?=$cal['location']?>

	</td>

 	<td class='left'>

 	<?php if (!empty($cal['note'])) : ?>
			<?=$cal['note'] ?? '' ?>
 	<?php endif; ?>


	</tr>

	<?php $lasteventdate = $eventdate; ?>
<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>
