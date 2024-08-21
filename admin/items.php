<?php

/*
========================
== Items page
======================== 
*/

ob_start(); //output buffering start 

session_start();

$pageTitle = 'Items';



if (isset($_SESSION['username'])) {
   //echo 'welcome' . $_SESSION['username'];
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

   //----------------------Manage-------------------------//

   if ($do == 'Manage'){







   $stmt = $con->prepare("  SELECT items.* , categories.Name AS category_name , users.username AS member_name  FROM items

                            INNER JOIN categories ON categories.ID = items.Cat_ID

                            INNER JOIN users ON users.userid = items.Member_ID ORDER BY Item_ID DESC ");

    //execute the statement


   $stmt->execute();


   //assign to variable

   $items = $stmt->fetchAll();

   if(!empty($items)){
   
   ?>

    <h1 class="text-center">Manage Items</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control</td>
                </tr>

                <?php 

                     foreach ($items as $item) {

                        echo "<tr>" ;
                        echo "<td>" . $item['Item_ID'] . "</td>" ;
                        echo "<td>" . $item['Name'] . "</td>" ;
                        echo "<td>" . $item['Description'] . "</td>" ;
                        echo "<td>" . $item['Price'] . "</td>" ;
                        echo "<td>" . $item['Add_Date'] . "</td>" ;
                        echo "<td>" . $item['category_name'] . "</td>" ;
                        echo "<td>" . $item['member_name'] . "</td>" ;
                        echo "<td>
                            <a href='items.php?do=Edit&itemid=". $item['Item_ID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='items.php?do=Delete&itemid=". $item['Item_ID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                            if ($item['Approve'] == 0) {

                                echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] . " ' class='btn btn-info activate '><i class='fa fa-check'></i> Approve </a>";
  
                              }
  


                         echo " </td>" ;
                        echo "</tr>" ;

                     }
                ?>




                <tr>
            </table>
        </div>

    <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>  New Item </a>

    </div>
    <?php }else{
        echo '<div class="container">';
        echo '<div class="nice-message">There\'s no items to Show</div>';
        echo    '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>  New Item </a>';
        echo '</div>';

    }         

    ?>




   <?php


            //-------------------------Add--------------------------------------//



    } elseif ($do == 'Add'){?>

        <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" >
                        <!-- start Name field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required="required"   placeholder="Name of the Item" />
                        </div>
                    </div>
                    <!-- end Name field -->

                        <!-- start Description field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required="required"   placeholder="Description of the Item" />
                        </div>
                    </div>
                    <!-- end Description field -->

                        <!-- start Price field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" class="form-control" required="required"   placeholder="Price of the Item" />
                        </div>
                    </div>
                    <!-- end Price field -->

                        <!-- start  country_made field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" class="form-control" required="required"   placeholder="Country of Made" />
                        </div>
                    </div>
                    <!-- end country_made field -->

                        <!-- start  Status field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status" >
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- end Status field -->



                        <!-- start  Members field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="member" >
                                <option value="0">...</option>
                                <?php
                                    $allmembers = getAllFrom("*" , "users" , "userid" , "" , ""   );


                                    foreach ($allmembers as $user) {
                                        echo " <option value='" . $user['userid'] . "'>"  . $user['username'] . "</option>" ;
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end Members field -->


                        <!-- start  Categories field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="category" >
                                <option value="0">...</option>
                                <?php
                                    $allcats = getAllFrom("*" , "categories" , "ID" , "WHERE parent = 0" , ""   );

                                    foreach ($allcats as $cat) {
                                        echo " <option value='" . $cat['ID'] . "'>"  . $cat['Name'] . "</option>" ;
                                        $childcats = getAllFrom("*" , "categories" , "ID" , "WHERE parent = {$cat['ID']}" , ""   );
                                        foreach ($childcats as $child) {
                                            echo " <option value='" . $child['ID'] . "'>--- "  . $child['Name'] . "</option>" ;
                                        }

                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end Categories field -->


                    <!-- start  Tags field -->
        
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="tags" class="form-control"    placeholder="Separate Tags with comma (,)" />
                        </div>
                    </div>
                    <!-- end Tags field -->



                    <!-- start submit field -->
                    <div class="container">
                        <form class="form-horizontal" >
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                                </div>
                    </div>
                    <!-- end submit field -->
                </form>
            </div>
        
            <?php
        
        
            //-------------------------insert--------------------------------------//


    }elseif ($do == 'Insert' ) {

    
     
         if ($_SERVER['REQUEST_METHOD']  == 'POST'){
    
            echo " <h1 class='text-center'>Insert Item</h1> " ;
            echo "<div class = 'container'>";
        
     
             //get variables from the form
     
             $name      = $_POST['name'];
             $desc      = $_POST['description'];
             $price     = $_POST['price'];
             $country   = $_POST['country'];
             $status    = $_POST['status'];

             $member    = $_POST['member'];
             $cat       = $_POST['category'];
             $tags       = $_POST['tags'];
     
    
    
     
     
             //validate the form 
     
             $formerrors = array();
     
             if(empty($name)){
                 $formerrors[] = 'Name cant be <strong>empty</strong>';
     
             }
             if(empty($desc)){
                 $formerrors[] = 'description cant be <strong>empty</strong>';
     
             }
     
             if(empty($price)){
                 $formerrors[] = 'price cant be <strong>empty</strong>';
             }
             if(empty($country)){
                 $formerrors[] = 'country cant be <strong>empty</strong>';
             }
             if($status == 0){
                 $formerrors[] = 'you  must choose the <strong>status</strong>';
     
             }

             if($member == 0){
                 $formerrors[] = 'you  must choose the <strong>member</strong>';
     
             }

             if($cat == 0){
                 $formerrors[] = 'you  must choose the <strong>category</strong>';
     
             }



             //loop into errors array and echo it
     
             foreach($formerrors as $error){
                 echo '<div class = "alert alert-danger">' . $error . '</div>' ;
             }
     
             //check if there is no error proceed the update operation
     
             if(empty($formerrors)){
    

    
                            
                        

                    //Insert  user info in database
                    $stmt= $con->prepare("INSERT INTO 
                                                items(Name , Description , Price , 	Country_Made ,Status ,Add_Date ,Cat_ID,Member_ID,tags)
                                        VALUES(:zname , :zdesc , :zprice , :zcountry ,:zstatus , now(), :zcat, :zmember , :ztags    )");
    
                    $stmt->execute(array(

                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zstatus'   => $status,
                        'zcat'      => $cat,
                        'zmember'   => $member,
                        'ztags'     => $tags

                    ));
                    
                    //echo success message

                    $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Inserted</div>' ;

                    redirectHome($theMsg , 'back' );
     
                        
     
             }
     
     
     
     
         }else{
    
            echo"<div class = 'container'>";
    
             $theMsg = '<div class= "alert alert-danger"> sorry you can\'t browse this page directly</div>';
    
             redirectHome($theMsg);
             echo "</div>";
         }
     
         echo"</div>";

     
                //-------------------------Edit--------------------------------------//


    } elseif ($do == 'Edit'){


    //check if get request itemid is numeric & get the integer value of it

   $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0 ;
   //select all data depend on this id
    $stmt = $con->prepare("SELECT  * from  items where Item_ID = ? ");

    //execute query

    $stmt->execute(array($itemid));

    //fetch the data

    $item = $stmt->fetch();

    //the row count

    $count =  $stmt->rowCount();

    //if there is such id show the form

    if ($count > 0) {?>
        

            <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST" >
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
                            <!-- start Name field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="name" class="form-control" required="required"   placeholder="Name of the Item" value="<?php echo $item['Name']   ?>" />
                            </div>
                        </div>
                        <!-- end Name field -->

                            <!-- start Description field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="description" class="form-control" required="required"   placeholder="Description of the Item" value="<?php echo $item['Description']   ?>"  />
                            </div>
                        </div>
                        <!-- end Description field -->

                            <!-- start Price field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="price" class="form-control" required="required"   placeholder="Price of the Item" value="<?php echo $item['Price']   ?>" />
                            </div>
                        </div>
                        <!-- end Price field -->

                            <!-- start  country_made field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="country" class="form-control" required="required"   placeholder="Country of Made" value="<?php echo $item['Country_Made']   ?>"/>
                            </div>
                        </div>
                        <!-- end country_made field -->

                            <!-- start  Status field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="status" >
                                    <option value="1" <?php if ($item['Status'] == 1){ echo 'selected' ;}  ?>>New</option>
                                    <option value="2" <?php if ($item['Status'] == 2){ echo 'selected' ;}  ?>>Like New</option>
                                    <option value="3" <?php if ($item['Status'] == 3){ echo 'selected' ;}  ?>>Used</option>
                                    <option value="4" <?php if ($item['Status'] == 4){ echo 'selected' ;}  ?>>Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- end Status field -->



                            <!-- start  Members field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Member</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="member" >
                                    <?php

                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();

                                        foreach ($users as $user) {
                                            echo " <option value='" . $user['userid'] . "'    ";
                                            if ($item['Member_ID'] == $user['userid'] ){ echo 'selected' ;}
                                             echo  " >"  . $user['username'] . "</option>" ;
                                        }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- end Members field -->


                            <!-- start  Categories field -->
            
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="category" >
                                    <?php

                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();

                                        foreach ($cats as $cat) {
                                            echo " <option value='" . $cat['ID'] . "'";
                                            if ($item['Cat_ID'] == $cat['ID'] ){ echo 'selected' ;}
                                            echo ">"  . $cat['Name'] . "</option>" ;
                                        }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- end Categories field -->

                        <!-- start  Tags field -->
        
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-10 col-md-4">
                                    <input type="text" name="tags" class="form-control"    placeholder="Separate Tags with comma (,)" value="<?php echo $item['tags']   ?>" />
                                </div>
                            </div>
                        <!-- end Tags field -->


                        <!-- start submit field -->
                        <div class="container">
                            <form class="form-horizontal" >
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
                                    </div>
                        </div>
                        <!-- end submit field -->
                    </form>
                      <?php  
                                //----------select all users expcept admin---------------------

                                $stmt = $con->prepare("SELECT comments.*, users.username AS Member FROM comments 
                                                    INNER JOIN  users ON users.userid = comments.user_id 
                                                    WHERE item_id = ? ");

                                //execute the statement


                                $stmt->execute(array($itemid));


                                //assign to variable

                                $rows = $stmt->fetchAll();


                                if (!empty($rows)) {

                               

                                ?>

                                <h1 class="text-center">Manage [<?php echo $item['Name']   ?>] comments</h1>
                                <div class="table-responsive">
                                    <table class="main-table text-center table table-bordered">
                                        <tr>
                                            <td>Comment</td>
                                            <td>User NAME</td>
                                            <td>Added Date</td>
                                            <td>Control</td>
                                        </tr>

                                        <?php 

                                            foreach ($rows as $row) {

                                                echo "<tr>" ;
                                                echo "<td>" . $row['comment'] . "</td>" ;
                                                echo "<td>" . $row['Member'] . "</td>" ;
                                                echo "<td>" . $row['comment_date'] . "</td>" ;
                                                echo "<td>
                                                    <a href='comments.php?do=Edit&comid=". $row['c_id'] . " ' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                                    <a href='comments.php?do=Delete&comid=". $row['c_id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                                    
                                                    if ($row['status'] == 0) {
                                                    echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] . " ' class='btn btn-info activate '><i class='fa fa-close'></i> Approve </a>";

                                                    }

                                                echo " </td>" ;
                                                echo "</tr>" ;

                                            }
                                        ?>




                                        <tr>
                                    </table>
                                </div>
                                <?php  }  ?>

                </div>


   <?php 



        //if there is no such id show error message
    }else{
        echo '<div class = container>';

        $theMsg = '<div class = "alert alert-danger" >there\'s no such id</div>' ;

        redirectHome($theMsg );

        echo '</div>';
    }


            //-------------------------update--------------------------------------//


    } elseif ($do == 'Update'){

        echo " <h1 class='text-center'>Update Items</h1> " ;
        echo "<div class = 'container'>";
     
         if ($_SERVER['REQUEST_METHOD']  == 'POST'){
     
             //get variables from the form
     
             $id        = $_POST['itemid']; //from hidden input
             $name      = $_POST['name'];
             $desc      = $_POST['description'];
             $price     = $_POST['price'];
             $country   = $_POST['country'];
             $status    = $_POST['status'];
             $cat       = $_POST['category'];
             $member    = $_POST['member'];
             $tags      = $_POST['tags'];
     
     
     
     
             //validate the form 
                
             $formerrors = array();
     
             if(empty($name)){
                 $formerrors[] = 'Name cant be <strong>empty</strong>';
     
             }
             if(empty($desc)){
                 $formerrors[] = 'description cant be <strong>empty</strong>';
     
             }
     
             if(empty($price)){
                 $formerrors[] = 'price cant be <strong>empty</strong>';
             }
             if(empty($country)){
                 $formerrors[] = 'country cant be <strong>empty</strong>';
             }
             if($status == 0){
                 $formerrors[] = 'you  must choose the <strong>status</strong>';
     
             }

             if($member == 0){
                 $formerrors[] = 'you  must choose the <strong>member</strong>';
     
             }

             if($cat == 0){
                 $formerrors[] = 'you  must choose the <strong>category</strong>';
     
             }



             //loop into errors array and echo it
     
             foreach($formerrors as $error){
                 echo '<div class = "alert alert-danger">' . $error . '</div>' ;
             }     
             //check if there is no error proceed the update operation
     
             if(empty($formerrors)){
     
                         //Update the database with this info
     
                         $stmt = $con->prepare("UPDATE
                                                     items 
                                                SET 
                                                    Name = ? ,
                                                    Description = ? ,
                                                    Price = ?,
                                                    Country_Made = ?,
                                                     Status = ?,
                                                      Cat_ID = ? ,
                                                      Member_ID = ?,
                                                      tags = ?
                                                WHERE 	
                                                    Item_ID = ?   ");
                         $stmt->execute(array($name , $desc ,$price,$country, $status,$cat,$member,$tags, $id ));
     
                         //echo success message
     
                         $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;
     
                         redirectHome($theMsg , 'back');
     
             }
     
     
     
     
         }else{
             $theMsg = '<div class = "alert alert-danger">sorry you can\'t browse this page directly</div>';
             redirectHome($theMsg );
         }
     
         echo"</div>";
     
     

            //-------------------------delete--------------------------------------//


        } elseif ($do == 'Delete') {  

            echo " <h1 class='text-center'>Delete Item</h1> " ;
            echo "<div class = 'container'>";
         
        
           
        
                    //check if get request itemid is numeric & get the integer value of it
        
                    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0 ;
                    //select all data depend on this id
        
                    /*$stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");*/
        
                    $value = $itemid;
                    $check = checkitem("Item_ID" , "items" , $value) ;
        
                
                
                    //if there is such id show the form
                
                    if ($check > 0) {
        
                        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
        
                        $stmt->bindParam(":zid", $itemid );
        
                        $stmt->execute();
        
                        $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Deleted</div>' ;
        
                        redirectHome($theMsg , 'back');
        
        
                
                    }else{
                        $theMsg = "<div class = 'alert alert-danger'>this id is not exist</div>";
        
                        redirectHome($theMsg);
        
                    }
                        
             echo "</div>";
        

            //-------------------------approve--------------------------------------//


    } elseif ($do == 'Approve'){

        echo " <h1 class='text-center'>Approve Item</h1> " ;
        echo "<div class = 'container'>";
     
    
       
    
                //check if get request itemid is numeric & get the integer value of it
    
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0 ;
                //select all data depend on this id
    
                /*$stmt = $con->prepare("SELECT  * from  users where UserId = ? LIMIT    1");*/
    
                $value = $itemid;
                $check = checkitem("Item_ID" , "items" , $value) ;
    
            
                //if there is such id show the form
            
                if ($check > 0) {
    
                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
    
    
                    $stmt->execute(array($itemid));
    
                    $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;
    
                    redirectHome($theMsg , 'back');
    
    
            
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

ob_end_flush(); //release the output

?>