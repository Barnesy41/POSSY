<?php
session_start();

include "database_connect.php";
$connection = openConnection();

// Calculate table name
$companyName = $_SESSION['companyName'];
$tableName = "stockmanagementtable_".$companyName;

/* Fetch dateTime and orderAmount from lowStock.php */
$dateTime = $_GET['dateTime'];
$orderAmount = $_GET['orderAmount'];

/* Validate date & time */
date_default_timezone_set("GMT");
$currentDateLong = date("c");
$currentDateShort = substr($currentDateLong,0,16);

/* Check that the entered date & time is greater than today's date & time*/
if($dateTime > $currentDateShort AND $orderAmount > 0){
    /* Update the SM table */
    $productID = $_SESSION['ProductID'];
    $query = "UPDATE $tableName SET Ordered = 'on', ArrivalDate = '$dateTime', ArrivalAmount = '$orderAmount'
              WHERE ProductID = '$productID'";
    mysqli_query($connection,$query);
}
else{
    echo "error! Invalid input"; // Output error
}

echo $currentDateShort;
echo "<br>".$dateTime;

?>