<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<?php
	//u\echor($notices,'notices');
	$d= $notices['alerts'] ??'';
	$e = $notices['announcements'] ?? '';
	if ( $d || $e) :
?>

  <h4>Announcements and Alerts</h4>
  <div class='inleft2'>
    <?php if ($d):?>
    <div class='float warn red' style='width:45%;'>
        <?php
                echo "<ul>";
                $anlist = explode("\n",$d);
                        foreach ($anlist as $item) :
                                if (empty(trim($item))):continue;endif;
                                echo "<li>$item</li>";
                        endforeach;
                echo "</ul>" . NL;
        ?>
    </div>
    <?php endif; ?>

    <?php if($e) : ?>
    <div class='float warn' style='width:45%;'>

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
 	 <div class='clear'></div>
  </div>
<?php endif; ?>
