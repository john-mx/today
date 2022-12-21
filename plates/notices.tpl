<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Announcements and Alerts</h4>
<div class='inleft2'>
<?php
	//u\echor($notices,'notices');
	$d= $notices['alerts'] ??'';
	$e = $notices['announcements'] ?? '';
	if ( $d || $e) :
?>
    <?php if ($d):?>
    <div class=' warn red' >
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
    <div class=' warn' >

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
	<?php else: ?>
	None at this time.
	<?php endif; ?>
</div>
