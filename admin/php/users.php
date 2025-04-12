<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}
else{
    $userId = $_SESSION['userID'];
}

if(isset($_POST['username'], $_POST['password'], $_POST['name'], $_POST['userRole'], $_POST['customer']
, $_POST['indicator'], $_POST['printer'], $_POST['stamping'], $_POST['stampWeight'], $_POST['activateDate']
, $_POST['expiredDate'])){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $roleCode = filter_input(INPUT_POST, 'userRole', FILTER_SANITIZE_STRING);
    $customer = filter_input(INPUT_POST, 'customer', FILTER_SANITIZE_STRING);
    $indicator = filter_input(INPUT_POST, 'indicator', FILTER_SANITIZE_STRING);
	$printer = filter_input(INPUT_POST, 'printer', FILTER_SANITIZE_STRING);
    $stamping = filter_input(INPUT_POST, 'stamping', FILTER_SANITIZE_STRING);
    $stampWeight = filter_input(INPUT_POST, 'stampWeight', FILTER_SANITIZE_STRING);
    $activateDate = filter_input(INPUT_POST, 'activateDate', FILTER_SANITIZE_STRING);
    $expiredDate = filter_input(INPUT_POST, 'expiredDate', FILTER_SANITIZE_STRING);

    $activateDate = DateTime::createFromFormat('d/m/Y', $activateDate)->format('Y-m-d H:i:s');
    $expiredDate = DateTime::createFromFormat('d/m/Y', $expiredDate)->format('Y-m-d H:i:s');

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db2->prepare("UPDATE users SET username=?, name=?, role_code=?, customer=?, indicator_bluetooth=?, printer_bluetooth=?, include_stamping=?, stamping_weight=?, activation_date=?, expired_datetime=?, license_key=? WHERE id=?")) {
            $randomNumber = mt_rand(10000000, 99999999);
            $update_stmt->bind_param('ssssssssssss', $username, $name, $roleCode, $customer, $indicator, $printer, $stamping, $stampWeight, $activateDate, $expiredDate, $randomNumber, $_POST['id']);
            
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $update_stmt->error
                    )
                );
            }
            else{
                $update_stmt->close();
                $db2->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Updated Successfully"
                    )
                );
            }
        }
    }
    else{
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        //$password = '123456';
        $password = hash('sha512', $password . $random_salt);
        $randomNumber = mt_rand(10000000, 99999999);

        if ($insert_stmt = $db2->prepare("INSERT INTO users (username, name, password, salt, created_by, role_code, customer, indicator_bluetooth, printer_bluetooth, include_stamping, stamping_weight, activation_date, expired_datetime, license_key) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssssssssssssss', $username, $name, $password, $random_salt, $userId, $roleCode, $customer, $indicator, $printer, $stamping, $stampWeight, $activateDate, $expiredDate, $randomNumber);
            
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt->error
                    )
                );
            }
            else{
                $insert_stmt->close();
                $db2->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Added Successfully!!" 
                    )
                );
            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    );
}
?>