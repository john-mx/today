<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;



//BEGIN START

	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\DisplayManager;

	$Plates = $container['Plates'];
	$DM = $container['DisplayManager'];

//END START

//Utilities::echor($y,'y',STOP);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Local Settings',

	]);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('body',$meta);
	echo $Plates->render('title',$meta);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//	Utilities::echor ($_POST, 'post');
	$local['rotate'] = $_POST['rotate']??[];
	$local['rdelay'] = $_POST['rdelay']??'';
	$local['local_site']=  $_POST['local_site'] ?? '';
	$local['hide_js'] = isset($_POST['hide_js'])? true:false;
	$_SESSION['local'] = $local;

	Log::info("Local settings saved");
	//Utilities::echor($_SESSION,'session');
	echo "<script>self.close();</script>";
	return true;

}
// set up form
$admin = $DM->build_topic_admin()['admin'];
//Utilities::echor($admin);

$local = $_SESSION['local'] ?? [];
//Utilities::echor($local);

$rchecked = [];

$rotators = ($local['rotate'] ?? '') ? $local['rotate'] : $admin['rotate'];
$rdelay = ($local['rdelay'] ?? '' ) ? $local['rdelay'] : $admin['rdelay'];
$hide_js = ($local['hide_js'] ?? '') ? true:false;
//echo ($rdelay) . BR;
foreach (LS::getRemotePageKeys()  as $pid){
		if (in_array($pid,$rotators)){$rchecked[$pid] = 'checked';}
}
$site_array = [];
foreach (['29vc','jtvc','cwvc','brvc','hqvc','park','none'] as $vc){

	$site_array[$vc] = LS::getLocName($vc);
}
$checked = $local['local_site'] ?? '';
$site_options = Utilities::buildRadioSet(
    'local_site',
    $site_array,
    $check = $checked,

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


Select which pages should appear in the rotation sequence (?snap).  If you only choose one, it will show continuously.  If you choose none, the today page will show.<br />
<?php foreach (LS::getRpageArray() as $pid=>$pdesc) : ?>
<div style='white-space:nowrap;display:inline;'><label><input type='checkbox' name='rotate[]' value="<?=$pid?>" <?=$rchecked[$pid] ?? ''?> >&nbsp;&nbsp;<!-- <a href='/pager.php?<?=$pid?>' target = 'pager' --><?=$pid?>: <?=$pdesc?><label></div><br />
<?php endforeach; ?>
<br />
<h4>Rotation Delay</h4>
Set rotation time in seconds: <input type='number' name='rdelay' value="<?=$rdelay?>" size='8' min=0 max=30 step=5 > (0 uses default)
<br />
<h4>Disable Time displays</h4>
Use checkbox below to disable time and sunset displays in title bar on rotation, if TV browser is not displaying them correctly. (Javascript issue)<br/>
<div style='white-space:nowrap;display:inline;'>
	<label>
		<input type='checkbox' name='hide_js'
			<?php if ($hide_js) : ?>checked <?php endif; ?> />&nbsp;&nbsp;Hide time
		<label>
	</div><br />

<button type='submit'>Submit Form</button>

</form>
