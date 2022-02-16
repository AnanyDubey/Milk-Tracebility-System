<?php require_once "postdata.php";
//$email = $_SESSION['email'];
//$password = $_SESSION['password'];
//if($email == false && $password == false){
//	header('Location: logout-user.php');
//}
//print_r($_SESSION);
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <!-- <link href="style.css" rel="stylesheet"> -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    <style>

        body {
            font-family: "Lato", sans-serif;
        }



        .main-head{
            height: 150px;
            background: #FFF;

        }

        .sidenav {
            height: 100%;
            background-color: #000;
            overflow-x: hidden;
            padding-top: 20px;
        }


        .main {
            padding: 0px 10px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
        }

        @media screen and (max-width: 450px) {
            .login-form{
                margin-top: 10%;
            }

            .register-form{
                margin-top: 10%;
            }
        }

        @media screen and (min-width: 768px){
            .main{
                margin-left: 40%; 
            }

            .sidenav{
                width: 40%;
                position: fixed;
                z-index: 1;
                top: 0;
                left: 0;
            }

            .login-form{
                margin-top: 80%;
            }

            .register-form{
                margin-top: 20%;
            }
        }


        .login-main-text{
            margin-top: 20%;
            padding: 60px;
            color: #fff;
        }

        .login-main-text h2{
            font-weight: 300;
        }

        .btn-black{
            background-color: #000 !important;
            color: #fff;
        }

    </style>
    <body>
        <div class="sidenav">
            <div class="login-main-text">
                <h2>Application<br> Login Page</h2>
                <p>Enter OTP here to access.</p>
            </div>
        </div>
        <div class="main">
            <div class="col-md-6 col-sm-12">
                <div class="login-form">
                    <form action="user-otp.php" method="POST" autocomplete="off">
                        <h2 class="text-center">Code Verification</h2>
                        <?php 
                        if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                        if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="form-group">
                            <input class="form-control" type="number" name="otp" placeholder="Enter verification code" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control button" type="submit" name="check" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>