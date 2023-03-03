<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



/* this template is for calendawr admin insertion
	into a form on another page.  Does NOT include a form.
*/

	function dayset(int $i,string $days) {
		// i is the events line
		// days is a string of '' or up to 7 digits, for day nuymbers 0..6
		$t='';
		for ($j=0;$j<7;++$j) {

			$daychecked = strpos($days,strval($j)) !== false ?'checked':'';
			#echo "checking for $j in $days $daychecked" . BR;
			$t .= "<input type='checkbox' name='events[$i][day$j]' $daychecked> " . '&nbsp;';
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
			. "<input type='checkbox' name='events[$i][day$j]' $daychecked> "
			. "</div>";

		}
		$t .= "</div>";
		return $t;
	}
	//echo LIVE?'true':'false';exit;
if (! LIVE){
		echo "<div class='info'><a href='/copy_live.php'>Copy Live</a> You can copy the current calendar and campgrpound info from the live site to have something to work on here.</div>";
	}

?>

<ul>
<li>Title, Type, Duration, Time, Location are required.
<li>Check "Delete" to remove an item.<br>
<li>Check "Suspend" to stop displaying without removing.
<li>Help <?php U::showHelp('calendar');?>
</ol>

<table>


<tr><th>Title</th><th>Type</th><th>Duration</th><th>Location</th></tr>

<?php
	$i=0;
	foreach ($calendar['events'] as $event) :

	$dayset = daylist($i,$event['days']);
	$eventtimeclass = 'input';
	if (empty($event['time'])){$eventtimeclass='invalid';}
?>


		<tr style='vertical-align:top;'>

			<td class='left'>Title: <br /><input type = 'text' size='20'
				name="events[<?=$i?>][title]"
				value="<?=$event['title']?>" > </td>





		<td>Type <select name="events[<?=$i?>][type]" ><?=$event['typeoptions']?></select>

			</td>


		<td>Duration <br /><input type = 'text'
				name="events[<?=$i?>][duration]"
				value="<?=$event['duration']?>" size='15'> </td>
		<td rowspan='2'>Location <input type = 'text'
				name="events[<?=$i?>][location]" size='30'
				value="<?=$event['location']?>"  />
				<br />
				<input type='checkbox' name='events[<?=$i?>][reservation]'
					<?php if ($event['reservation'] ?? ''): ?> checked <?php endif; ?>
					> Reservation Req'd
				</td>
		</tr>


		<tr  style='vertical-align:top;'>

				<td class='left' id='timetd'>Start time: <input type=text name="events[<?=$i?>][time]" size='8' value="<?=$event['time']?>" id='timeset[<?=$i?>]' placeholder = '2:30 pm' onChange='checkTime(this)' class='<?=$eventtimeclass?>'>
			</td>

				<td >On/start date: <br /><input type = 'text' size='15'
				name="events[<?=$i?>][date]"
				value ="<?= $event['date']?>" ><br />
				Repeat Ends<br />
				<input type='text' size='15' name = "events[<?=$i?>][end]" value="<?=$event['end']?>">

			</td>
			<td>

 Repeat Every: <br />
			<?=$dayset?>

			</td>

		</tr>

		<tr><td colspan='4' class='left'>
		<label><input type='checkbox' name='events[<?=$i?>][suspended]'
					<?php if ($event['suspended']): ?> checked <?php endif; ?>
					> Suspend (stop showing, don't delete.)</label> &bull;
	<label>Cancel until <input type='text' size='15'
					value="<?= $event['canceldate'] ?? ''?>" name="events[<?=$i?>][canceldate]?> " >
					</label>
					&bull;
		<label> <input type='checkbox' name='events[<?=$i?>][delete]'>
					Delete </label>
		 </td></tr>

		<tr class='left' style='border-bottom:8px solid black;'><td class='right' colspan='4'>
		Notes:
		<input type = 'text' size='80'
				name="events[<?=$i?>][note]"
				value="<?=$event['note']?>" ?? '' > </td>
		</tr>


<?php
	++$i;
	endforeach;
?>

</table>

<button class='submit' type='submit'>Submit Form</button>




