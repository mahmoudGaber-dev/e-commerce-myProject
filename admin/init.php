<?php


include 'connect.php';

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

//include navbar on all pages expect the one with $nonavbar variable

if (!isset($nonavbar)) {
    include $tpl . "navbar.php"; 
}


