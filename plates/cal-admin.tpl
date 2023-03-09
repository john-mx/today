<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



/* this template is for calendawr admin insertion
	into a form on another page.  Does NOT include a form.
*/

	// function dayset(int $i,array $dayset) {
// 		// i is the events line
// 		// days is a array of '' or up to 7 digits, for day nuymbers 0..6
// 		$t='';
// 		for ($j=0;$j<7;++$j) {
//
// 			$daychecked = key_exists(strval($j),$dayset) !== false ?'checked':'';
// 			#echo "checking for $j in $days $daychecked" . BR;
// 			$t .= "<input type='checkbox' name='events[$i][dayset][$j]' $daychecked> " . '&nbsp;';
// 		}
// 		return $t;
// 	}

	function daylist (int $i,array $dayset,$no_input) {
	// thius function produces the set of checkboxes for input
		// i is the calendar line
		// days is a array of '' or up to 7 digits, for day nuymbers 0..6
		// no_input makes this form display only (for nps items); false makes it actually input
		$daylabels=['S','M','T','W','Th','F','Sa'];
		$t='<div>';
		for ($j=0;$j<7;++$j) {
			$daychecked = key_exists(strval($j),$dayset) !== false ?'checked':'';
			$boxinput = ($no_input)? '': "name = 'events[$i][dayset][$j]'";
			$t .=
			"<div class='inlineblock' style='padding-left:6px;padding-right:6px;'>"
			. $daylabels[$j] . '<br />'
			. "<input type='checkbox' $boxinput $daychecked> "
			. "</div>";

		}
		$t .= "</div>";
		return $t;
	}
	//echo LIVE?'true':'false';exit;
if (0 && ! LIVE && (PLATFORM == 'remote')){
		echo "<div class='info'><a href='/copy_live.php'>Copy Live</a> You can copy the current calendar and campground info from the live site to have something to work on here.</div>";
	}

?>

<ul>
<li>Title, Type, Duration, Time, Location are required.
<li>Check "Delete" to remove an item.<br>
<li>Check "Suspend" to stop displaying without removing.
<li>Check "Cancel" to display the item with "Cancelled" legend.
<li>ALL SUBMIT BUTTONS ARE THE SAME.  Click any one of them.
<li>Help <?php U::showHelp('calendar');?>
</ol>
<br />
<button class='submit' type='submit'>Submit Form</button>

<?php
	$i=0;
	$npscnt = 0; $localcnt = 0;
	?>

 	<h3>Local Events NOT on NPS Calendar</h3>
	<table>

<tr><th>Title</th><th>Type</th><th>Duration</th><th style='width:17%;'>Location</th></tr>
<?php
	foreach ($calendar['events'] as $event) :

	$npsid = $event['npsid']??'';
	$dayseti = $event['dayset']??[];
	$daychecks = daylist($i,$dayseti,$npsid);
	$eventtimeclass = 'input';
	$eventstyle = (!empty($event['status']) && $event['status']=='new')? 'bg-gray':'';
	if (empty($event['time'])){$eventtimeclass='invalid';}
