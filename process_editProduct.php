<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

$productID = $_SESSION['productID'];
$tableName = $_SESSION['tableName'];
$productName = $_GET['ProductName'];
$minimumStockValue = $_GET['MinimumStockValue'];
$currentStockValue = $_GET['CurrentStockValue'];
$ordered = $_GET['Ordered'];
$supplierName = $_GET['SupplierName'];
$phoneNumber = $_GET['PhoneNumber'];
$arrivalDate = $_GET['dueDate'];
$arrivalAmount = $_GET['restockQuantity'];

/* Validate date & time */
date_default_timezone_set("GMT");
$currentDateLong = date("c");
$currentDateShort = substr($currentDateLong,0,16);

/* Check that the entered date & time is greater than today's date & time*/
if(($arrivalDate > $currentDateShort OR $arrivalDate == null) AND ($arrivalAmount > 0 OR $arrivalDate == null)) {
    $query = "UPDATE $tableName 
           SET ProductName='$productName',MinimumStockValue='$minimumStockValue',CurrentStockValue='$currentStockValue',
           Ordered='$ordered',SupplierName='$supplierName',Phone='$phoneNumber', ArrivalDate='$arrivalDate',
           ArrivalAmount='$arrivalAmount' WHERE productID='$productID'";
    mysqli_query($connection, $query);
    header('refresh: 0; URL = stockManagement.php');// redirect to home page
    exit;
}
else{
    echo "ERROR! Invalid date or restock quantity";
    header('refresh: 2; URL = stockManagement.php');// redirect to home page
    exit;
}


?>