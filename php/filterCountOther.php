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

if($_POST['fromDate'] != null && $_POST['fromDate'] != ''){
  $searchQuery = " and created_datetime >= '".$_POST['fromDate']."'";
}

if($_POST['toDate'] != null && $_POST['toDate'] != ''){
	$searchQuery .= " and created_datetime <= '".$_POST['toDate']."'";
}

if($_POST['farm'] != null && $_POST['farm'] != '' && $_POST['farm'] != '-'){
	$searchQuery .= " and farm_id = '".$_POST['farm']."'";
}

if($_POST['customer'] != null && $_POST['customer'] != '' && $_POST['customer'] != '-'){
	$searchQuery .= " and customer = '".$_POST['customer']."'";
}

if($searchValue != ''){
  $searchQuery = " and (weighing.serial_no like '%".$searchValue."%' or 
  weighing.lorry_no like '%".$searchValue."%' )";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing, farms WHERE weighing.farm_id = farms.id AND weighing.deleted = '0' AND weighing.status='Complete' AND farms.category IN ('Other')");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing, farms WHERE weighing.farm_id = farms.id AND weighing.deleted = '0' AND weighing.status='Complete' AND farms.category IN ('Other')".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select weighing.*, farms.name from weighing, farms WHERE weighing.farm_id = farms.id AND weighing.deleted = '0' AND weighing.status='Complete' AND farms.category IN ('Other')".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();
$counter = 1;

while($row = mysqli_fetch_assoc($empRecords)) {
  $weight_data = json_decode($row['weight_data'], true);
  $averageBirds = 0;
  $totalWeigh = 0;
  $totalBirds = 0;
  $totalCages = 0;

  for($i=0; $i<count($weight_data); $i++){
    $totalBirds += (int)$weight_data[$i]['numberOfBirds'];
    $totalCages += (int)$weight_data[$i]['numberOfCages'];
    $totalWeigh += (float)$weight_data[$i]['netWeight'];
  }

  $averageBirds = $totalWeigh / $totalBirds;

  $data[] = array( 
    "no"=>$counter,
    "id"=>$row['id'],
    "status"=>$row['status'],
    "serial_no"=>$row['serial_no'],
    "po_no"=>$row['po_no'],
    "group_no"=>$row['group_no'],
    "customer"=>$row['customer'],
    "supplier"=>$row['supplier'],
    "product"=>$row['product'],
    "driver_name"=>$row['driver_name'],
    "lorry_no"=>$row['lorry_no'],
    "farm_id"=>$row['name'],
    "average_cage"=>$row['average_cage'],
    "average_bird"=>number_format($averageBirds, 2, '.', ''),
    "minimum_weight"=>$row['minimum_weight'],
    "maximum_weight"=>$row['maximum_weight'],
    "max_crate"=>$row['max_crate'],
    "created_datetime"=>$row['created_datetime'],
    "start_time"=>$row['start_time'],
    "end_time"=>$row['end_time'],
    "total_birds"=>$totalBirds,
    "total_cages"=>$totalCages
  );

  $counter++;
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