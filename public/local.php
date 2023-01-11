<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


ini_set('display_errors', 1);

//BEGIN START

	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];

	$Today = $container['Today'];


//END START





//Utilities::echor($y,'y',STOP);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(

	'page' => basename(__FILE__),
	'subtitle' => 'Local Options',


	);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('title',$meta);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//Utilities::echor ($_POST, 'post');
	$local['rotate'] = $_POST['rotate'];
	$local['rdelay'] = $_POST['rdelay'];
	$local['local_site']=  $_POST['local_site'];
	$_SESSION['local'] = $local;

	Log::info("Local settings saved");
	//Utilities::echor($_SESSION,'session');
	echo "<script>
		window.opener.location.reload();
		window.close();
		</script>";
	return true;

}
// set up form
$admin = $Today->build_topic_admin()['admin'];
//Utilities::echor($admin);

$local = $_SESSION['local'] ?? [];
//Utilities::echor($local);

$rchecked = [];

$rotators = ($local['rotate'] ?? '') ? $local['rotate'] : $admin['rotate'];
$rdelay = ($local['rdelay'] ?? '' ) ? $local['rdelay'] : $admin['rdelay'];
//echo ($rdelay) . BR;
foreach (array_keys(Defs::$rpages) as $pid){
		if (in_array($pid,$rotators)){$rchecked[$pid] = 'checked';}
}
$site_array = [];
foreach (['29vc','jtvc','cwvc','brvc','hqvc','park'] as $vc){

	$site_array[$vc] = Defs::$sitenames[$vc];
}
$site_options = Utilities::buildRadioSet(
    'local_site',
    $site_array,
    $check = $local['local_site'] ?? '',

    $per_row = 1,
    $show_code = false,
    );
?>

<h3>Local Options</h3>
These setting apply only to the device this page is running on.
If the site is not accessed on this device for 48 hours, the settings will be revert to the standard setting, and must be set again if you want your own settings.

<hr>
<form method='POST'>
<h4>Which site is this?</h4>
<?=$site_options?>

<h4>Choose Pages for TV Rotation</h4>
<?php //Utilities::echor($admin); ?>
Set which site you are in:

Select which pages should appear in the rotation sequence (?snap)<br />
<?php foreach (Defs::$rpages as $pid=>$pdesc) : ?>
<input type='checkbox' name='rotate[]' value='<?=$pid?>' <?=$rchecked[$pid] ?? ''?> ><a href='/pager.php?<?=$pid?>' target = 'pager'><?=$pid?></a>: <?=$pdesc?><br />
<?php endforeach; ?>
<br />
Set rotation delay in seconds: <input type='number' name='rdelay' value='<?=$rdelay?>' size='8' min=10 max=30 step=5 >

<button type='submit'>Submit Form</button>

</form>
