<?php
session_start();
$_SESSION['tableName'] = $_GET['TableName'];
$_SESSION['productID'] = $_GET['ProductID'];

echo "<form action='process_editProduct.php'>
    <label for='ProductName'>Product Name:</label>
    <input type='text' name='ProductName' value='$_GET[ProductName]' required>
    
    <br>
    
    <label for='CurrentStockValue'>Current Stock:</label>
    <input type='text' name='CurrentStockValue' value='$_GET[CurrentStockValue]' required>
    
    <br>
    
    <label for='MinimumStockValue'>Product Name:</label>
    <input type='text' name='MinimumStockValue' value='$_GET[MinimumStockValue]' required>
    
    <br>
    
    <label for='Ordered'>On order:</label>
    <input type='hidden' value='off' name='Ordered'> <!-- to solve not being posted if off -->
    <input type='checkbox' name='Ordered' value='$_GET[Ordered]'>
    
    <br>
    
    <label for='SupplierName'>Supplier Name:</label>
    <input type='text' name='SupplierName' placeholder='Supplier' value='$_GET[SupplierName]'>
    
    <br>
    
    <label for='PhoneNumber'>Phone Number:</label>
    <input type='tel' name='PhoneNumber'  pattern='[0-9]{11}' placeholder='07999123456' value='$_GET[PhoneNumber]'>
    
    <br>
    <button type='submit'>Done</button>
</form>";

?>
