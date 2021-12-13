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

/* See if product name already exists */
/* Run SQL query searching for all products and compare product names to entered product name */
function isProductInTable($connection,$tableName,$productName){
    $query = "SELECT ProductName FROM $tableName";
    $result = mysqli_query($connection, $query);

    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
        $row = mysqli_fetch_assoc($result);
        if ($row['ProductName'] == $productName) {
            return true;
        }
    }
    return false;
}

/* Checks if a value is greater than  or equal to zero. */
/* Returns true if value is greater than or equal to zero */
/* Returns false if value is less then 0 */
function isGreaterThanOrEqualToZero($value){
    if($value >= 0){
        return true;
    }
    else {
        return false;
    }
}

/* Removes whitespace before first character,
   and after the last character of a string. */
/* Returns the resulting string */
function removeWhitespaceBeforeAndAfterString($string){

    while($string[0] == " "){
        $string = substr($string, 1);
    }
    while ($string[strlen($string)-1] == " "){
        $string = substr($string,0, strlen($string) - 1);
    }

    return $string;
}


function doesContainComma($string){

    $commaFound = false;
    for($i=0; $i<strlen($string); $i++){
        if($string[$i] == ","){
            $commaFound = true;
        }
    }

    return $commaFound;
}

/* If the product is not in the table, insert the product into the stock management database */
if (isProductInTable($connection, $tableName, $productName) == false) {

    if(doesContainComma($productName) == false) {
        $productName = removeWhiteSpaceBeforeAndAfterString($productName);

        if (isGreaterThanOrEqualToZero($minimumStockValue) and isGreaterThanOrEqualToZero($currentStockValue)) {
            $query = "INSERT INTO $tableName(ProductName,MinimumStockValue,CurrentStockValue,Ordered,SupplierName,Phone)
          VALUES('$productName', '$minimumStockValue', '$currentStockValue', '$ordered',
          '$supplierName', '$phoneNumber')";
            mysqli_query($connection, $query);
            echo "Product Successfully Added";
        } else {
            echo "Value Smaller Than Zero Entered, INVALID!";
        }
    }
    else{
        echo "Invalid character: Comma ',' in product name";
    }
}
else{
    echo "Product already exists!";
}

header('Refresh: 2; URL=StockManagement.php');

?>