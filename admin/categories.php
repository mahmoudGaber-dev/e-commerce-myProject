<?php

/*
========================
== Category page
======================== 
*/

ob_start(); //output buffering start 

session_start();

$pageTitle = 'Categories';



if (isset($_SESSION['username'])) {
   //echo 'welcome' . $_SESSION['username'];
   include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

            //-------------------------Manage--------------------------------------//


   if ($do == 'Manage'){

    $sort = 'asc';

    $sort_array  = array('asc','desc');

    if (isset($_GET['sort']) && in_array($_GET['sort'] ,  $sort_array )){

        $sort = $_GET['sort'];

    }

    $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering  $sort ");

    $stmt2->execute();

    $cats = $stmt2->fetchAll();
    
    if(!empty($cats)){
    
    ?>

    <h1 class="text-center">Manage Categories</h1>

    <div  class="container categories">

    <div class="panel panel-default">

        <div class="panel-heading">
        <i class = 'fa fa-edit'></i> Manage Categories
                <div class="option pull-right">
                <i class = 'fa fa-sort'></i> Ordering:[
                    <a class="<?php if ($sort == 'asc') { echo 'active';}  ?>" href="?sort=asc">Asc</a> |
                    <a class="<?php if ($sort == 'desc') { echo 'active';}  ?>" href="?sort=desc">Desc</a> ]
                    <i class = 'fa fa-eye'></i> view: [
                    <span class="active" data-view="full">Full</span> |
                    <span data-view="classic">Classic</span> ]
                </div>
            </div>
            <div class="panel-body">
                <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>" ;
                                echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class = 'fa fa-edit'></i>Edit</a>";
                                echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "'  class='confirm btn btn-xs btn-danger'><i class = 'fa fa-close'></i>Delete</a>";
                            echo "</div>";
                            echo "<h3>"   . $cat['Name'] . '</h3>' ;
                            echo '<div class="full-view">';
                                echo "<p>" ; if($cat['Description'] == '' ){echo 'this category has no description' ;}else{echo $cat['Description'] ;} echo '</p>' ;
                                if($cat['visibility'] == 1 ){echo ' <span class="visibility"><i class="fa fa-eye"></i>Hidden</span>' ;} 
                                if($cat['Allow_Comment'] == 1 ){echo ' <span class="commenting"><i class="fa fa-close"></i>Comment Disabled</span>' ;} 
                                if($cat['Allow_Ads'] == 1 ){echo ' <span class="advertises"><i class="fa fa-close"></i>ads Disabled</span>' ;} 

                                //get child categories

                                $childCats = getAllFrom("*" , "categories" , "ID" , "WHERE parent = {$cat['ID']} " , ""  , "ASC" ) ;
                                if (! empty($childCats)){
                                echo "<h4 class='child-head'>child categories </h4>";
                                echo "<ul class='list-unstyled child-cats'>";


                                //$categories = getCat();
            
                                foreach ($childCats as $c) {
            
                                echo  "<li class='child-link'>
                                <a href='categories.php?do=Edit&catid=" . $c['ID'] . "' >" .$c['Name'] . "</a>
                                <a href='categories.php?do=Delete&catid=" . $c['ID'] . "'  class='show-delete confirm'>Delete</a>
                                </li>";
                        
                                }   
                                echo '</ul>';
                                }
                            echo '</div>';



                        echo "</div>";
                        


                    echo"<hr>";
                    }
                
                ?>
            </div>


        </div>

        <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category </a>


    </div>

    <?php }else{
        echo '<div class="container">';
        echo '<div class="nice-message">There\'s no categories to Show</div>';
        echo    '<a href="categories.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>  New category </a>';
        echo '</div>';

    }         

            //-------------------------Add--------------------------------------//


    } elseif ($do == 'Add'){?>

<h1 class="text-center">Add New Category</h1>
    <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST" >
                <!-- start Name field -->

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10 col-md-4">
                    <input type="text" name="name" class="form-control" required="required"  autocomplete="off"  placeholder="Name of the Category" />
                </div>
            </div>
            <!-- end Name field -->



            <!-- start Description field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-4">
                    <input type="text" name="description"  class="form-control"   placeholder="Descripe the Category"   />
                </div>
            </div>
            <!-- end Description field -->




            <!-- start Ordering field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10 col-md-4">
                    <input type="text" name="ordering"   class="form-control "  placeholder="Number To Arrange The Categories" />
                </div>
            </div>
            <!-- end Ordering field -->




            <!----- start category type ----->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Parent?</label>
                <div class="col-sm-10 col-md-4">
                    <select name="parent" >
                        <option value="0">None</option>
                        <?php 
                            $allCats = getAllFrom("*" , "categories" , "ID" , "WHERE parent = 0" , "" , "ASC" );
                            foreach ($allCats as $cat) {
                                echo "<option value=". $cat['ID']  .">" . $cat['Name'] ."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>


            <!----- end category type ----->



            <!-- start Visibility field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Visible</label>
                <div class="col-sm-10 col-md-4">
                    <div>
                        <input id="vis-yes" type="radio" name="visibility" value="0"  checked >
                        <label for="vis-yes">Yes</label>
                    </div>
                    <div>
                        <input id="vis-no" type="radio" name="visibility" value="1"   />
                        <label for="vis-no">No</label>
                    </div>
                </div>
            </div>
            <!-- end Visibility field -->



            <!-- start Commenting field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Allow Commenting</label>
                <div class="col-sm-10 col-md-4">
                    <div>
                        <input id="com-yes" type="radio" name="commenting" value="0"  checked >
                        <label for="com-yes">Yes</label>
                    </div>
                    <div>
                        <input id="com-no" type="radio" name="commenting" value="1"   />
                        <label for="com-no">No</label>
                    </div>
                </div>
            </div>
            <!-- end Commenting field -->



            <!-- start Ads field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0"  checked >
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"   />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
            <!-- end Ads field -->



            <!-- start submit field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                        </div>
            </div>
            <!-- end submit field -->

        </form>
    </div>

    <?php

        //-------------------------Insert--------------------------------------//

    } elseif ($do == 'Insert'){

            //Insert Category page

 
     if ($_SERVER['REQUEST_METHOD']  == 'POST'){

        echo " <h1 class='text-center'>Insert Category</h1> " ;
        echo "<div class = 'container'>";
    
 
         //get variables from the form
 
         $name      = $_POST['name'];
         $desc      = $_POST['description'];
         $parent    = $_POST['parent'];
         $order     = $_POST['ordering'];
         $visible   = $_POST['visibility'];
         $comment   = $_POST['commenting'];
         $ads       = $_POST['ads'];
  

 


                      //check if category exist in database

                      $value = $name;

                      $check = checkitem("Name" , "categories" , $value) ;
                  
                      if ($check == 1) {
                          $theMsg = "<div class = 'alert alert-danger'>sorry this Category is exist</div>";
                          redirectHome($theMsg , 'back');
                      }else{

                        
                    
    
                        //Insert  category info in database
                        $stmt= $con->prepare("INSERT INTO 
                                                    categories(Name , 	Description , parent , Ordering , visibility ,Allow_Comment	, 	Allow_Ads )
                                            VALUES(:zname , :zdesc , :zparent , :zorder , :zvisible , :zcomment , :zads) ");
        
                        $stmt->execute(array(

                            'zname' => $name,
                            'zdesc'=> $desc,
                            'zparent'=> $parent,
                            'zorder'=> $order,
                            'zvisible'=> $visible,
                            'zcomment'=> $comment,
                            'zads'=> $ads

                        ));
                        
                        //echo success message
    
                        $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Inserted</div>' ;

                        redirectHome($theMsg , 'back' );
 
                    }
 
   
 
 
 
 
     }else{

        echo"<div class = 'container'>";

         $theMsg = '<div class= "alert alert-danger"> sorry you can\'t browse this page directly</div>';

         redirectHome($theMsg , 'back' );
         echo "</div>";
     }
 
     echo"</div>";
 



            //-------------------------Edit--------------------------------------//



    }elseif  ($do == 'Edit'){ //edit page 

        //check if get request catid is numeric & get the integer value of it
    
       $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0 ;
       //select all data depend on this id
        $stmt = $con->prepare("SELECT  * from  categories where ID = ? ");
    
        //execute query
    
        $stmt->execute(array($catid));
    
        //fetch the data
    
        $cat = $stmt->fetch();
    
        //the row count
    
        $count =  $stmt->rowCount();
    
        //if there is such id show the form
    
        if ($count > 0) {?>


            <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST" >
                    <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                <!-- start Name field -->

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="name" class="form-control" required="required"    placeholder="Name of the Category" value="<?php echo $cat['Name']    ?>" />
                            </div>
                        </div>
                <!-- end Name field -->

                <!-- start Description field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="description"  class="form-control"   placeholder="Descripe the Category"  value="<?php echo $cat['Description']    ?>"   />
                            </div>
                        </div>
                <!-- end Description field -->

                <!-- start Ordering field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="ordering"   class="form-control "  placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering']    ?>" />
                            </div>
                        </div>
                <!-- end Ordering field -->

                <!----- start category type ----->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent" >
                                <option value="0">None</option>
                                <?php 
                                    $allCats = getAllFrom("*" , "categories" , "ID" , "WHERE parent = 0" , "" , "ASC" );
                                    foreach ($allCats as $c) {
                                        echo "<option value='". $c['ID']  ."'";
                                        if($cat['parent'] ==  $c['ID']) {echo'selected';}
                                        echo ">" . $c['Name'] ."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>


                <!----- end category type ----->



                <!-- start Visibility field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['visibility'] == 0){echo 'checked' ;}  ?>   />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['visibility'] == 1){echo 'checked' ;}  ?>   />
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                <!-- end Visibility field -->

                <!-- start Commenting field -->
                <div class="container">
                    <form class="form-horizontal" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked' ;}  ?>   >
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked' ;}  ?>    />
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end Commenting field -->

                        <!-- start Ads field -->
                        <div class="container">
                            <form class="form-horizontal" >
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Allow Ads</label>
                                    <div class="col-sm-10 col-md-4">
                                        <div>
                                            <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked' ;}  ?>    >
                                            <label for="ads-yes">Yes</label>
                                        </div>
                                        <div>
                                            <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked' ;}  ?>   />
                                            <label for="ads-no">No</label>
                                        </div>
                                    </div>
                                </div>
                        <!-- end Ads field -->

                        <!-- start submit field -->
                        <div class="container">
                            <form class="form-horizontal" >
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="save" class="btn btn-primary btn-lg">
                                    </div>
                        </div>
                        <!-- end submit field -->
                    </form>
                </div>


            
    
       <?php 
    
            //if there is no such id show error message
        }else{
            echo '<div class = container>';
    
            $theMsg = '<div class = "alert alert-danger" >there\'s no such ID</div>' ;
    
            redirectHome($theMsg );
    
            echo '</div>';
        }
    


            //-------------------------Update--------------------------------------//


    } elseif ($do == 'Update'){

        echo " <h1 class='text-center'>Update Categories</h1> " ;
        echo "<div class = 'container'>";
     
         if ($_SERVER['REQUEST_METHOD']  == 'POST'){
     
             //get variables from the form
     
             $id        = $_POST['catid'];
             $name      = $_POST['name'];
             $desc      = $_POST['description'];
             $order     = $_POST['ordering'];
             $parent    = $_POST['parent'];

             $visible   = $_POST['visibility'];
             $comment     = $_POST['commenting'];
             $ads     = $_POST['ads'];

     
     
     

            //Update the database with this info

            $stmt = $con->prepare("UPDATE
                                         categories 
                                    SET 
                                        Name = ? ,
                                        Description = ? ,
                                        Ordering = ?,
                                        parent = ?,
                                        visibility = ? ,
                                        Allow_Comment = ? ,
                                        Allow_Ads = ? 
                                    WHERE 
                                        ID = ?   ");
            $stmt->execute(array($name , $desc ,$order,$parent,$visible,$comment,$ads, $id ));

            //echo success message

            $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Updated</div>' ;

            redirectHome($theMsg , 'back');

     
     
     
     
         }else{
             $theMsg = '<div class = "alert alert-danger">sorry you can\'t browse this page directly</div>';
             redirectHome($theMsg );
         }
     
         echo"</div>";
     


            //-------------------------Delete--------------------------------------//



    } elseif ($do == 'Delete') {  //Delete member page

        echo " <h1 class='text-center'>Delete Category</h1> " ;
        echo "<div class = 'container'>";
     
    
       
    
                //check if get request catid is numeric & get the integer value of it
    
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0 ;
                //select all data depend on this id
    
    
                $check = checkitem("ID" , "categories" , $catid) ;
    
            
                //execute query
            
               // $stmt->execute(array($userid));
            
            
                //the row count
            
                //$count =  $stmt->rowCount();
            
                //if there is such id show the form
            
                if ($check > 0) {
    
                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
    
                    $stmt->bindParam(":zid", $catid );
    
                    $stmt->execute();
    
                    $theMsg = "<div class='alert alert-success'>"  . $stmt->rowCount() . 'record Deleted</div>' ;
    
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

ob_end_flush(); //release the output

?>