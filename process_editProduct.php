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

$query = "UPDATE $tableName 
           SET ProductName='$productName',MinimumStockValue='$minimumStockValue',CurrentStockValue='$currentStockValue',
           Ordered='$ordered',SupplierName='$supplierName',Phone='$phoneNumber' 
           WHERE productID='$productID'";
mysqli_query($connection,$query);



//header('Location: stockManagement.php');// redirect user
//exit;
?>