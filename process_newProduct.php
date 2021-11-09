<?php
session_start();
(string)$companyName = $_SESSION['companyName'];
$_SESSION['TableName'] = "stockmanagementtable_".$companyName;
$tableName = $_SESSION['TableName'];
(string)$productName = $_GET['ProductName'];
(int)$minimumStockValue = $_GET['MinimumStockValue'];
(int)$currentStockValue = $_GET['CurrentStockValue'];
(string)$ordered = $_GET['Ordered'];
(string)$supplierName = $_GET['SupplierName'];
(int)$phoneNumber = $_GET['PhoneNumber'];

include "database_connect.php";
$connection = openConnection();

$query = "INSERT INTO $tableName(ProductName,MinimumStockValue,CurrentStockValue,Ordered,SupplierName,Phone)
          VALUES('$productName', '$minimumStockValue', '$currentStockValue', '$ordered',
          '$supplierName', '$phoneNumber')";
mysqli_query($connection,$query);

//header('Location: stockManagement.php');// redirect user
//exit;
?>