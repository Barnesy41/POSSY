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
$productID = $_GET['productID'];
$tableName = "stockmanagementtable_".$_SESSION['companyName'];
$query = "UPDATE $tableName SET CurrentStockValue = '$newStockValue', Ordered = 'off', ArrivalDate= NULL, 
          ArrivalAmount = NULL WHERE ProductID = '$productID'";
mysqli_query($connection,$query);

/* Redirect the user */
header('refresh: 0; URL = pointOfSale.php');// redirect to home page
exit;