<?php

/*
    Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats]

    condition ? true : false

*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;


//if the page is main page 

if ($do == 'Manage'){
  echo ' welcome you are in manage category page';
  echo '<a href="page.php?do=Add"> Add New Category +</a>';



}elseif ($do == 'Add'){

    echo ' welcome you are in Add category page';

}elseif ($do == 'Insert'){
    echo ' welcome you are in insert category page';


}else{
    echo 'erorr there\'s no page with this name';
}