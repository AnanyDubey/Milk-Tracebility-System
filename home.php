<?php
require "postdata.php";
//$success="False";
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$domain = $_SESSION['domain'];
$errors = array();
$info = array();
$success="False";
$whitelist_emails=['crash1741@gmail.com'];
foreach ($whitelist_emails as $accepted) {
    if ($email == $accepted){
        $success="True";
        break;
    }
}

$domain_check = "SELECT domain FROM admins WHERE email = '$email'";
$res = mysqli_query($con, $domain_check);
$data = mysqli_fetch_assoc($res);
if($data['domain'] != $domain && $success != "True"){
    $errors = "Please choose your correct domain!";
    session_unset();
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}

if (isset($_SESSION['info'])){
    $info[]=$_SESSION['info'];
    unset($_SESSION['info']);
}
if (isset($_SESSION['errors'])){
    $errors[]=$_SESSION['errors'];
    unset($_SESSION['errors']);
}
if($email != false && $password != false){
    $sql = "SELECT * FROM admins WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "notverified"){
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: logout-user.php');
}
//echo $query;
//print_r($_SESSION);
//echo"<br>";
//echo $domain;
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <!-- <link href="style.css" rel="stylesheet"> -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <nav><button type="button" class="btn btn-light"><a href="logout-user.php">Logout</a></button></nav>
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
    <body>
        <?php
        if(count($info) > 0){
        ?>
        <div class="alert alert-success text-center">
            <?php
            foreach($info as $showinfo){
                echo $showinfo;
            }
            ?>
        </div>
        <?php
        }
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

        switch($domain){
            case 'Diary Farm':
                // Run the query.
                $result = $con->query("SELECT * FROM dairy_farm");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>

        <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
            <h2 class="text-center">Diary Farm</h2>
            <div class="form-group">
                <input class="form-control" type="text" name="cbreed" placeholder="Enter Cattle Breed" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="milkq" placeholder="Enter Milk Quantity" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="date" name="mdate" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="vaccn" placeholder="Enter Vaccines" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="feeds" placeholder="Enter Feeds" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="bname" placeholder="Buyer's Name" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Enter batch number" maxlength="250" required>
            </div>									
            <div class="form-group">
                <input class="form-control button" type="submit" name="submit_diary" value="Submit">
            </div>
            
        </form>
        <?php break; 
            case 'Collection & Transportation':
                // Run the query.
                $result = $con->query("SELECT * FROM coll_trans");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>

        <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
            <h2 class="text-center">Collection & Transportation</h2>
            <div class="form-group">
                <input class="form-control" type="date" name="dcol" placeholder="Date of collecting" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="tcol" placeholder="Time of collecting" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="loc" placeholder="Enter location" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Enter batch number" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="mquan" placeholder="Enter accepted milk quantity" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="qdet" placeholder="Quality details" maxlength="250" >
            </div>		
            <div class="form-group">
                <input class="form-control" type="text" name="vdet" placeholder="Vehicle details" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="icoll" placeholder="Collector's ID" maxlength="250" >
            </div>            							
            <div class="form-group">
                <input class="form-control button" type="submit" name="submit_collec" value="Submit">
            </div>
        </form>

        <?php break;

            case 'Processing/Manufacturing':
                // Run the query.
                $result = $con->query("SELECT * FROM process");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>


        <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
            <h2 class="text-center">Processing/Manufacturing</h2>
            <div class="form-group">
                <input class="form-control" type="text" name="pdet" placeholder="Enter processing details" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="date" name="mdate">
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="mtime" placeholder="Enter manufacturing time" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="ptest" placeholder="Product Testing" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="loc" placeholder="Enter location" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="qvec" placeholder="Enter quality vector" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Enter batch number" maxlength="250" required>
            </div>	
            <div class="form-group">
                <input class="form-control button" type="submit" name="submit_process" value="Submit">
            </div>
        </form>


        <?php break;

            case 'Packaging':
                // Run the query.
                $result = $con->query("SELECT * FROM package");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>

        <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
            <h2 class="text-center">Packaging</h2>
            <div class="form-group">
                <input class="form-control" type="date" name="pdate" placeholder="Enter package date" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="ptime" placeholder="Enter package date" maxlength="250" >
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="loc" placeholder="Enter location" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="pdet" placeholder="Enter packaging details" maxlength="250" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Batch number" maxlength="250" >
            </div>									
            <div class="form-group">
                <input class="form-control button" type="submit" name="submit_pack" value="Submit">
            </div>
        </form>

        <?php break;

            case 'Distribution':
                // Run the query.
                $result = $con->query("SELECT * FROM distribute");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>

         <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
         <h2 class="text-center">Distribution</h2>
         <div class="form-group">
             <input class="form-control" type="text" name="snum" placeholder="Enter shipment number" maxlength="250" >
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="vol" placeholder="Enter volume" maxlength="250" >
         </div>

         <div class="form-group">
             <input class="form-control" type="text" name="vdet" placeholder="Enter vehicle details" maxlength="250" required>
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="rid" placeholder="Enter Receiver's identity" maxlength="250" required>
         </div>
         <div class="form-group">
             <input class="form-control" type="num" name="onum" placeholder="Order number" maxlength="250" >
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="qfac" placeholder="Quality factors" maxlength="250" >
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="cstat" placeholder="Cargo Status" maxlength="250" >
         </div>	
         <div class="form-group">
             <input class="form-control" type="date" name="date" placeholder="Date" maxlength="250" >
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="time" placeholder="Time" maxlength="250" >
         </div>	
         <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Enter batch number" maxlength="250" required>
            </div>									
         <div class="form-group">
             <input class="form-control button" type="submit" name="submit_dist" value="Submit">
         </div>
         </form>

        <?php break;

        case 'Retailers/Wholesalers':
                // Run the query.
                $result = $con->query("SELECT * FROM retailer");
                $con->close();

                // Get the result in to a more usable format.
                $query = array();
                while($query[] = mysqli_fetch_assoc($result));
                array_pop($query);

                // Output a dynamic table of the results with column headings.
                echo '<table border="1">';
                echo '<tr>';
                foreach($query[0] as $key => $value) {
                    echo '<td>';
                    echo $key;
                    echo '</td>';
                }
                echo '</tr>';
                foreach($query as $row) {
                    echo '<tr>';
                    foreach($row as $column) {
                        echo '<td>';
                        echo $column;
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
        ?>

         <form action="" enctype="multipart/form-data" method="POST" autocomplete="off" style="background-color:powderblue;">
         <h2 class="text-center">Distribution</h2>
         <div class="form-group">
             <input class="form-control" type="date" name="ware_date" >
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="ware_time" placeholder="Enter ware time" maxlength="250" >
         </div>

         <div class="form-group">
             <input class="form-control" type="text" name="loc" placeholder="Enter vehicle details" maxlength="250" required>
         </div>
         <div class="form-group">
             <input class="form-control" type="date" name="shelf_date" required>
         </div>
         <div class="form-group">
             <input class="form-control" type="text" name="shelf_time" placeholder="Order number" maxlength="250" >
         </div>
         <div class="form-group">
                <input class="form-control" type="text" name="bnum" placeholder="Enter batch number" maxlength="250" required>
            </div>	
         <div class="form-group">
             <input class="form-control button" type="submit" name="submit_rtl" value="Submit">
         </div>
         </form>

        <?php break;

        } ?>
    </body>
</html>