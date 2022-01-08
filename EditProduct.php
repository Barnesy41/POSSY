<?php

/* Start and initialise session */
session_start();
$_SESSION['tableName'] = $_GET['TableName'];
$_SESSION['productID'] = $_GET['ProductID'];

/* Open DB connection */
include "database_connect.php";
$connection = openConnection();

/* GET EXISTING RESTOCK DATE FROM STOCK MANAGEMENT TABLE */
$tableName = 'stockmanagementtable_'.$_SESSION['companyName'];
$productID = $_GET['ProductID'];
$query = "SELECT ArrivalDate,ArrivalAmount FROM $tableName WHERE ProductID = '$productID' ";
$queryResult = mysqli_query($connection,$query);

/* Enter fetched values into variables */
$tableRow = mysqli_fetch_assoc($queryResult);
$arrivalDate = $tableRow['ArrivalDate'];
$arrivalAmount = $tableRow['ArrivalAmount'];

echo "
<!-- print form -->
    <!-- Display product Name input field -->
    <form action='process_editProduct.php'>
    <label for='ProductName'>Product Name:</label>
    <input type='text' name='ProductName' value='$_GET[ProductName]' required>
    
    <br>

    <!-- Display current stock value input field -->
    <label for='CurrentStockValue'>Current Stock:</label>
    <input type='text' name='CurrentStockValue' value='$_GET[CurrentStockValue]' required>
    
    <br>

    <!-- Display minimum stock value input field -->
    <label for='MinimumStockValue'>Minimum Stock:</label>
    <input type='text' name='MinimumStockValue' value='$_GET[MinimumStockValue]' required>
    
    <br>

    <!-- Display checkbox input field for whether or not a product has been ordered -->
    <label for='Ordered'>On order:</label>
    <input type='hidden' value='off' name='Ordered'> <!-- to solve not being posted if off -->
    <input type='checkbox' name='Ordered' value='$_GET[Ordered]'>
    
    <br>

    <!-- Supplier name text input field -->
    <label for='SupplierName'>Supplier Name:</label>
    <input type='text' name='SupplierName' placeholder='Supplier' value='$_GET[SupplierName]'>
    
    <br>

    <!-- phone number input field of type telephone -->
    <label for='PhoneNumber'>Phone Number:</label>
    <input type='tel' name='PhoneNumber'  pattern='[0-9]{11}' placeholder='07999123456' value='$_GET[PhoneNumber]'>
    
    <br>

    <!-- restock due date input field of type date -->
    <label for='dueDate'>Restock Date:</label>
    <input type='datetime-local' name='dueDate' value='$arrivalDate'>
    
    <br>

    <!-- restock quantity input field of type number -->
    <label for='Quantity due:'>Restock Amount:</label>
    <input type='number' name='restockQuantity' placeholder='0' step='1' value='$arrivalAmount'>
    
    <br>
    <button type='submit'>Done</button>
</form>";

?>
