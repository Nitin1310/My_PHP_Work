<?php
require_once('../t.class.php');
$t=new t();

echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
echo date('Y-m-d H:i:s');
?>