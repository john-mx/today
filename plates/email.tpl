<<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>
<?php
	$site_path = SITE_PATH;
	$site_url = SITE_URL;

	// set up styles
	$fsan = 'font-family:arial,helvetica,sanserif;';
	$fser = "font-family:'Times New Roman',times,serif;";
	$fb = 'font-weight:bold;';
	$tc = 'text-align:center;';
	$tl = 'text-align:left;';
	$tsb = 'font-size:1.2em;';
	$tu = 'text-decoration:underline;';
	$b0 = 'border:0;';
	$bbg = 'border-bottom:1px solid gray;';
	$bc = 'border-collapse:collapse;';
	$mgh = 'margin-top:1em;margin-bottom:0.2em;';
	$ml = 'margin-left:2em;';
	$tvt = 'vertical-align:top;';

	$sgtitle = "$fsan $fb $tc $tsb $tu"; // for main title
	$sgsub="$fsan $tc $fb";	// for subtitle
	$sgsh = "$fsan $fb $tl "; // for section heads
	$sgtd	= "$fsan $tl ";
	$sgtdc = "$fsan $tc";
	$sgtdb	= "$fsan $tl $bbg";
	$sgtdcb	= "$fsan $tc $bbg";

	$sitenames = Defs::$sitenames;


echo <<<EOT

<table border:0; margin:6px;max-width:90vw'>
<tr><td style="$sgtitle">
Today in Joshua Tree National Park</td></tr>
<tr><td style="$sgsub">
$target</td></tr>
EOT;

if(!empty($admin['pithy'])):
	echo <<<EOT
	<tr><td style="$fser $tc"><i>${admin['pithy']}</i></td></tr>
EOT;
endif;

if (!empty($admin['alerts'])) :
	echo <<<EOT
	<tr><td style="$tl">
	<p style="$sgsh">Active Alerts </p>
	<ul>
EOT;
	$anlist = explode("\n",$admin['alerts']);
		foreach ($anlist as $item) :
			if (!empty(trim($item))):
				echo "<li style='color:red;'>$item</li>";
			endif;
		endforeach;
		echo <<<EOT
		</ul>
		<br />
	</td></tr>
EOT;
endif;
?>



<?php if(!empty($admin['announcements'])) :
	echo <<<EOT
<tr><td style="$tl">
	<p style="$sgsh">Announcements</p>
	<ul>
EOT;
	$anlist = explode("\n",$admin['announcements']);
		foreach ($anlist as $item) :
			if (!empty(trim($item))):
				echo "<li>$item</li>";
			endif;
		 endforeach;

echo <<<EOT
	</ul>
	<br />
	</td>
	</tr>
EOT;

endif;
?>

<?php
echo <<<EOT
<tr><td style="$tl">
<p style="$sgsh">Light and Dark</p>
	<div style="$ml">
	Sunrise ${light['sunrise']} Set ${light['sunset']}<br />
Moonrise ${light['moonrise']} Set ${light['moonset']} ${light['moonphase']}
</div>
</td></tr>

<tr><td style="$tl">
	<p style="$sgsh">UV Exposure:</p>

	<p style = "$fsan background-color:${uv['uvcolor']};"> <b>${uv['uv']}</b>  ${uv['uvscale']}</p>
	<div style="$ml">
	<b>For UV = ${uv['uvscale']}</b><br />${uv['uvwarn']}

	</div>
</td></tr>

<tr><td style="$tl">
<p style="$sgsh">Fire Danger: </p>
	 	<p  Current Level: <span style='background-color:${fire['color']}'>
	 	<b>${fire['level']}</b> </span></p>
EOT;
	$fl = $fire['level'];
	$fw = Defs::$firewarn[$fl];
	echo "<div style='$ml'>$fw</div>
</td></tr>
";


?>

<?php
echo <<<EOT
<tr><td style="$tl">
<p style="$sgsh">Air Quality</p>
<table style="$ml $b0">
<tr><th>Location</th><th>Air Quality</th><th>Particulates<br> (PM10)</th><th>Ozone</th></tr>
EOT;
foreach ($air as $loc => $dat) :
	if (! in_array($loc,array_keys($sitenames))) continue;
	// not a valid locaiton

	$rdt = date ('M j H:ia',$dat['dt']);
