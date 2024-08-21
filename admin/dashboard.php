<?php 

    //solve the problem of header  sent

    ob_start(/*"ob_gzhandler"*/);  //output buffering start 


    session_start();



    // $nonavbar = '';


    if (isset($_SESSION['username'])) {
       //echo 'welcome' . $_SESSION['username'];
       $pageTitle = 'dashboard';
       include 'init.php';

        /* start dashboard page */

         $numUsers = 6 ;  //number of latest users

        $latestUsers = getlatest("*" , "users" , "userid" , $numUsers ) ; //latest users array

        $numItems = 9 ;  //number of latest Items

        $latestItems = getlatest("*" , "items" , "Item_ID " , $numItems ) ; //latest Items array

        $numComments = 4 ;  //number of latest Comments

        $latestComments = getlatest("*" , "items" , "Item_ID " , $numItems ) ; //latest Items array



        ?>


        <div class="home-stats">
            <div class="container  text-center">

                <h1>Dashboard</h1>
                <div class="row">



                        <a href="members.php">
                        <div class="col-md-3">
                                <div class="stat st-members">
                                    <i class="fa fa-users"></i>
                                    <div class="info">
                                    Total Members
                                    <span><?php echo countitems('userid' , 'users') ?>  </span>
                                </div>
                               
                            </div>
                            
                    </div>
                    </a>


                    
                    <a href="members.php?do=Manage&page=pending">
                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class="fa fa-user-plus"></i>
                            <div class="info">
                                Pending Members
                                <span>
                                    <?php echo checkitem("regstatus" , "users" , 0)  ?>
                                </span>

                            </div>
                        </div>
                        
                    </div>
                    </a>


                    <a href="items.php">
                    <div class="col-md-3">
                        <div class="stat st-items">
                                <i class="fa fa-tag"></i>
                                <div class="info">
                                        Total Items
                                        <span><?php echo countitems('Item_ID ' , 'items') ?></span>
                                </div>
                        </div>
                    </div>
                    </a>



                    <a href="comments.php">
                    <div class="col-md-3">
                        <div class="stat st-coments">

                            <i class="fa fa-comments"></i>
                            <div class="info">
                                Total Coments
                                <span><?php echo countitems('c_id  ' , 'comments') ?></span>
                            </div>
                        </div>
                    </div>
                    </a>


                    
                </div>

            </div>
        </div>

        <div class="latest">
            <div class="container ">
                <div class="row">

                         <!-------------- start latest users ------------------>

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i>Latest <?php echo $numUsers ?>   Registerd users
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php

                                    if (!empty(($latestUsers))) {
                                    foreach ($latestUsers as $user) {
                            
                                        echo '<li>' ;
                                            echo $user['username'] ;
                                            echo '<a href = "members.php?do=Edit&userid=' . $user['userid'] . '  ">' ;
                                            echo '<span class= "btn btn-success pull-right">' ;
                                                echo ' <i class="fa fa-edit"></i> Edit';
                                                if ($user['regstatus'] == 0) {
                                                    echo "<a href='members.php?do=Activate&userid=". $user['userid'] . " ' class='btn btn-info pull-right activate '><i class='fa fa-check'></i> Activate </a>";
                      
                                                  }
                      
                                            echo '</span> ';
                                            echo '</a>' ;
                                        echo '</li>';
                                                }
                            
                                    }else{
                                        echo 'there\'s no members to show';
                                    }           
                      
                                ?>
                                </ul>
                                
                            </div>

                        </div>
                    </div>
                <!-------------- start latest items ------------------>

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i>Latest <?php echo $numItems ?> Items
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>

                            </div>
                            <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php

                                if (!empty($latestItems)) {

                                        foreach ($latestItems as $item) {
                                
                                            echo '<li>' ;
                                                echo $item['Name'] ;
                                                echo '<a href = "items.php?do=Edit&itemid=' . $item['Item_ID'] . '  ">' ;
                                                echo '<span class= "btn btn-success pull-right">' ;
                                                    echo ' <i class="fa fa-edit"></i> Edit';
                                                    if ($item['Approve'] == 0) {
                                                        echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] . " ' class='btn btn-info pull-right activate '><i class='fa fa-check'></i> Approve </a>";
                        
                                                    }
                        
                                                echo '</span> ';
                                                echo '</a>' ;
                                            echo '</li>';
                                        }
                                    }else{
                                        echo 'there\'s no items to show';
                                    }                        
                                ?>
                                </ul>
                                

                            </div>

                        </div>
                    </div>
                    </div>



                <!-------------- start latest comment ------------------>
                <div class="row">

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i>Latest <?php echo $numComments ?> Comments
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                                <div class="panel-body">


                                    <?php 
                                            //select all users expcept admin

                                            $stmt = $con->prepare("SELECT comments.*, users.username AS Member FROM comments 
                                            INNER JOIN  users ON users.userid = comments.user_id ORDER BY c_id DESC LIMIT $numComments");



                                            $stmt->execute();

                                            $comments = $stmt->fetchAll();


                                    if(!empty($comments)) {


                                        foreach ($comments as $comment) {

                                            echo '<div class="comment-box">';
                                                echo  '<span class="member-n"><a href="members.php?do=Edit&userid=' .$comment['user_id'].'">'  . $comment['Member'] . '</a></span>' ;
                                                echo  '<p class="comment-c">'  . $comment['comment'] . '</p>' ;
                                            echo '</div>';
                                        }
                                    }else{
                                        echo 'there\'s no comments to show';
                                    }
                                    ?>

            
                                    
                                </div>

                        </div>
                    </div>
                </div>



        
                </div>
                <!------------------- end latest comment ------------------------->

            </div>
            
        </div>

        <?php

        /* end dashboard page */


       include $tpl . "footer.php";
        
    }else{
        //echo 'you are noy authorized to view this page' ;

        header('Location: index.php');
        
        exit();
    }

    ob_end_flush();

    ?>