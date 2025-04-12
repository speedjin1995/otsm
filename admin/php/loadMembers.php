<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";

if(isset($_POST['id']) && $_POST['id'] != null && $_POST['id'] != ''){
  $searchQuery = " AND customer = '".$_POST['id']."'";
}

if($searchValue != ''){
   $searchQuery = " and (users.name like '%".$searchValue."%' or 
        users.username like '%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($db2,"select count(*) as allcount from users, roles, companies WHERE users.role_code = roles.role_code AND users.customer = companies.id AND users.deleted = '0' AND users.customer <> '0'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db2,"select count(*) as allcount from users, roles, companies WHERE users.role_code = roles.role_code AND users.customer = companies.id AND users.deleted = '0' AND users.customer <> '0'".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select users.*, roles.role_name, companies.name AS company, users.indicator_bluetooth, users.printer_bluetooth from users, roles, companies WHERE 
users.role_code = roles.role_code AND users.customer = companies.id AND users.deleted = '0' AND users.customer <> '0'".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db2, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
    $joined_date = '-';
    $activated_date = '-';
    $expired_date = '-';
    $indicator = '';
    $printer = '';
    
    if($row['created_date'] != null && $row['created_date'] != ""){
      $joined_date = date("d-m-Y", strtotime($row['created_date']));
    }

    if($row['activation_date'] != null && $row['activation_date'] != ""){
      $activated_date = date("d-m-Y", strtotime($row['activation_date']));
    }

    if($row['expired_datetime'] != null && $row['expired_datetime'] != ""){
      $expired_date = date("d-m-Y", strtotime($row['expired_datetime']));
    }

    if(isset($row['indicator_bluetooth']) && $row['indicator_bluetooth'] != null && $row['indicator_bluetooth'] != ''){
      if ($update_stmt = $db->prepare("SELECT * FROM scales WHERE id=?")) {
        $update_stmt->bind_param('s', $row['indicator_bluetooth']);
        $update_stmt->execute();
        $result = $update_stmt->get_result();
        
        if ($row2 = $result->fetch_assoc()) {
          $indicator = $row2['indicator_bluetooth'];
        }
      }
    }

    if(isset($row['printer_bluetooth']) && $row['printer_bluetooth'] != null && $row['printer_bluetooth'] != ''){
      if ($update_stmt2 = $db->prepare("SELECT * FROM printers WHERE id=?")) {
        $update_stmt2->bind_param('s', $row['printer_bluetooth']);
        $update_stmt2->execute();
        $result2 = $update_stmt2->get_result();
        
        if ($row3 = $result2->fetch_assoc()) {
          $printer = $row3['printer_bluetooth'];
        }
      }
    }
    
    $data[] = array( 
      "id"=>$row['id'],
      "name"=>$row['name'],
      "username"=>$row['username'],
      "role_name"=>$row['role_name'],
      "company"=>$row['company'],
      "indicator"=>$indicator,
      "printer"=>$printer,
      "created_date"=>$joined_date,
      "activated_date"=>$activated_date,
      "expired_date"=>$expired_date
    );
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);

echo json_encode($response);

?>