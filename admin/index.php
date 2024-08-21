



<?php 


    session_start();

    $nonavbar = '';
    $pageTitle = 'login';


    if (isset($_SESSION['username'])) {
        header('Location: dashboard.php'); //redirect to dashboard page
        
    }


    //print_r($_SESSION);
    include "init.php";
    //include 'includes/languages/arabic.php';


    //check if user coming from http post request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);

        //echo   $username . ' ' .  $password;
        //echo $hashedpass;

        //check if the user exist in database

        $stmt = $con->prepare("SELECT 
        UserId,username , password
          from 
            users
          where
                 username = ?
          AND
             password = ? 
          And
                 groupid = 1 
                 LIMIT    1");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch();
        $count =  $stmt->rowCount();


        //echo $count;

        //if count > 0 this mean the database contain record about  this username

        if ($count > 0){
            $_SESSION['username'] = $username ; //register session name
            $_SESSION['ID'] = $row['UserId'] ; //register session ID
            header('Location: dashboard.php'); //redirect to dashboard page
            exit();
        }
    }

?>



 <!-- <i class="fa fa-home fa-5x"></i>   -->    <!--  <div class="btn btn-danger btn-block">test bootstrap</div> -->

<?php

   // echo lang('message') . ' ' . lang('admin');

?>




<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>

    <input class="form-control input-lg" type="text" name="user" placeholder="username" autocomplete="off" />
    <input class="form-control input-lg" type="password" name="pass" placeholder="password" autocomplete="new-password" />
    <input class="btn input-lg btn-primary btn-block" type="submit" value="login" />
</form>






<?php include $tpl . "footer.php"; ?>

