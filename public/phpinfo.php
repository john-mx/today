
<?php
if (!$container['Login']->checklevel(basename(__FILE__))) exit;
$cli = `php -v`;
echo "CLI: $cli" ;
phpinfo();
?>
