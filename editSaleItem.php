<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

session_start();
$tableName = $_SESSION['companyMenuTableName'];

$query = "SELECT SaleItemName FROM $tableName";
$result = mysqli_query($connection,$query);

echo "<form action='process_editSaleItem.php'>";
echo "<label for='saleItemToEdit'>Choose a Sale Item: </label>";
echo "<select name='saleItemToEdit' id='saleItemToEdit'>";

for ($i=0;$i<mysqli_num_rows($result);$i++){
    $row = mysqli_fetch_assoc($result);
    $saleItem = $row['SaleItemName'];
        echo "<option value='$saleItem'>$saleItem</option>";
}
echo "<option value='+new'>New Sale Item</option>";
echo "</select>";

echo "<input type='text' name='saleItemName' placeholder='Banana'>";
echo "<input type='number' name='saleItemPrice' placeholder='0.00' step='0.01' min='0.00' minlength='3'>";
echo "<input type='text' name='saleItemCategory' placeholder='Food'>";
echo "<textarea name='products' placeholder='bread,bread,cheese'></textarea>";
echo "<button type='submit'>Confirm</button>";

require_once('functions.php');
isUserAllowedAccessToThisPage($_SESSION['accountType'],"editSaleItem.php");
?>