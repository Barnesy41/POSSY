<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* Calculate new stock value */
$priorStockValue = $_GET['currentStockValue'];
$arrivalStockValue = $_GET['arrivalQuantity'];
$newStockValue = $priorStockValue + $arrivalStockValue;

/* Update the values of the product in the company's stock management database */
$tableName = "stockmanagementtable_".$_SESSION['companyName'];
$query = "UPDATE $tableName SET CurrentStockValue = '$newStockValue', Ordered = 'off', ArrivalDate= NULL, ArrivalAmount = NULL";
mysqli_query($connection,$query);