echo <<<EOT
<tr style="$bbg">
	<td style="$tl">${sitenames[$loc]} </td>
	<td style="$tc">${dat['aqi']}
		<span style="background-color: ${dat['aqi_color']}">
		${dat['aqi_scale']}</span>
		</td>
	<td style="$tc">${dat['pm10']}</td>
	<td style="$tc">${dat['o3']}</td>
</tr>
EOT;
endforeach;
echo <<<EOT
</table>
<br />
</td></tr>
EOT;
?>

<?php
echo <<<EOT
<tr><td style='text-align:left;'>
<p style="$fsan $fb $mgh $tl">Weather</p>
EOT;
	$weather = $wapi['fc'];
	$periods = [0,1,2];
echo <<<EOT
	<table style="$ml $bc width:90%; ">
			<tr><th></th>
EOT;

		foreach ($periods as $p) :
			echo "<th>{$weather['forecast']['jr'][$p]['date']}</th>";
		endforeach;

		echo "</tr>";

	foreach ($weather['forecast'] as $loc => $x ) : //x period array
			if (! $locname = $sitenames[$loc] ) : continue; endif;
	echo <<<EOT
			<tr style="$bbg">
			<td  style=$bbg"><b>$locname</b></td>
EOT;

				foreach ($periods as $p) :
					echo  "<td style='$bbg'><p>";

						$v = $x[$p]['skies'] ;
						echo "$v<br />";

						$v = $x[$p]['Low'] ;
						$w = $x[$p]['High'] ;
						echo "Low: $v High: $w  &deg;F<br />";

						$v = $x[$p]['maxwind'] ;
						echo "Wind to $v mph <br />";

						$v = $x[$p]['avghumidity'] ;
						echo "Humidity: $v %<br />";

						$v = $x[$p]['rain'] ;
						echo "Rain $v %<br />";

					echo 	"</p></td>\n" ;
				endforeach;
			echo "</tr>";
	 endforeach;
	 echo "
	</table>
	<br />
</td></tr>";
?>

<?php echo <<<EOT
<tr><td style="$tl">
<p style="$sgsh">Campgrounds</p>
EOT;

if (!empty($campgroundadivse)) :
	echo "<div $campgroundadvise</div> ";
endif;

if(empty($camps)): echo "No Data"; else:
echo <<<EOT
<table style="$ml $bc width:90%; ">
<tr><th></th><th>Availability</th><th>Status</th></tr>
EOT;
foreach (['ic','jr','sp','hv','be','wt','ry','br','cw'] as $cg) :
echo <<<EOT

	<tr>
	<td style="$sgtdb">  {$sitenames [$cg]}  </td>
	 <td  style="$sgtdb"> {$camps['cgavail'][$cg]}  </td>
	<td style="$sgtdb"> {$camps['cgstatus'][$cg]}  </td>
	</tr>
EOT;
	endforeach;
echo <<<EOT
</table>

<br />
EOT;
endif;
echo "</td></tr>";
?>


<?php
echo <<<EOT
<tr><td style='text-align:left;'>
<p style="$sgsh">Events</p>
EOT;
if(empty($calendar)) : echo "No Data"; else:
echo <<<EOT
<table style="$ml $bc width:90%; ">
<!-- <tr><th>Date and Time</th><th>Location</th><th>Type</th><th>Title</th></tr> -->
<tbody>
EOT;

	$calempty = 1;
	foreach ($calendar as $cal) :
	// stop looking if more than 3 days out
if ( ($cal['dt'] < time() ) || ($cal['dt'] > (time() + 3600*24*3 ) ) ) :
	continue;
	endif;
	$calempty = 0;
	$datetime = date('l M j g:i a', $cal['dt']);
	echo <<<EOT

	<tr >
	<td style="$sgtdb">$datetime  <br />
	&nbsp;&nbsp;(${cal['duration']}) </td>

 	<td style="$sgtdb">
 	<b>${cal['title']}</b><br />
 	${cal['type']} at ${cal['location']} <br />
EOT;
	if (!empty($cal['note'])) :
		echo "${cal['note']} </p>";
	endif;
	echo "</td>
 </tr>";

 endforeach;
if($calempty): echo "No Events in next 3 days"; endif;
echo "</tbody></table>";
endif;
?>



<?php echo "
</td></tr>
</table>
";
?>

