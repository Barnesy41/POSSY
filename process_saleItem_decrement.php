<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

session_start();
$transactionID = $_SESSION['transactionID'];
$companyName = $_SESSION['companyName'];
$saleItemName = $_GET['saleItem'];

$tableName = "transaction_".$transactionID."_".$companyName;
$query = "SELECT Quantity FROM $tableName WHERE SaleItemName = '$saleItemName'";
$result = mysqli_query($connection,$query);

// update the quantity of that sale item in the transaction
$row = mysqli_fetch_assoc($result);
$quantity = $row['Quantity'];
$newQuantity = $quantity - 1;

if($newQuantity >0) {
    $query = "UPDATE $tableName 
              SET Quantity = '$newQuantity' 
              WHERE SaleItemName = '$saleItemName' ";
    mysqli_query($connection, $query);
}
else{
    $query = "DELETE FROM $tableName WHERE SaleItemName = '$saleItemName'";
    mysqli_query($connection,$query);
}

// redirect user
header('Refresh: 0; URL=pointOfSale.php');
exit;