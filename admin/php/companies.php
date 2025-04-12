<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['reg_no'], $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['products'], $_POST['type'])){
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $reg_no = filter_input(INPUT_POST, 'reg_no', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $address2 = null;
    $address3 = null;
    $address4 = null;
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $products = $_POST['products'];
    $products = json_encode($products);
    $fax = null;
    $website = null;
    $farms_no = null;
    $companies = null;

    if(isset($_POST['companies']) && $_POST['companies'] != null && $_POST['companies'] != ''){
        $companies = filter_input(INPUT_POST, 'companies', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['fax']) && $_POST['fax'] != null && $_POST['fax'] != ''){
        $fax = filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['website']) && $_POST['website'] != null && $_POST['website'] != ''){
        $website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['farm_no']) && $_POST['farm_no'] != null && $_POST['farm_no'] != ''){
        $farms_no = filter_input(INPUT_POST, 'farm_no', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['address2']) && $_POST['address2'] != null && $_POST['address2'] != ''){
        $address2 = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['address3']) && $_POST['address3'] != null && $_POST['address3'] != ''){
        $address3 = filter_input(INPUT_POST, 'address3', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['address4']) && $_POST['address4'] != null && $_POST['address4'] != ''){
        $address4 = filter_input(INPUT_POST, 'address4', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['phone']) && $_POST['phone'] != null && $_POST['phone'] != ''){
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    }

    if(isset($_POST['email']) && $_POST['email'] != null && $_POST['email'] != ''){
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    }

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db2->prepare("UPDATE companies SET reg_no=?, name=?, address=?, address2=?, address3=?, address4=?, phone=?, email=?, products=?, fax=?, website=?, farms_no=?, type=?, parent=? WHERE id=?")) {
            $update_stmt->bind_param('sssssssssssssss', $reg_no, $name, $address, $address2, $address3, $address4, $phone, $email, $products, $fax, $website, $farms_no, $type, $companies, $_POST['id']);
            
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
                        "message"=> "Updated Successfully!!" 
                    )
                );
            }
        }
    }
    else{
        if ($insert_stmt = $db2->prepare("INSERT INTO companies (reg_no, name, address, address2, address3, address4, phone, email, products, fax, website, farms_no, type, parent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssssssssssssss', $reg_no, $name, $address, $address2, $address3, $address4, $phone, $email, $products, $fax, $website, $farms_no, $type, $companies);
            
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
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