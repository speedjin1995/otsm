<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$db = mysqli_connect("srv597.hstgr.io", "u664110560_otsm_cw", "Otsm@123", "u664110560_otsm_cw");
$db2 = mysqli_connect("srv597.hstgr.io", "u664110560_otsm_admin", "Otsm@123", "u664110560_otsm_admin");

if(mysqli_connect_errno()){
    echo 'Database connection failed with following errors: ' . mysqli_connect_error();
    die();
}
?>