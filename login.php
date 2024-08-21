<?php

    ob_start();
    session_start();

    $pageTitle = 'login';


    if (isset($_SESSION['user'])) {
        header('Location: index.php'); //redirect to dashboard page
        
    }





    include 'init.php' ;





    //check if user coming from http post request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        if (isset($_POST['login'])) {


            $user = $_POST['username'];
            $pass = $_POST['password'];

            $hashedpass = sha1($pass);


            //check if the user exist in database

            $stmt = $con->prepare(" SELECT 
            userid ,username , password
            from 
                users
            where
                    username = ?
            AND
                password = ?   ");

            $stmt->execute(array($user,$hashedpass));

            $get = $stmt->fetch();


            $count =  $stmt->rowCount();


            //echo $count;

            //if count > 0 this mean the database contain record about  this username

            if ($count > 0){

                $_SESSION['user'] = $user ; //register session name

                $_SESSION['uid'] = $get['userid']; //register user id in session

                header('Location: index.php'); //redirect to index page

                exit();

            }
        }else{
            $formErrors = array();



            $username   = $_POST['username'] ;
            $password   = $_POST['password'] ;
            $password2  = $_POST['password2'] ;
            $email      = $_POST['email'] ;

            //-------validate user name--------

            if (isset($username)) {


                //$filterdUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING); //deparated


                $filterdUser = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

                if (strlen($filterdUser) < 4) {

                    $formErrors[] ='Username Must be larger than 4 characters '  ;

                }
            }

            //-------validate password-----------

            if (isset($password) && isset($password2)) {


                
                if (empty($password)) {
                    $formErrors[] = 'sorry password can\'t be empty';
                }



                if (sha1($password) !== sha1($password2)) {
                    $formErrors[] = 'sorry password is not match';
                }


                
            }

            //-------validate email --------

            if (isset($email)) {


                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL );

                if (filter_var($filterdEmail , FILTER_VALIDATE_EMAIL) != true) {

                    $formErrors[] ='this email is not valid'  ;

                }
            }



                     //check if there is no error proceed the user add
 
                        if(empty($formerrors)){

                            //check if user exist in database
                            $value = $username;
                            $check = checkitem("username" , "users" , $value) ;
                        
                            if ($check == 1) {
                                $formErrors[] ='this user is exist'  ;
                            }else{

                            
                        

                            //Insert  user info in database
                            $stmt= $con->prepare("INSERT INTO 
                                                        users(username , password , email ,regstatus, Date )
                                                    VALUES(:zuser , :zpass , :zmail ,  0 , now()) ");

                            $stmt->execute(array(

                                'zuser' => $username,
                                'zpass'=> sha1($password),
                                'zmail'=> $email,

                            ));
                            
                            //echo success message

                            $successMsg  = 'congrats you are now registerd user';

                                
                        }

                }

            


        }

       
    }




    ?>

    <div class="container login-page">

        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span>  | 
            <span data-class="signup">SignUp</span>
        </h1>

        <!-- start login form -->

        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                <div class="input-container"><input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your UserName"  required /></div>
                <div class="input-container"><input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your password" required /></div>
                <input class="btn btn-primary btn-block" name="login" type="submit" value="login" />
        </form>

        <!-- end login form -->



        <!-- start signup form -->


        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >

            <div class="input-container"><input class="form-control" pattern=".{4,}" title="Username must be  4  characters" type="text" name="username" autocomplete="off" placeholder="Type Your UserName" required  /></div>
            <div class="input-container"><input class="form-control" minlength="4" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex password" required  /></div>
            <div class="input-container"><input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type a  password again" required   /></div>
            <div class="input-container"><input class="form-control" type="email" name="email"  placeholder="Type a Valid Email" required ></div>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="signup" />
        
        </form>


        <!-- end signup form -->

        <div class="the-errors text-center">

        <?php

        if (!empty($formErrors)) {
        
            foreach ($formErrors as $error) {
                echo '<div class="msg error ">'. $error . '</div>' ;
            }
        
        }
        
        if (isset( $successMsg)){

            echo '<div class="msg success">'. $successMsg .'</div>';

        }



        ?>
        </div>

    </div>


<?php

include  $tpl. 'footer.php';
ob_end_flush();
?>
