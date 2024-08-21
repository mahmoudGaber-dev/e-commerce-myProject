<?php 


session_start();

$pageTitle = 'Create New Item';


include "init.php";


if (isset($_SESSION["user"])) {




    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $formErrors = array();


        $name       = htmlspecialchars($_POST['name'] , ENT_QUOTES, 'UTF-8') ;
        $desc       = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') ;
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT) ;
        $country    = htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8') ;
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT) ;
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT) ;
        $tags       = htmlspecialchars($_POST['tags'] , ENT_QUOTES, 'UTF-8') ;








        if(strlen($name) < 4 ) {

            $formErrors[] = 'Items Title Must Be At least 4 characters' ;

        }

        if(strlen($desc) < 10 ) {

            $formErrors[] = 'Items Description  Must Be At least 10 characters' ;

        }

        if(strlen($country) < 2 ) {

            $formErrors[] = 'Items Country Must Be At least 2 characters' ;

        }

        if(empty($price)) {

            $formErrors[] = 'Items price Must Be not empty' ;

        }

        if(empty($status)) {

            $formErrors[] = 'Items status Must Be not empty' ;

        }

        if(empty($category)) {

            $formErrors[] = 'Items category Must Be not empty' ;

        }

                //check if there is no error proceed the update operation

                if(empty($formErrors)){



                    
                

                //--------------Insert  user info in database---------------------
                $stmt= $con->prepare("INSERT INTO 
                                            items(Name , Description , Price , 	Country_Made ,Status ,Add_Date ,Cat_ID,Member_ID,tags)
                                    VALUES(:zname , :zdesc , :zprice , :zcountry ,:zstatus , now(), :zcat, :zmember , :ztags    )");

                $stmt->execute(array(

                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   => $status,
                    'zcat'      => $category,
                    'zmember'   => $_SESSION['uid'],
                    'ztags'     => $tags

                ));
                
                //------------echo success message----------------
                if($stmt){
                    $successMsg= 'Item has been Added';
                }


                    
    
            }



    
    }

    
?>
<!------------- heading ----------------------->

<h1 class="text-center"><?php echo $pageTitle  ?></h1>



<!------------ Create New Ad ----------------->
    <div class="create-ad block">

        <div class="container">

            <div class="panel panel-info ">

                <div class="panel-heading"><?php echo $pageTitle  ?></div>
                <div class="panel-body">


                    
                    <div class="row">

                        <!---------------------------------------- form--------------------------------------------- -->
                        <div class="col-md-8">

                                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
                                    <!-- start Name field -->
                            
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input pattern=".{4,}" title="this field require at least 4 characters" type="text" name="name" class="form-control live-name"    placeholder="Name of the Item" data-class=".live-title" required />
                                            </div>
                                        </div>
                                    <!-- end Name field -->

                                    <!-- start Description field -->
                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input  pattern=".{10,}" title="this field require at least 10 characters" type="text" name="description" class="form-control live-desc"    placeholder="Description of the Item" data-class=".live-desc" required />
                                            </div>
                                        </div>
                                    <!-- end Description field -->

                                    <!-- start Price field -->
                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Price</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name="price" class="form-control live-price"  placeholder="Price of the Item" data-class=".live-price" required />
                                            </div>
                                        </div>
                                    <!-- end Price field -->

                                    <!-- start  country_made field -->
                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Country</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name="country" class="form-control"    placeholder="Country of Made" required />
                                            </div>
                                        </div>
                                    <!-- end country_made field -->

                                    <!-- start  Status field -->
                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-10 col-md-9">
                                                <select name="status" required >
                                                    <option value="">...</option>
                                                    <option value="1">New</option>
                                                    <option value="2">Like New</option>
                                                    <option value="3">Used</option>
                                                    <option value="4">Very Old</option>
                                                </select>
                                            </div>
                                        </div>

                                    <!-- end Status field -->





                                    <!-- start  Categories field -->
                        
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Category</label>
                                            <div class="col-sm-10 col-md-9">
                                                <select name="category"  required>
                                                    <option value="">...</option>
                                                    <?php
                                                        $cats = getAllFrom('*','categories' , 'ID' , '' , '');

                                                        foreach ($cats as $cat) {
                                                            echo " <option value='" . $cat['ID'] . "'>"  . $cat['Name'] . "</option>" ;
                                                        }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <!-- end Categories field -->

                                    <!-- start  Tags field -->
                    
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-3 control-label">Tags</label>
                                            <div class="col-sm-10 col-md-9">
                                                <input type="text" name="tags" class="form-control"    placeholder="Separate Tags with comma (,)" />
                                            </div>
                                        </div>

                                    <!-- end Tags field -->



                                    <!-- start submit field -->
                                        <div class="container">
                                            <form class="form-horizontal" >
                                                <div class="form-group form-group-lg">
                                                    <div class="col-sm-offset-2 col-sm-9">
                                                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                                                    </div>
                                        </div>
                                    <!-- end submit field -->
                                </form>


                            </div>
                        </div>
                        <!---------------------------------------- Ads--------------------------------------------- -->

                        <div class="col-md-4">

                                <div class="thumbnail item-box live-preview"> 
                                     <span class="price-tag">
                                        $<span class="live-price">0</span>
                                     </span>
                                    <img class="img-responsive" src="layout/images/img.png" alt="" />
                                    <div class="caption">
                                        <h3 class="live-title">Title</h3>
                                        <p class="live-desc">Description</p>
                                    </div>
                                </div>

                        </div>


                    </div>

                        <!-- start looping through errors -->
                        <?php

                            if (! empty($formErrors)){
                                foreach ($formErrors as $error) {
                                    echo '<div class = "alert alert-danger">' . $error . '</div>'  ;
                            }
                        }

                        if (isset( $successMsg)){

                            echo '<div class="alert alert-success">'. $successMsg .'</div>';
                
                        }


                        ?>

                        <!-- end looping through errors -->








                </div>


            </div>

        
        </div>


    </div>









<?php

    } else {
        header("Location: login.php");

        exit();
    }


    include $tpl . "footer.php";


  ?>