?>
<?php if (!$npsid) : ?>

		<tr class='<?=$eventstyle?>' style='vertical-align:top;'>

			<td class='left'>Title: <br /><input type = 'text' size='20'
				name="events[<?=$i?>][title]"
				value="<?=$event['title']?>" > </td>





		<td>Type <select name="events[<?=$i?>][type]" > <?=$event['typeoptions']?></select>

			</td>


		<td>Duration <br /><input type = 'text'
				name="events[<?=$i?>][duration]"
				value="<?=$event['duration']?>" size='15'> </td>
		<td rowspan='2' >Location <input type = 'text'
				name="events[<?=$i?>][location]" size='30'
				value="<?=$event['location']?>"  />
				<br />
				<input type='checkbox' name='events[<?=$i?>][reservation]'
					<?php if ($event['reservation'] ?? ''): ?> checked <?php endif; ?>
					> Reservation Req'd
				</td>
		</tr>


		<tr  class='<?=$eventstyle?>' style='vertical-align:top;'>

				<td class='left' id='timetd'>Start time: <input type=text name="events[<?=$i?>][time]" size='8' value="<?=$event['time']?>" id='timeset[<?=$i?>]' placeholder = '2:30 pm' onChange='checkTime(this)' class='<?=$eventtimeclass?>'>
			</td>

				<td >On/start date: <br /><input type = 'text' size='15'
				name="events[<?=$i?>][date]"
				value ="<?= $event['date'] ?? '' ?>" ><br />
				Repeat Ends<br />
				<input type='text' size='15' name = "events[<?=$i?>][end]" value="<?=$event['end']??'' ?>">

			</td>
			<td>

 Repeat Every: <br />
			<?=$daychecks?>

			</td>

		</tr>

		<tr><td colspan='4' class='left '<?=$eventstyle?>''>
		<label>Suspend (hide) until <input type='text' size='15'
					value="<?= $event['suspenddate'] ?? ''?>" name="events[<?=$i?>][suspenddate]" >
					</label>&bull;&bull;
	<label>Cancel until <input type='text' size='15'
					value="<?= $event['canceldate'] ?? ''?>" name="events[<?=$i?>][canceldate] " >
					</label>
					&bull;
		<label> Delete <input type='checkbox' name='events[<?=$i?>][delete]'>
					</label>
		 </td></tr>

		<tr class='left <?=$eventstyle?>' style='border-bottom:8px solid black;'><td colspan='4'>
		Notes:
		<input type = 'text' size='80'
				name="events[<?=$i?>][note]"
				value="<?=$event['note'] ?? '' ?>"  > </td>
		</tr>

	<?php  endif; ++$i; endforeach; ?>

</table>

<button class='submit' type='submit'>Submit Form</button>

	<h3>Events Copied From NPS Calendar</h3>
	<table>

<tr><th>Title</th><th>Type</th><th>Duration</th><th style='width:20rem;'>Location</th></tr>
<?php
	foreach ($calendar['events'] as $event) :

	$npsid = $event['npsid']??'';
		$dayseti = $event['dayset']??[];
	$daychecks = daylist($i,$dayseti,$npsid);
	$eventtimeclass = 'input';
	if (empty($event['time'])) :$eventtimeclass='invalid'; endif;
?>
<?php if ($npsid) : ?>
<!-- item from nps cal.  Cannot be edit here -->

<tr class='bg-lgrn' style='vertical-align:top;'>

			<td class='left'>Title: <br /><?=$event['title']?> </td>
		<td>Type: <br><?=$event['type']?></td>
		<td>Duration <br /><?=$event['duration']?></td>
		<td rowspan='2'>Location <?=$event['location']?>
				<br />
					<?php if ($event['reservation'] ?? ''): ?>
					 Reservation Req'd
					 <?php endif; ?>
				</td>
		</tr>


		<tr  class = 'bg-lgrn' style='vertical-align:top;'>

				<td class='left' id='timetd'>Start time: <?=$event['time']?></td>

				<td >On/start date: <br /><?= $event['date'] ?><br />
				Repeat Ends<br />
				<?=$event['end']??'' ?></td>
			<td>
 Repeat Every: <br />
			<?=$daychecks?>
			</td>
		</tr>

		<tr><td colspan='4' class='left '>
		<label>Suspend (hide) until <input type='text' size='15'
					value="<?= $calendar['npstags'][$npsid]['suspenddate'] ?? ''?> " name="npstags[<?=$npsid?>][suspenddate]" >
					</label>&bull;
	<label>Cancel until <input type='text' size='15'
					value="<?= $calendar['npstags'][$npsid]['canceldate'] ?? ''?> " name="npstags[<?=$npsid?>][canceldate]"
					</label>


		 </td></tr>

		<tr class='left' style='border-bottom:8px solid black;'><td class='right' colspan='4'>
		Notes:
		<input type = 'text' size='80'
				name="npstags[<?=$npsid?>][note]"
				value="<?=$calendar['npstags'][$npsid]['note'] ?? ''?>" ?? '' > </td>
		</tr>
	<?php  endif; ++$i; endforeach; ?>

	</table>

<button class='submit' type='submit'>Submit Form</button>

