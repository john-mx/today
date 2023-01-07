<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

/* this template is for calendawr admin insertion
	into a form on another page.  Does NOT include a form.
*/

	function dayset(int $i,string $days) {
		// i is the calendar line
		// days is a string of '' or up to 7 digits, for day nuymbers 0..6
		$t='';
		for ($j=0;$j<7;++$j) {

			$daychecked = strpos($days,strval($j)) !== false ?'checked':'';
			#echo "checking for $j in $days $daychecked" . BR;
			$t .= "<input type='checkbox' name='calendar[$i][day$j]' $daychecked> " . '&nbsp;';
		}
		return $t;
	}
	function daylist (int $i,string $days) {
		// i is the calendar line
		// days is a string of '' or up to 7 digits, for day nuymbers 0..6
		$dayids=['S','M','T','W','Th','F','Sa'];
		$t='<div>';
		for ($j=0;$j<7;++$j) {
			$daychecked = strpos($days,strval($j)) !== false ?'checked':'';

			$t .=
			"<div class='inlineblock' style='padding-left:6px;padding-right:6px;'>"
			. $dayids[$j] . '<br />'
			. "<input type='checkbox' name='calendar[$i][day$j]' $daychecked> "
			. "</div>";

		}
		$t .= "</div>";
		return $t;
	}

?>


<h4>Calendar</h4>
<ol>
<li>Enter the event's title, location, type, and duration. Duration  will be displayed exactly as you type it (e.g. '45 mins', 'one hour');
<li>Enter starting time for the event.  If you remove the time, the event will be deleted, If you want to stop displaying the event, but keep it in the system for later use, check the "Suspend" box.
<li>For one-time events, enter the Date for the event. For repeating events, leave blank to start immediately, or enter date after which events begin.
<li>
Enter repeating schedule for repeating events.
If event repeats, check the days it repeats on.
Repeating events will continue until the date entered as Last Day, or until removed.
<li>Enter the last day of repeating events.  If blank, events will be repeated until removed.
<li>Enter any additional notes.
</ol>
<p>Set a time to "0" or blank to remove an event. All events are deleted after their last scheduled date.
</p>

<table '>


<tr><th>Title</th><th>Location</th><th>Type</th><th>Duration</th></tr>

<?php
	$i=0;
	foreach ($calendar as $event) :

	$dayset = daylist($i,$event['days']);
	$eventtimeclass = 'input';
	if (empty($event['time'])){$eventtimeclass='invalid';}
?>


		<tr style='vertical-align:top;'>

			<td>Title: <br /><input type = 'text' size='30'
				name="calendar[<?=$i?>][title]"
				value="<?=$event['title']?>" > </td>





		<td><select name="calendar[<?=$i?>][type]" ><?=$event['typeoptions']?></select>

			</td>


		<td>Duration <br /><input type = 'text'
				name="calendar[<?=$i?>][duration]"
				value="<?=$event['duration']?>" size='15'> </td>
		<td><input type='checkbox' name='calendar[<?=$i?>][reservation]'
					<?php if ($event['reservation'] ?? ''): ?> checked <?php endif; ?>
					> Reservation Req'd</td>

		</tr>
		<tr><td colspan='4' class='left'>Location :<input type = 'text'
				name="calendar[<?=$i?>][location]" size='80'
				value="<?=$event['location']?>"  > </td></tr>

		<tr  style='vertical-align:top;'>

				<td id='timetd'>Starts at: <input type=text name="calendar[<?=$i?>][time]" size='8' value="<?=$event['time']?>" id='timeset[<?=$i?>]' placeholder = '2:30 pm' onChange='checkTime(this)' class='<?=$eventtimeclass?>'> <br />
				0 or blank removes event.<br>
				<input type='checkbox' name='calendar[<?=$i?>][suspended]'
					<?php if ($event['suspended']): ?> checked <?php endif; ?>
					> Suspend (save,but don't display.)
			</td>

				<td >On or starting date: <br /><input type = 'text' size='15'
				name="calendar[<?=$i?>][date]"
				value ="<?= $event['date']?>" >

			</td>
			<td>

 Repeat Every: <br />
			<?=$dayset?>


			</td><td>
	Repeat Ends on<br />
				<input type='text' size='15' name = "calendar[<?=$i?>][end]" value="<?=$event['end']?>">


				</td>

		</tr>

		<tr class='left' style='border-bottom:8px solid black;'><td class='right' colspan='4'>
		Notes:
		<input type = 'text' size='60'
				name="calendar[<?=$i?>][note]"
				value="<?=$event['note']?>" ?? '' > </td>
		</tr>


<?php
	++$i;
	endforeach;
?>

</table>
<input type='hidden' name='type' value='update'>

</form>


