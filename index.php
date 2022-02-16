<?php include("postdata.php");
if (isset($_SESSION['info'])){
    $info[]=$_SESSION['info'];
    unset($_SESSION['info']);
}

if (isset($_SESSION['errors'])){
    $errors[]=$_SESSION['errors'];
    unset($_SESSION['errors']);
}

?>
<html>
    <head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- <link href="style.css" rel="stylesheet"> -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    </head>
    <!------ Include the above in your HEAD tag ---------->

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
    <body style="background-color:powderblue;">
    
    <div class="sidenav">
    <div class="login-main-text">
    <h2>Milk Tracebility System<br>
    <h4>Login or register here to access</h4>
    </div>
    </div>
    
    <div class="main">
    <div class="col-md-6 col-sm-12">
    <div class="login-form">
    <form method="post">
    <?php
    if(count($errors) > 0){
?>
<div class="alert alert-danger text-center">
    <?php
        foreach($errors as $showerror){
            echo $showerror; } ?>
</div>
<?php } ?>
<div class="form-group">
    <label>Email</label>
    <input type="text" name="email" class="form-control" placeholder="Email" required>
</div>
<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<div class="form-group">
    <label for="domain">Choose a domain:</label>
    <select class="form-group" name="domain" id="domain" required>
        <option value="Diary Farm">Diary Farm</option>
        <option value="Collection & Transportation">Collection & Transportation</option>
        <option value="Processing/Manufacturing">Processing/Manufacturing</option>
        <option value="Packaging">Packaging</option>
        <option value="Distribution">Distribution</option>
        <option value="Retailers/Wholesalers">Retailers/Wholesalers</option>

    </select>
</div>
<br><br>
<button type="submit" name="login" class="btn btn-black">Login</button>
<button type="submit" name="signup" class="btn btn-secondary">Register</button>
</form>
</div>
</div>
</div>
</body>
</html>