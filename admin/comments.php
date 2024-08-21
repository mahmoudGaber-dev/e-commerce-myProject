<?php

/*
========================
== manage comments page
== you can  edit | delete | Approve comments from here
======================== 
*/

ob_start(); 

session_start();

$pageTitle = 'comments';



if (isset($_SESSION['username'])) {
   //echo 'welcome' . $_SESSION['username'];
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

   //start manage page

   //----------------------Manage-------------------------//

   if ($do == 'Manage'){ //manage members page




   //select all users expcept admin

   $stmt = $con->prepare("SELECT comments.*, items.Name AS Item_Name, users.username AS Member FROM comments 
                         INNER JOIN items ON items.Item_ID = comments.item_id  
                         INNER JOIN  users ON users.userid = comments.user_id ORDER BY c_id DESC");

    //execute the statement


   $stmt->execute();


   //assign to variable

   $comments = $stmt->fetchAll();

   if(!empty($comments)){
   
   ?>

    <h1 class="text-center">Manage comments</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>ID</td>
                    <td>Comment</td>
                    <td>Item Name</td>
                    <td>User NAME</td>
                    <td>Added Date</td>
                    <td>Control</td>
                </tr>

                <?php 

                     foreach ($comments as $comment) {

                        echo "<tr>" ;
                        echo "<td>" . $comment['c_id'] . "</td>" ;
                        echo "<td>" . $comment['comment'] . "</td>" ;
                        echo "<td>" . $comment['Item_Name'] . "</td>" ;
                        echo "<td>" . $comment['Member'] . "</td>" ;
                        echo "<td>" . $comment['comment_date'] . "</td>" ;
                        echo "<td>
                            <a href='comments.php?do=Edit&comid=". $comment['c_id'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='comments.php?do=Delete&comid=". $comment['c_id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                             
                            if ($comment['status'] == 0) {
                              echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] . " ' class='btn btn-info activate '><i class='fa fa-close'></i> Approve </a>";

                            }

                         echo " </td>" ;
                        echo "</tr>" ;

                     }
                ?>




                <tr>
            </table>
        </div>


    </div>

    <?php }else{
        echo '<div class="container">';
        echo '<div class="nice-message">There\'s no comments to Show</div>';
        echo '</div>';

    }   
    ?>      




  <?php

            //-------------------------Edit--------------------------------------//


    }elseif  ($do == 'Edit'){ //edit page 

    //check if get request comid is numeric & get the integer value of it

   $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0 ;
   //select all data depend on this id
    $stmt = $con->prepare("SELECT  * from  comments where c_id  = ? ");

    //execute query

    $stmt->execute(array($comid));

    //fetch the data

    $row = $stmt->fetch();

    //the row count

    $count =  $stmt->rowCount();

    //if there is such id show the form

    if ($count > 0) {?>
        
            <h1 class="text-center">Edit Comment</h1>
            <!-- start comment field -->
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" >
                    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                            <textarea name="comment" class="form-control"><?php echo $row['comment'] ?></textarea>
                        <div class="col-sm-10 col-md-4">
                        </div>
                    </div>
            <!-- end comment field -->

            <!-- start submit field -->
            <div class="container">
                <form class="form-horizontal" >
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
            <!-- end submit field -->
                </form>
            </div>

   <?php 

        //if there is no such id show error message
    }else{
        echo '<div class = container>';

        $theMsg = '<div class = "alert alert-danger" >there\'s no such id</div>' ;

        redirectHome($theMsg );

        echo '</div>';
    }

                //-------------------------Update--------------------------------------//



}elseif ($do == 'Update'){ //Update page
   echo " <h1 class='text-center'>Update Comment</h1> " ;
   echo "<div class = 'container'>";

    if ($_SERVER['REQUEST_METHOD']  == 'POST'){

        //get variables from the form

        $comid     = $_POST['comid'];
        $comment   = $_POST['comment'];






        //Update the database with this info

        $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?   ");
        $stmt->execute(array($comment , $comid  ));

        //echo success message

        $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;

        redirectHome($theMsg , 'back');

        




    }else{
        $theMsg = '<div class = "alert alert-danger">sorry you can\'t browse this page directly</div>';
        redirectHome($theMsg );
    }

    echo"</div>";

                //-------------------------Delete--------------------------------------//


} elseif ($do == 'Delete') {  //Delete  page

    echo " <h1 class='text-center'>Delete Comment</h1> " ;
    echo "<div class = 'container'>";
 

   

            //check if get request comid is numeric & get the integer value of it

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0 ;
            //select all data depend on this id


            $value = $comid;
            $check = checkitem("c_id" , "comments" , $value) ;

        
        
            //if there is such id show the form
        
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");

                $stmt->bindParam(":zid", $comid );

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Deleted</div>' ;

                redirectHome($theMsg, 'back');


        
            }else{
                $theMsg = "<div class = 'alert alert-danger'>this id is not exist</div>";

                redirectHome($theMsg);

            }
                
     echo "</div>";


                     //-------------------------Approve--------------------------------------//




}elseif ($do == "Approve") { 

    echo " <h1 class='text-center'>Approve Comment</h1> " ;
    echo "<div class = 'container'>";
 

   

            //check if get request comid is numeric & get the integer value of it

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0 ;
            //select all data depend on this id

            /*$stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");*/

            $value = $comid;
            $check = checkitem("c_id" , "comments" , $value) ;

        
            //if there is such id show the form
        
            if ($check > 0) {

                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");


                $stmt->execute(array($comid));

                $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Approved</div>' ;

                redirectHome($theMsg, 'back');


        
            }else{
                $theMsg = "<div class = 'alert alert-danger'>this id is not exist</div>";

                redirectHome($theMsg);

            }
                
     echo "</div>";

}


   include $tpl . "footer.php";
    
}else{
    //echo 'you are noy authorized to view this page' ;

    header('Location: index.php');
    
    exit();
}

ob_end_flush();

?>