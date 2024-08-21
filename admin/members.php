<?php

/*
========================
== manage members page
== you can add | edit | delete members from here
======================== 
*/

ob_start(); 

session_start();

$pageTitle = 'Members';



if (isset($_SESSION['username'])) {
   //echo 'welcome' . $_SESSION['username'];
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

   //start manage page

   //----------------------Manage-------------------------//

   if ($do == 'Manage'){ //manage members page


    $query ='';

    if (isset($_GET['page']) && $_GET['page'] == 'pending') {

        $query = 'AND regstatus = 0';

    }




   //select all users expcept admin

   $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query  ORDER BY userid DESC");

    //execute the statement


   $stmt->execute();


   //assign to variable

   $rows = $stmt->fetchAll();


   if (!empty($rows)) {
   
   ?>

    <h1 class="text-center">Manage members</h1>
    <div class="container ">
        <div class="table-responsive">
            <table class="main-table manage-members text-center table table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Avatar</td>
                    <td>USERNAME</td>
                    <td>EMAIL</td>
                    <td>FULL NAME</td>
                    <td>Registerd Date</td>
                    <td>Control</td>
                </tr>

                <?php 

                     foreach ($rows as $row) {

                        echo "<tr>" ;
                        echo "<td>" . $row['userid'] . "</td>" ;
                        echo "<td>" . $row['username'] . "</td>" ;

                        echo "<td>";
                        if(empty($row['avatar'])){
                            echo "<img src='uploads/avatars/img.png' alt='' />" ;;
                        }else{
                           echo  "<img src='uploads/avatars/" . $row['avatar'] ."' alt='' />" ;
                        }
                            echo "</td>" ;

                        echo "<td>" . $row['email'] . "</td>" ;
                        echo "<td>" . $row['fullname'] . "</td>" ;
                        echo "<td>" . $row['Date'] . "</td>" ;
                        echo "<td>
                            <a href='members.php?do=Edit&userid=". $row['userid'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='members.php?do=Delete&userid=". $row['userid'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                             
                            if ($row['regstatus'] == 0) {
                              echo "<a href='members.php?do=Activate&userid=". $row['userid'] . " ' class='btn btn-info activate '><i class='fa fa-close'></i> Activate </a>";

                            }

                         echo " </td>" ;
                        echo "</tr>" ;

                     }
                ?>




                <tr>
            </table>
        </div>

    <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>  New Member</a>

    </div>
    <?php }else{
        echo '<div class="container">';
        echo '<div class="nice-message">There\'s no Members to Show</div>';
        echo '    <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>  New Member</a>';
        echo '</div>';

    }         

    ?>




   <?php 
} elseif ($do == 'Add'){  //---------add members page--------------// ?>   



    <h1 class="text-center">Add New members</h1>
    <div class="container member-cont">
        <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data" >
            <!-- start username field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="username" class="form-control" required="required"  autocomplete="off"  placeholder="username to login into shop " />
                    </div>
                </div>
            <!-- end username field -->

            <!-- start password field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="password" name="password" required="required" class="password form-control" autocomplete="new-password"  placeholder="password must be hard and complex "   />
                                <i class="show-pass fa fa-eye fa-2x"></i>
                            </div>
                        </div>
            <!-- end password field -->
             
            <!-- start Email field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="email" name="email" required="required"  class="form-control "  placeholder="email must be valid" />
                            </div>
                        </div>
            <!-- end Email field -->

            <!-- start Full Name field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full"  class="form-control" required="required"  placeholder="full name appear in your profile page" />
                            </div>
                        </div>
            <!-- end Full Name field -->

            <!-- start Avatar field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">User Avatar</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="file" name="avatar"  class="form-control" required="required"  />
                            </div>
                        </div>
            <!-- end Avatar field -->

            <!-- start submit field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                            </div>
                        </div>
            <!-- end submit field -->
        </form>
    </div>

    
   <?php 
            //---------------------insert---------------------------//
   }elseif ($do == 'Insert' ) {

    //Insert member page

 
     if ($_SERVER['REQUEST_METHOD']  == 'POST'){

        echo " <h1 class='text-center'>Update members</h1> " ;
        echo "<div class = 'container'>";
    
        // upload variabls

        $avatarName = $_FILES['avatar']['name'] ;
        $avatarSize = $_FILES['avatar']['size'] ;
        $avatarTmp = $_FILES['avatar']['tmp_name'] ;
        $avatarType = $_FILES['avatar']['type'] ;

        //list of allowed file typed to upload

        $avatarAllowedExtension = array('jpeg','jpg','png','gif') ;

        //get Avatar Extension
        $avatarExtensionExplowded =  explode('.', $avatarName) ;
        $avatarExtension = strtolower(end ($avatarExtensionExplowded));




 
         //get variables from the form
 
         $user   = $_POST['username'];
         $pass   = $_POST['password'];
         $email  = $_POST['email'];
         $name   = $_POST['full'];
 
        $hashpass = sha1($_POST['password']);


         //echo $pass;
         //password trick
 
         //condition ? true : false ;
 
 
 
 /*
         if(empty($_POST['newpassword'])){
 
             $pass = $_POST['oldpassword'];
 
         }else{
             $pass = sha1($_POST['newpassword']);
         }
 */
 
 
         //validate the form 
 
         $formerrors = array();
 
         if(strlen($user) < 4){
             $formerrors[] = 'Username cant be less than <strong>4 characters</strong>';
 
         }
         if(strlen($user) > 20){
             $formerrors[] = 'Username cant be more than <strong>20 characters</strong>';
 
         }
 
         if(empty($user)){
             $formerrors[] = 'Username cant be <strong>empty</strong>';
         }
         if(empty($pass)){
             $formerrors[] = 'password cant be <strong>empty</strong>';
         }
         if(empty($name)){
             $formerrors[] = 'Full name cant be <strong>empty</strong>';
 
         }
         if(empty($email)){
             $formerrors[] = 'Email cant be <strong>empty</strong>';
 
         }

         if(! empty($avatarName) && ! in_array($avatarExtension , $avatarAllowedExtension)){
            $formerrors[] = 'This Extension Is Not <strong>Allowed</strong>';
        }

         if(empty($avatarName)){
            $formerrors[] = 'Avatar Is  <strong>Required</strong>';
        }

 
         if($avatarSize > 4194304){
            $formerrors[] = 'Avatar can\'t Be Larger Than  <strong>4MB</strong>';
        }

 
         //loop into errors array and echo it

         foreach($formerrors as $error){
             echo '<div class = "alert alert-danger">' . $error . '</div>' ;
         }
 
         //check if there is no error proceed the update operation
 
         if(empty($formerrors)){

            //-----------avatar-------------------

            $avatar = rand(0,100000000) . '_' .  $avatarName ;
            echo  $avatar ;

            move_uploaded_file($avatarTmp,"uploads\avatars\\" . $avatar);
             

           
                      //check if user exist in database
                      $value = $user;
                      $check = checkitem("username" , "users" , $value) ;
                  
                      if ($check == 1) {
                          $theMsg = "<div class = 'alert alert-danger'>sorry this user is exist</div>";
                          redirectHome($theMsg , 'back');
                      }else{

                        
                    
    
                        //Insert  user info in database
                        $stmt= $con->prepare("INSERT INTO 
                                                    users(username , password , email , fullname ,regstatus, Date, avatar )
                                            VALUES(:zuser , :zpass , :zmail , :zname , 1 , now() , :zavatar    ) ");
        
                        $stmt->execute(array(

                            'zuser'     => $user,
                            'zpass'     => $hashpass,
                            'zmail'     => $email,
                            'zname'     => $name,
                            'zavatar'   => $avatar

                        ));
                        
                        //echo success message
    
                        $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Inserted</div>' ;

                        redirectHome($theMsg , 'back' );
 
                    }
 
         }
 
 

 
     }else{

        echo"<div class='container'>";

         $theMsg = '<div class= "alert alert-danger"> sorry you can\'t browse this page directly</div>';

         redirectHome($theMsg);
         echo "</div>";
     }
 
     echo"</div>";
 
            //-------------------------Edit--------------------------------------//


    }elseif  ($do == 'Edit'){ //edit page 

    //check if get request userid is numeric & get the integer value of it

   $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
   //select all data depend on this id
    $stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");

    //execute query

    $stmt->execute(array($userid));

    //fetch the data

    $row = $stmt->fetch();

    //the row count

    $count =  $stmt->rowCount();

    //if there is such id show the form

    if ($count > 0) {?>
        
            <h1 class="text-center">Edit members</h1>
            <!-- start username field -->
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" >
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">username</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['username'] ?>" autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- end username field -->
            <!-- start password field -->
            <div class="container">
                <form class="form-horizontal" >
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="leave lank if you dont want to change " />
                        </div>
                    </div>
                    <!-- end password field -->
            <!-- start Email field -->
            <div class="container">
                <form class="form-horizontal" >
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control " required="required" />
                        </div>
                    </div>
                    <!-- end Email field -->
            <!-- start Full Name field -->
            <div class="container">
                <form class="form-horizontal" >
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" value="<?php echo $row['fullname'] ?>" class="form-control" required="required"  />
                        </div>
                    </div>
                    <!-- end Full Name field -->
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
   echo " <h1 class='text-center'>Update members</h1> " ;
   echo "<div class = 'container'>";

    if ($_SERVER['REQUEST_METHOD']  == 'POST'){

        //get variables from the form

        $id     = $_POST['userid'];
        $user   = $_POST['username'];
        $email  = $_POST['email'];
        $name   = $_POST['full'];

        //password trick

        //condition ? true : false ;

        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;


/*
        if(empty($_POST['newpassword'])){

            $pass = $_POST['oldpassword'];

        }else{
            $pass = sha1($_POST['newpassword']);
        }
*/


        //validate the form 
/*
        $formerrors = array();

        if(strlen($user) < 4){
            $formerrors[] = '<div class = "alert alert-danger">Username cant be less than <strong>4 characters</strong></div>';

        }
        if(strlen($user) > 20){
            $formerrors[] = '<div class = "alert alert-danger">Username cant be more than <strong>20 characters</strong></div>';

        }

        if(empty($user)){
            $formerrors[] = '<div class = "alert alert-danger">Username cant be <strong>empty</strong></div>';
        }
        if(empty($name)){
            $formerrors[] = '<div class = "alert alert-danger">Full name cant be <strong>empty</strong></div>';

        }
        if(empty($email)){
            $formerrors[] = '<div class = "alert alert-danger">Email cant be <strong>empty</strong></div>';

        }

        //loop into errors array and echo it

        foreach($formerrors as $error){
            echo $error ;
        }
*/
           
         $formerrors = array();
 
         if(strlen($user) < 4){
             $formerrors[] = 'Username cant be less than <strong>4 characters</strong>';
 
         }
         if(strlen($user) > 20){
             $formerrors[] = 'Username cant be more than <strong>20 characters</strong>';
 
         }
 
         if(empty($user)){
             $formerrors[] = 'Username cant be <strong>empty</strong>';
         }
         if(empty($name)){
             $formerrors[] = 'Full name cant be <strong>empty</strong>';
 
         }
         if(empty($email)){
             $formerrors[] = 'Email cant be <strong>empty</strong>';
 
         }
 
         //loop into errors array and echo it
 
         foreach($formerrors as $error){
             echo '<div class = "alert alert-danger">' . $error . '</div>' ;
         }

        //check if there is no error proceed the update operation

        if(empty($formerrors)){


            $stmt2 = $con->prepare("SELECT * FROM users WHERE username = ? AND userid != ? ");

            $stmt2->execute(array( $user , $id));

            $count =  $stmt2->rowCount();


            if($count == 1){
                
                $theMsg = "<div class='alert alert-danger'>sorry this user is exist</div>";


                redirectHome($theMsg , 'back');

            }else{


                    //Update the database with this info

                    $stmt = $con->prepare("UPDATE users SET username = ? ,email = ? , fullname = ?,password = ? WHERE UserId = ?   ");
                    $stmt->execute(array($user , $email ,$name,$pass, $id ));

                    //echo success message

                    $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;

                    redirectHome($theMsg , 'back');
            
            }
        }






    }else{
        $theMsg = '<div class = "alert alert-danger">sorry you can\'t browse this page directly</div>';
        redirectHome($theMsg );
    }

    echo"</div>";

                //-------------------------Delete--------------------------------------//


} elseif ($do == 'Delete') {  //Delete member page

    echo " <h1 class='text-center'>Delete members</h1> " ;
    echo "<div class = 'container'>";
 

   

            //check if get request userid is numeric & get the integer value of it

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
            //select all data depend on this id

            /*$stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");*/

            $value = $userid;
            $check = checkitem("userid" , "users" , $value) ;

        
            //execute query
        
           // $stmt->execute(array($userid));
        
        
            //the row count
        
            //$count =  $stmt->rowCount();
        
            //if there is such id show the form
        
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM users WHERE userid = :zuser");

                $stmt->bindParam(":zuser", $userid );

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Deleted</div>' ;

                redirectHome($theMsg , 'back');


        
            }else{
                $theMsg = "<div class = 'alert alert-danger'>this id is not exist</div>";

                redirectHome($theMsg);

            }
                
     echo "</div>";


                     //-------------------------Activate--------------------------------------//




        }elseif ($do == "Activate") { //Activate page

            echo " <h1 class='text-center'>Activate members</h1> " ;
            echo "<div class = 'container'>";
        

   

            //check if get request userid is numeric & get the integer value of it

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
            //select all data depend on this id

            /*$stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");*/

            $value = $userid;
            $check = checkitem("userid" , "users" , $value) ;

        
            //execute query
        
           // $stmt->execute(array($userid));
        
        
            //the row count
        
            //$count =  $stmt->rowCount();
        
            //if there is such id show the form
        
            if ($check > 0) {

                $stmt = $con->prepare("UPDATE users SET regstatus = 1 WHERE userid = ?");


                $stmt->execute(array($userid));

                $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;

                redirectHome($theMsg);


        
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