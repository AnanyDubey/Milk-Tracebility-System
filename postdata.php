<?php
session_set_cookie_params(time()+600,'/','localhost',true,true);
session_start();
require "conn.php";
include('phpqrcode/qrlib.php');

$email = "";
$name = "";
$count=0;
$pattern = "/@gmail.com/i";
$errors = array();
$info = array();

function getIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ipAddr=$_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "src/PHPMailer.php";
require_once "src/SMTP.php";
require_once "src/Exception.php";

//if user signup button
if(isset($_POST['signup'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $domain = mysqli_real_escape_string($con, $_POST['domain']);
    $email_check = "SELECT * FROM admins WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered already exists!";
    }
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO admins (email, password, domain, code, status) values('$email', '$encpass', '$domain', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";

            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'crash1741@gmail.com';
            $mail->Password = 'Qwertyuiop123!';
            $mail->SMTPSecure = 'tls';
            $mail->setFrom("crash1741@gmail.com","Milk Tracebility System");
            $mail->AddAddress($email);
            $mail->WordWrap = 50;
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->SMTPOptions=array("ssl"=>array(
                "verify_peer"=>false,
            ));

            if($mail->Send())
            {
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['domain'] = $_POST['domain'];
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}
//if user click verification code submit button
if(isset($_POST['check'])){
    if(isset($_SESSION['total_count'])){
        $total_count=$_SESSION['total_count'];
    }else{
        $total_count=0;
    }
    $_SESSION['info'] = "Email verified successfully";
    $otp = $_POST['otp'];
    $email = $_SESSION['email'];
    $check_code = "SELECT * FROM admins WHERE code = '$otp' and email = '$email'";
    $code_res = mysqli_query($con, $check_code);
    if($total_count==10){
        $errors['email'] = "Too many failed reset attempts. Please try later";
        $_SESSION['email'] = $errors['email'];
        header('Location: logout-user.php');
    }else{
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE admins SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if($update_res){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $_SESSION['password'];
                $_SESSION['domain'] = $_SESSION['domain'];
                header('location: home.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $total_count++;
            $_SESSION['total_count']=$total_count;
            $rem_attm=10-$total_count;
            if($rem_attm==0){
                $block_time=time();
                $errors['email'] = "Too many failed otp attempts. Please try later";
                $_SESSION['email'] = $errors['email'];
                header('Location: logout-user.php');
            }else{
                $errors['email'] = "You've entered incorrect code! $rem_attm attempts remaining";
            }
            $try_time=time();
        }
    }
}

//if user click login button
if(isset($_POST['login'])){
    $time=time()-3600;
    $email_count=0;
    $ip_address=getIpAddr();
    $check_login_row=mysqli_fetch_assoc(mysqli_query($con, "select count(*) as total_count from login_log where try_time>$time and ip_address='$ip_address'"));
    $total_count=$check_login_row['total_count'];

    $check_email_row=mysqli_fetch_assoc(mysqli_query($con, "select count(*) as email_count from blacklist_email where block_time>$time and block_email='$email'"));
    $email_count=$check_email_row['email_count'];

    if($total_count==3 || $email_count>0){
        $errors['email']="Too many failed login attempts. Please login after 1 hour";
    }else{
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM admins WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;
                $status = $fetch['status'];
                if($status == 'verified'){
                    mysqli_query($con, "delete from login_log where ip_address='$ip_address'");
                    mysqli_query($con, "delete from blacklist_email where block_email='$email'");
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $codie="true";
                    $_SESSION['codie'] = $codie;
                    $_SESSION['domain'] = $_POST['domain'];
                    header('location: home.php');
                    exit();
                }elseif ($status == 'notverified') {
                    $info = "It looks like you haven't verifified your email yet - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $_SESSION['domain'] = $_POST['domain'];
                    header('location: user-otp.php');
                    exit();
                }
            }else{
                $total_count++;
                $rem_attm=3-$total_count;
                if($rem_attm==0){
                    $block_time=time();
                    $errors['email'] = "Too many failed login attempts. Please login after 1 hour";
                    mysqli_query($con, "insert into blacklist_email(block_email, block_time) values('$email','$block_time')");
                }else{
                    $errors['email'] = "Incorrect password! $rem_attm attempts remaining";
                }
                $try_time=time();
                mysqli_query($con, "insert into login_log(ip_address, try_time) values('$ip_address','$try_time')");
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member!";
        }
    }
}

//Diary Farm submission
if(isset($_POST['submit_diary'])){

    extract($_POST);

    $query = "INSERT INTO dairy_farm (Cattle_Breed, Milk_Quantity, Milking_Date, Vaccines, Feeds, Buyer_Name, Batch_Number) VALUES('$cbreed','$milkq','$mdate','$vaccn','$feeds','$bname','$bnum')";
    $query2 = "INSERT INTO qr (Cattle_Breed, Milk_Quantity, Milking_Date, Vaccines, Feeds, Buyer_Name, Batch_Number) VALUES('$cbreed','$milkq','$mdate','$vaccn','$feeds','$bname','$bnum')";
    if(mysqli_query($con,$query2)){
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr1.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;

    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}
}
if(isset($_POST['submit_collec'])){

    extract($_POST);

    $query = "INSERT INTO coll_trans (date_coll, time_coll, loc, batch_num, milk_quant, detail_qual, detail_veh, iden_coll) VALUES('$dcol','$tcol','$loc','$bnum','$mquan','$qdet','$vdet','$icoll')";
    $query2 = "UPDATE qr SET Collection_Date='$dcol', Collection_Time = '$tcol', Location='$loc', Quality_Details='$qdet', Vehicle_Details='$vdet', Collector_Identity='$icoll' WHERE Batch_Number='$bnum'";
    mysqli_query($con, $query2);
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr2.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;
    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}

if(isset($_POST['submit_process'])){

    extract($_POST);

    $query = "INSERT INTO process (proc_det, maf_date, maf_time, prod_test, loc, qual_fac, batch_num) VALUES('$pdet','$mdate','$mtime','$ptest','$loc','$qvec','$bnum')";
    $query2 = "UPDATE qr SET Product_Details='$pdet', Manufacturing_Date = '$mdate', Manufacturing_Time='$mtime', Production_Test='$ptest', Quality_Factor='$qvec' WHERE Batch_Number='$bnum'";
    mysqli_query($con, $query2);
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr3.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;
    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}

if(isset($_POST['submit_pack'])){

    extract($_POST);

    $query = "INSERT INTO package (pack_date, pack_time, loc, pack_det, batch_num) VALUES('$pdate','$ptime','$loc','$pdet','$bnum')";
    $query2 = "UPDATE qr SET Packaging_Date='$pdate', Packaging_Time = '$ptime', Packaging_Details='$pdet' WHERE Batch_Number='$bnum'";
    mysqli_query($con, $query2);
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr4.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;
    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}

if(isset($_POST['submit_dist'])){

    extract($_POST);

    $query = "INSERT INTO distribute (ship_num, vol, veh_det, rec_id, order_num, qual_fac, cargo_stat, date_disp, disp_time, batch_num) VALUES('$snum','$vol','$vdet','$rid','$onum','$qfac','$cstat','$date','$time','$bnum')";
    $query2 = "UPDATE qr SET Shipment_Number='$snum', Volume = '$vol', Receivers_Identity='$rid', Order_Number='$onum', Cargo_Status='$cstat',Display_Date='$date', Display_Time='$time' WHERE Batch_Number='$bnum'";
    mysqli_query($con, $query2);
    
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr5.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;
    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}

if(isset($_POST['submit_rtl'])){

    extract($_POST);

    $query = "INSERT INTO retailer (ware_date, ware_time, loc, shelf_date, shelf_time ,batch_num) VALUES('$ware_date','$ware_time','$loc','$shelf_date','$shelf_time','$bnum')";
    $query2 = "UPDATE qr SET Warehouse_Date='$ware_date', Warehouse_Time = '$ware_time', Shelf_Date='$shelf_date', Shelf_Time='$shelf_time' WHERE Batch_Number='$bnum'";
    mysqli_query($con, $query2);
    if(mysqli_query($con, $query)) {
        $url .= "/Anany/details.php?bnum=$bnum"; 
        $filename = 'qr6.png';
        QRcode::png($url, $filename, 'H', 5, 1);
        $info = "Data inserted successfully";
        $_SESSION['info'] = $info;
    }else{
        $errors = "Data insertion failed";
        $_SESSION['errors'] = $errors;
    }
}
?>