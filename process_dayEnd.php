<?php
/* Open DB connection */
include "database_connect.php";
$connection = openConnection();

/* Start the session */
session_start();

/* Select all from company transaction history */
$companyName = $_SESSION['companyName'];
$query = "SELECT * FROM transactionhistory_$companyName";
$queryResult = mysqli_query($connection, $query);

/* Calculate number of rows returned by query */
if($queryResult == false){ // If no rows are returned by function
    $numRows = 0;
}
else{
    $numRows = mysqli_num_rows($queryResult); // Returns number of rows
}

/* Delete all transaction tables */
for ($i=0; $i<$numRows; $i++) {
    $rowResult = mysqli_fetch_assoc($queryResult);
    $transactionNumber = $rowResult['TransactionID']; // Get transaction ID

    /* Delete transaction tables for this company */
    $tableName = "transaction_" . $transactionNumber . "_" . $companyName; // Calculate name of table
    $query = "DROP TABLE `$tableName`"; // Delete the table from DB Query
    mysqli_query($connection, $query);

    /* Delete corresponding row in transaction history table */
    $tableName = "transactionhistory_" . $companyName;
    $query = "DELETE FROM $tableName WHERE TransactionID = '$transactionNumber'";
    mysqli_query($connection, $query);
}


/* Create a new transaction in the transaction history table,
 * and create a new transaction table for the transaction.
 */

/* Insert new transaction table name into transaction history table */
$tableName = "transactionhistory_".$companyName;
$query = "INSERT INTO $tableName(Complete) VALUES('no')";
mysqli_query($connection,$query);

/* Retrieve transaction ID */
$query = "SELECT TransactionID FROM $tableName WHERE Complete = 'no'";
$queryResult = mysqli_query($connection,$query);
$transactionHistoryRow = mysqli_fetch_assoc($queryResult);
$transactionID = $transactionHistoryRow['TransactionID'];

/* Create Initial Transaction Table */
$tableName = "transaction_".$transactionID."_".$companyName;
$query = "CREATE TABLE `users`.`$tableName` ( `SaleItemID` INT(9) NOT NULL AUTO_INCREMENT ,
                `SaleItemName` TEXT NOT NULL , `Cost` DOUBLE NOT NULL , `Quantity` INT NOT NULL ,
                 PRIMARY KEY (`SaleItemID`))";
mysqli_query($connection,$query);



?>