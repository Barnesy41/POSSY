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



?>