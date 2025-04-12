<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db2->prepare("SELECT * FROM users WHERE id=?")) {
        $update_stmt->bind_param('s', $id);
        
        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();
            
            while ($row = $result->fetch_assoc()) {
                $message['id'] = $row['id'];
                $message['username'] = $row['username'];
                $message['password'] = $row['password'];
                $message['name'] = $row['name'];
                $message['role_code'] = $row['role_code'];
                $message['customer'] = $row['customer'];
                $message['indicator'] = $row['indicator_bluetooth'];
                $message['printer'] = $row['printer_bluetooth'];
                $message['include_stamping'] = $row['include_stamping'];
                $message['stamping_weight'] = $row['stamping_weight'];
                $message['activation_date'] = $row['activation_date'];
                $message['expired_datetime'] = $row['expired_datetime'];
            }
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message
                ));   
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Attribute"
            )); 
}
?>