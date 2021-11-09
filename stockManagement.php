<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

echo"
<head>
    <link rel='stylesheet' href='Styles.css' type='text/css'> <!-- link HomePage to style sheet -->
</head>

<!-- Toolbar -->
<div class='toolbar'>
    <a href='HomePage.php'>Home</a>
    <a href='MyMenus.php'>MyMenus</a>
    <a href='stockManagement.php' class='current'>Stock Management</a>
    <a href='pointOfSale.php'>POS</a>
    <a style='float: right;' href='Signup.php'>Sign up</a>
    <a style='float: right;' href='Login.php'>Log-in</a>
</div>";

echo "<div class='Container_SM_Overview'>
    <table id='stockManagementOverview'>
        <!-- Set Table Headings -->
        <tr>
            <th>Item Name</th>
            <th>Current Quentity</th>
            <th>Minimum Quantity</th>
            <th><form action='newProduct.php'><button type='submit'>+New Product</button></form></th>
        </tr>
        ".addEntriesToTable(strtolower($_SESSION['companyName']),$connection).

   "</table>
</div>";



/* Calculates the number of rows
   Returns number of rows found in the table
   if now results found, returns 0 */
function getNumRows($companyName, $DBconnection){
    $tableName = "stockmanagementtable_".$companyName;
    $query = "SELECT ProductName FROM $tableName";
    $result = mysqli_query($DBconnection, $query);
    if($result != false) {
        return mysqli_num_rows($result);
    }
    else{
        return 0;
    }
}

function addEntriesToTable($companyName, $connection){
    $tableName = "stockmanagementtable_".$companyName;
    $query = "SELECT ProductID,ProductName,MinimumStockValue,CurrentStockValue,Ordered,SupplierName,Phone 
              FROM $tableName";
    $result = mysqli_query($connection, $query);

    /* Ouput data of each row */
    $ret = "";
    for($i=0;$i<getNumRows($companyName,$connection);$i++){
        $row = mysqli_fetch_assoc($result);
        $ret = $ret.'<tr><td>'.$row["ProductName"].'</td>
                     <td> '.$row["CurrentStockValue"].'</td>
                     <td>'.$row["MinimumStockValue"].'</td>
                     <td><a href="EditProduct.php?ProductID='.$row['ProductID'].'&TableName='.$tableName.
                                 '&ProductName='.$row["ProductName"].'&CurrentStockValue='.$row["CurrentStockValue"].
                                 '&MinimumStockValue='.$row["MinimumStockValue"].'&Ordered='.$row["Ordered"].
                                 '&SupplierName='.$row["SupplierName"].'&PhoneNumber='.$row["Phone"].
                                 '">Edit Product</a></td></tr>';
    }
    return $ret;
}
?>