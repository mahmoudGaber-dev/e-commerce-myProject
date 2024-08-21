<?php

//Erorr Reporting

ini_set("display_errors","on");
error_reporting(E_ALL);




include 'admin/connect.php';


$sessionUser = '';

if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}

//routs

$tpl = 'includes/templets/'; //template directory
/*
echo $tpl;
*/
$lang = 'includes/languages/'; //language directory
$func='includes/functions/'; //functions directory
$css = 'layout/css/' ; //css directory
$js = 'layout/js/' ; //js directory



//include the important files

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . "header.php"; 




