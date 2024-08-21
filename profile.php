<?php 

    ob_start();
    session_start();

    $pageTitle = 'Profile';


    include "init.php";


    if (isset($_SESSION["user"])) {


        $getUser = $con->prepare("SELECT * FROM users WHERE username = ? ");

        $getUser->execute(array($sessionUser));

        $info = $getUser->fetch();

        $userid = $info['userid'] ;


    
    ?>
<!------------- heading ----------------------->

<h1 class="text-center">My Profile</h1>



<!------------ my information ----------------->
    <div class="information block">

        <div class="container">

            <div class="panel panel-info ">

                <div class="panel-heading">My Information</div>
                <div class="panel-body">
                    <ul clas="list-unstyled">


                        <li>
                            <i class="fa fa-unlock-alt fa-fw" ></i>
                            <span>Login Name</span> : <?php echo $info['username']  ?> 
                        </li>


                        <li>
                            <i class="fa fa-envelope-o fa-fw" ></i>
                            <span>Email</span> : <?php echo $info['email']  ?> 
                        </li>


                        <li>
                            <i class="fa fa-user fa-fw" ></i>
                            <span>Full Name</span> : <?php echo $info['fullname']  ?> 
                        </li>


                        <li>
                        <i class="fa fa-calendar fa-fw" ></i>
                            <span>Register Date</span> : <?php echo $info['Date']  ?> 
                        </li>


                        <li>
                        <i class="fa fa-tags fa-fw"></i>
                            <span>Fav Category</span> : 
                        </li>  
                        
                    </ul>

                    <a href="#" class="btn btn-default">Edit Information</a>

                </div>


            </div>

        
        </div>


    </div>

<!------------ my ads ----------------->
    <div id="my-ads" class="my-ads block">

        <div class="container">

            <div class="panel panel-info ">

                <div class="panel-heading">My Items</div>
                <div class="panel-body">

                    <?php

                    $myItems = getAllFrom( "*" , "items" , "Item_ID" , "WHERE Member_ID = $userid" /*, "AND approve = 1" */  );

                    //if(!empty(getItems('Member_ID' , $info['userid']))) {
                    if(!empty($myItems)) {

                        echo "<div class='row'>" ;

                            foreach($myItems as $item){

                                echo '<div class="col-sm-6 col-md-3">'; 
                                    echo  '<div class="thumbnail item-box">';

                                    if($item['Approve'] == 0){
                                         echo '<span class="approve-status">Waiting approval</span>' ;
                                        }
                                    
                                    echo '<span class="price-tag">$'.  $item['Price']  .'</span>';
                                        echo '<img class="img-responsive" src="layout/images/img.png" alt="" />';
                                        echo '<div class="caption">';
                                            echo '<h3><a href="items.php?itemid='. $item["Item_ID"] .'">' . $item['Name']  . '</a></h3>';
                                            echo '<p>' . $item['Description'] . '</p>';
                                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                     echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        

                        }
                    }else{

                        echo'sorry there\'s no Ads to show , Creat <a href="newad.php">New AD</a>';

                    }

                    ?>
                    

                    </div>


            </div>

        
        </div>


    </div>

<!------------ Latest Comments ----------------->
    <div class="my-comments block">

        <div class="container">

            <div class="panel panel-info ">

                <div class="panel-heading">Latest Comments</div>
                <div class="panel-body">
                <?php


                        $myComments = getAllFrom("comment" , "comments" , "c_id" , "WHERE user_id = $userid" , ""   );

                        /*
                        //----------select all users expcept admin---------------------
                        
                        $stmt = $con->prepare("SELECT comment FROM comments  WHERE user_id = ? ");

                        //execute the statement


                        $stmt->execute(array($info['userid']));


                        //assign to variable

                        $comments = $stmt->fetchAll();
                        */


                        //if(!empty($comments)){
                        if(!empty($myComments)){

                            foreach($myComments as $comment){
                                echo '<p>'. $comment['comment'] .'</p>';
                            }

                        }else{
                            echo'there\'s no comments to show';
                        }



                ?>
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


    ob_end_flush();
  ?>
