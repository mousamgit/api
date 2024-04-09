<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
error_reporting(E_ALL);
ini_set('display_errors', '1');
include 'routes/web.php';
include 'routes/api.php';

