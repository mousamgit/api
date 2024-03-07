<?php

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: https://pim.samsgroup.info/login.php");
    exit();
}
?>