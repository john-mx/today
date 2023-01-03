<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php

	$d= $admin['notices']['alerts'] ??'';
	$e = $admin['notices']['announcements'] ?? '';
	if ( $d || $e) :

?>

  <?php if (0): ?>
  <!-- 	<h4 class='red'>Alerts</h4> -->
  	<?php foreach ($d as $alert):
   	echo $alert;
  	endforeach; ?>
	<?php endif; ?>



    <?php if($e) : ?>
    <h4>Announcements and Closures</h4>
    <div class=' warn inleft2' >

        <?php
                echo "<ul>";
                $anlist = explode("\n",$e);
                        foreach ($anlist as $item) :
                                if (empty(trim($item))):continue;endif;
                                echo "<li>$item</li>";
                        endforeach;
                echo "</ul>" . NL;
        ?>

    </div>
    <?php endif; ?>

<?php endif; ?>

