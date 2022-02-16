<?php 
$con = mysqli_connect('localhost', 'root', '', 'test');
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https";
elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    $url = "https";
else
    $url = "http";
  
$url .= "://";
  
$url .= $_SERVER['HTTP_HOST'];
// $bnum = 126;
// //$url .= $_SERVER['REQUEST_URI'];

// echo $url ;
?>