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
<p>(Will be drawn from park calendar when possible.  Until then use this. Items will be sorted by actual date/time when saved.)<br />
Items with days checked recur until changed.
Set a time to "0" to delete an entry
Items with only a date will be removed after the date has passed.

</p>
<form method=post>

<table>


<tr><th>Date/Days</th><th>Time</th><th>Title</th><th>Location</th><th>Type</th><th>Duration</th></tr>

<?php
	$i=0;
	foreach ($calendar as $event) :

	$dayset = dayset($i,$event['days']);
?>

		<tr><td colspan='6'><hr></td></tr>
		<tr style='vertical-align:top;'>
		<td ><input type = 'text' size='15'
				name="calendar[$i][date]"
				value =" ${event['date']}" >
				<p > <b>or</b> </p>
				Su&nbsp;M&nbsp;&nbsp;T&nbsp;&nbsp;W&nbsp;&nbsp;Th&nbsp;F&nbsp;&nbsp;Sa <br /> $dayset <br />
				Begin on
				<input type='text' size='15' name = "calendar[$i][begin]" value = "${event['begin']}"><br />
				End on
				<input type='text' size='15' name = "calendar[$i][end]" value="${event['end']}">

				</td>

			<td><input type=text name="calendar[$i][time]" size='8' value="${event['time']}">
			</td>
			<td><input type = 'text' size='30'
				name="calendar[$i][title]"
				value="{$event['title']}" ?? ''> </td>



		<td><input type = 'text'
				name="calendar[$i][location]"
				value="{$event['location']}" ?? '' > </td>
		<td><input type = 'text'
				name="calendar[$i][type]"
				value="{$event['type']}" ?? ''> </td>
		<td><input type = 'text'
				name="calendar[$i][duration]"
				value="{$event['duration']}" ?? ''> </td>
		</tr>
		<tr class='left' style='border-bottom:8px solid black;'><td class='right'>Notes:</td>
		<td colspan='3'><input type = 'text' size='60'
				name="calendar[$i][note]"
				value="{$event['note']}" ?? '' > </td>
		</tr>


<?php
	++$i
	endforeach;
?>

</table>
<input type='hidden' name='type' value='update'>
<button type='submit'>Submit Form</button>
</form>


