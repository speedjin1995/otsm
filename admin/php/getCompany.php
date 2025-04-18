<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db2->prepare("SELECT * FROM companies WHERE id=?")) {
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
                $message['reg_no'] = $row['reg_no'];
                $message['name'] = $row['name'];
                $message['address'] = $row['address'];
                $message['address2'] = $row['address2'];
                $message['address3'] = $row['address3'];
                $message['address4'] = $row['address4'];
                $message['phone'] = $row['phone'];
                $message['fax'] = $row['fax'];
                $message['email'] = $row['email'];
                $message['website'] = $row['website'];
                $message['farms_no'] = $row['farms_no'];
                $message['type'] = $row['type'];
                $message['parent'] = $row['parent'];

                if($row['products'] != null){
                    $message['products'] = json_decode($row['products'], true);
                }
                else{
                    $message['products'] = array();
                }
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