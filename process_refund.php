<?php
/* Open DB connection */
include "database_connect.php";
$connection = openConnection();

/* Start the session */
session_start();

/* Retrieve amount to refund */
$refundAmount = $_GET['refundAmount'];

/* Retrieve companyName */
$companyName = $_SESSION['companyName'];

/* Add refund as a negatively valued transaction to the transaction history table */
$refundAmount = -$refundAmount; // Set refundAmount to be a negative value
$tableName = "transactionhistory_".$companyName;
$query = "INSERT INTO $tableName(Complete,Total) VALUES('yes','$refundAmount')";
mysqli_query($connection,$query);