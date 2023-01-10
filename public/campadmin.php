<?php

use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	
	$Today = $container['Today'];
	$Login = $container['Login'];
	$Cal = $container['Calendar'];

$open_options = u\buildOptions(['','0','1-3','4-9','10+','?'],'',true);

$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Camp Admin',
	'extra' => "",

	);

if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}


$Login->check_pw(1);

//u\echor($_POST,'post');

if (!empty($_POST) && !isset($_POST['pw'])) {
		post_data ($_POST,$Today);
		echo "<script>window.location.href='/campadmin.php';</script>";
		exit;

} else {

// get calendar


	echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

		$y = $Today-> prepare_admin();
// u\echor($y);
		echo $Plates->render('admin',$y);


	exit;
?>
<script>
function clearopen(){
    var tObj = document.getElementsByClassName('cgo');
    for(var i = 0; i < tObj.length; i++){
        tObj[i].value='';
    }
}

</script>

if ($_SERVER['REQUEST_METHOD'] == 'POST'){


<form method='post'

<h4>Campground status</h4>


<!--
<p><input type='checkbox' name='cgfull'
> Check to force all campgrounds full until unset.</p>
 -->
<p>
Enter update to available sites.  No entry means keep current value.
Reservation sites updated (not implemented yet) from rec.gov hourly.
</p>

<p>
Uncertainty.  <input type='number' name='uncertainty' size='4' value="<?=$admin['uncertainty'] ?? 0 ?>" min=0 max=12 > Enter number of hours the new site vacancy setting is valid.  Will be displayed to users  as '?' after the time has lapsed.
</p>

<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>Update</th><th>Notes</th></tr>
<?php foreach (array_keys(Defs::$campsites) as $scode): ?>
	<tr><td><?= Defs::$sitenames[$scode] ?></td>

		<td><select name="cgstatus[<?=$scode?>]"><?=$admin['cg_options'][$scode]?></select></td>
		<td><?=$admin['cgsites'][$scode]?> </td>
		<td> <!--
<input type='text' name="cgopen[<?=$scode?>]"
			 size='8' class ='cgo'>
 -->
			 <select name="cgupdate[<?=$scode?>]" class='cgo'><?=$open_options?></select></td>

		<td><input type='text' name="cgnotes[<?=$scode?>]>"
		<?php if (isset($admin['cgnotes'])) : ?>
		value='<?=$admin['cgnotes'][$scode]?>' <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>
Click to <button type='button' onClick='clearopen()'> clear all site updates</button> (clear = no change) <br />


