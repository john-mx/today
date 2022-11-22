<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Cal = $container['Calendar'];


//END START





if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == 'update' ){
#u\echor ($_POST,'Post',false);
$z = $Cal->prepare_calendar($_POST['calendar']);
u\echor ($z);

$Cal->write_cache('calendar',$z);

$z=$Cal->filter_calendar($z,4);
u\echor($z,'4 day events');
exit;

}

$c = $Cal->load_cache();
u\echor($c);

$calendar = $Cal->filter_calendar($c,0);
#u\echor($calendar,'cal',true);


#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $Cal::$empty_cal;
	}
$platedata = array('calendar'=>$calendar);
echo $Plates->render('caladmin',$platedata);
exit;
?>

<form method=post>
<h4>Calendar</h4>
<p>(Will be drawn from park calendar when possible.  Until then use this. Items will be sorted by actual date/time when saved.)<br />
Items with days checked recur until changed.
Set a time to "0" to delete an entry
Items with only a date will be removed after the date has passed.

</p>


<table class='in2'>
<colgroup>
<col class='left'>
<col >
<col>
<col>
</colgroup>

<tr><th>Date/Days</th><th>Time</th><th>Title</th><th>Location</th><th>Type</th><th>Duration</th></tr>
<?php

	for ($i=0;$i<$csize+3;++$i) {
		$event = array_shift( $calendar ) ?? $Cal::$empty_cal;
		//u\echor ($event,$i,false);
		$dayset = $Cal->dayset($i,$event['days']);


		echo <<<EOT
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
EOT;

}

?>

</table>
<input type='hidden' name='type' value='update'>
<button type='submit'>Submit Form</button>
</form>






