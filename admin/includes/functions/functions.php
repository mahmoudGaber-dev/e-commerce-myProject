<?php





/*
**get  All function v2.0
**function to get All from Any database table  

**
*/
//function getAllFrom($tableName , $orderBy , $where = null) {
    function getAllFrom($field , $table  , $orderfield , $where = null , $and = null , $ordering = "DESC") {
        global $con;
    /*
        if($where == null){
            $sql = "";
        }else{
            $sql = 'WHERE  Approve = 1';
        }
    */
        //$sql = $where == null ? '' : $where;
    
    
        $getAll = $con->prepare("SELECT $field FROM $table $where  $and ORDER BY $orderfield $ordering");
    
        $getAll->execute();
    
        $all = $getAll->fetchAll();
    
        return $all;
    }






//echo 'function is here';

/*
**title function v1.0
**title function that echo the page title in case the page has the variable $pagetitle
**and echo defult title for other pages
*/
function getTitle(){
    global $pageTitle;

    if (isset($pageTitle)){
        echo $pageTitle;
    }else {
        echo 'default';
    }

}

/*
**home redirect function v2.0
** [this function accept parameters] 
**$theMsg = echo the  message [error | success | warning]
**$url = the  link you want to redirect to
**$seconds = seconds before redirecting
*/
function redirectHome($theMsg ,$url = null, $seconds = 3  ){

    if ($url === null){
        $url = 'index.php';
        $link = 'homepage';
    }else {
        

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

            $url = $_SERVER['HTTP_REFERER'];
            $link = 'previous page';

        }else{
            $url = 'index.php';
            $link = 'home page';
        }

    }

    echo $theMsg ;


    echo "<div class= 'alert alert-info' >you will be redirected to $link after $seconds seconds.</div>";

    header("refresh:$seconds;url=$url");
    exit();

}

/*
**check items function v1.0
**function to check item in database [function accept parameters]
**$select = the item to select [example: user , item , category]
**$from = the table to select from [example: users , items , categories]
**$value = the value of select [example: osama , box , electronics]
*/

function checkitem($select , $from , $value){

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statement->execute(array( $value));

    $count = $statement->rowCount();

    return $count;
}

/*
**count number of items function v1.0
**function to count number of items rows
**$item = the item to count
**$table = the table to choose from
*/

function countitems($item , $table) {

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();

}

/*
**get latest records function v1.0
**function to get latest items from database [users , items , coments]
**$select = field to select
**$table = the table to choose from
**$order = the desc ordering
**$limit = number of records to get
**
*/
function getlatest($select , $table, $order , $limit = 5 ) {
    global $con;

    $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

    $getstmt->execute();

    $rows = $getstmt->fetchAll();

    return $rows;
}