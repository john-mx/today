<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;




?>

<?php

	$e = $admin['notices']['announcements'] ?? '';
	$animation = $rotation['animation'] ?? '';



?>
 <h3>Announcements and Closures</h3>
    <div class=' indent2 left' >
		 <ul>
        <?php if($e) :
                $anlist = explode("\n",$e);
                        foreach ($anlist as $item) :
                                if (empty(trim($item))):continue;endif;
                                echo "<li>$item</li>";
                        endforeach;
        ?>

    <?php else: ?>
    <li>None at this time
    <?php endif; ?>
     </ul>
 </div>


