


<h4>Upcoming Events</h4>
<?php
if(empty($calendar)) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
?>
<table class='caltable'>
<colgroup>
        <col style="width: 15%;">
        <col style="width: 30%;">
        <col style="width: 20%;">
         <col >
</colgroup>
<tr><th>Time </th><th>Program</th><th> Location</th><th>Information</th></tr>
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
	<tr class="border-bottom" style='background-color:#F3984D; '>
	<td colspan=4
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


	</td>
	<td class='left'>
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
