<?php

function lang($phrase){

    static $lang = array (

        //dashboard page

        //navbar links

        'Home_Admin'     => 'Home',
        'CATEGORIES'     => 'Categories',
        'ITEMS'          => 'Items',
        'MEMBERS'        => 'Members',
        'COMMENTS'       => 'Comments',
        'STATISTICS'     => 'Statistics',
        'LOGS'           => 'Logs',
        '' => '',
        '' => '',
        '' => '',
        '' => ''



        /*

        'message' => 'welcome',

        'admin' => 'administrator'
        */
    );

    return $lang[$phrase];
}





    /*

    $lang = array(

        'osama' =>  'zero'

    );

echo $lang['osama'];
*/