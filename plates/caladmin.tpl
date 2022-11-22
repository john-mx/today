<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;


	function dayset(int $i,string $days) {
		// i is the calendar line
		// days is a string of '' or up to 7 digits, for day nuymbers 0..6
		$t='';
		for ($j=0;$j<7;++$j) {

			$daychecked = strpos($days,strval($j)) !== false ?'checked':'';
			#echo "checking for $j in $days $daychecked" . BR;
			$t .= "<input type='checkbox' name='calendar[$i][day$j]' $daychecked> ";
		}
		return $t;
	}

?>


<h4>Calendar</h4>
<p>
Enter date and time for event. <br>
If event repeats, check the days it repeats on.  If there is a starting date, then the first scheduled event will be on the first checked day on or after the date.If there is no starting date entered, repeating schedule will start immediately; i.e., starting date is today. <br />
Repeating events will continue until the date entered as Last Day.  <br>
Set a time to "0" or blank to remove an event. All events are deleted after their last scheduled date.
<br />
Note: in list below, repeating events are shown first, then one-time events.

</p>
<form method=post action='/caladmin.php'>

<table>


<tr><th>Title</th><th>Location</th><th>Type</th><th>Duration</th></tr>

<?php
	$i=0;
	foreach ($calendar as $event) :

	$dayset = dayset($i,$event['days']);
	$eventtimeclass = 'input';
	if (empty($event['time'])){$eventtimeclass='invalid';}
?>


		<tr style='vertical-align:top;'>

			<td><input type = 'text' size='30'
				name="calendar[<?=$i?>][title]"
				value="<?=$event['title']?>" > </td>



		<td>at <input type = 'text'
				name="calendar[<?=$i?>][location]" size='25'
				value="<?=$event['location']?>"  > </td>
		<td>
				<select name="calendar[<?=$i?>][type]" ><?=$event['typeoptions']?></select></td>
		<td>for <input type = 'text'
				name="calendar[<?=$i?>][duration]"
				value="<?=$event['duration']?>" size='15'> </td>
		</tr>

		<tr  style='vertical-align:top;'>

			<td >On or After: <br /><input type = 'text' size='15'
				name="calendar[<?=$i?>][date]"
				value ="<?= $event['date']?>" >

			</td>
				<td id='timetd'>Time: <input type=text name="calendar[<?=$i?>][time]" size='8' value="<?=$event['time']?>" id='timeset[<?=$i?>]' placeholder = '2:30 pm' onChange='checkTime(this)' class='<?=$eventtimeclass?>'><br />
				Blank = remove event.
			</td>
			<td>
			 Repeat Every: <br />
Su&nbsp;M&nbsp;&nbsp;T&nbsp;&nbsp;W&nbsp;&nbsp;Th&nbsp;F&nbsp;&nbsp;Sa <br />
			<?=$dayset?>
			</td><td>

				Last day of repeat<br />
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
<button type='submit'>Submit Form</button>
</form>


