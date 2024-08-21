<?php

/*
========================
== template page
======================== 
*/

ob_start(); //output buffering start 

session_start();

$pageTitle = '';



if (isset($_SESSION['username'])) {
   //echo 'welcome' . $_SESSION['username'];
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;


   if ($do == 'Manage'){
    echo'welcome';

    } elseif ($do == 'Add'){

    } elseif ($do == 'Inser'){

    } elseif ($do == 'Edit'){

    } elseif ($do == 'Update'){

    } elseif ($do == 'Delete'){

    } elseif ($do == 'Activate'){

    }
    
    include $tpl . "footer.php";
    
}else{
    //echo 'you are noy authorized to view this page' ;

    header('Location: index.php');
    
    exit();
}

ob_end_flush(); //release the output

?>