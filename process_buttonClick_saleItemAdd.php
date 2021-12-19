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

echo $query;
// This fixed an unusual bug with mysqli_query sometimes returning 0 rows but not being false
$numRows = 0;
if($result != false){
    $numRows = mysqli_num_rows($result);
}

// Add sale item into current transaction
if ($numRows == 0){
    // Inserts a new row into the current transaction table if the sale item does not already exist in the
    // current transaction
    $query = "INSERT INTO "."$tableName"."(SaleItemName,Cost,Quantity) VALUES('$saleItemName','$cost','1')";
    mysqli_query($connection,$query);

}
else{
    // If the sale item exists in the current transaction,
    // update the quantity of that sale item in the transaction
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