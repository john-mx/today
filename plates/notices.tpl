<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php

	$e = $admin['notices']['announcements'] ?? '';


?>
 <h4>Announcements and Closures</h4>
    <div class=' inleft2 left' >

        <?php if($e) : ?>
                echo "<ul>";
                $anlist = explode("\n",$e);
                        foreach ($anlist as $item) :
                                if (empty(trim($item))):continue;endif;
                                echo "<li>$item</li>";
                        endforeach;
                echo "</ul>" . NL;
        ?>

    <?php else: ?>
    None at this time
    <?php endif; ?>
 </div>


