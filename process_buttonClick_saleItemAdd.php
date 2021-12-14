<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

session_start();
$transactionID = $_SESSION['transactionID'];
$companyName = $_SESSION['companyName'];

$saleItemName = $_GET['saleItemName'];
$cost = $_GET['saleItemCost'];

$tableName = "transaction_".$transactionID."_".$companyName;

$query = "SELECT Quantity FROM $tableName WHERE SaleItemName = '$saleItemName'";
$result = mysqli_query($connection,$query);


If (mysqli_num_rows($result) == 0){
    $query = "INSERT INTO $tableName(SaleItemName,Cost,Quantity) VALUES('$saleItemName','$cost','1')";
    mysqli_query($connection,$query);

}
else{
    $row = mysqli_fetch_assoc($result);
    $quantity = $row['Quantity'];

    $newQuantity = $quantity + 1;
    $query = "UPDATE $tableName 
              SET Quantity = '$newQuantity' 
              WHERE SaleItemName = '$saleItemName' ";
    mysqli_query($connection,$query);

}




header('Location: pointOfSale.php');// redirect to home page
exit;
?>