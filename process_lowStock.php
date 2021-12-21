<?php
session_start();

include "database_connect.php";
$connection = openConnection();

// Calculate table name
$companyName = $_SESSION['companyName'];
$tableName = "stockmanagementtable_".$companyName;

$query = "UPDATE ".$tableName." SET Ordered = 'on' WHERE ProductID = ".$_SESSION['ProductID']."";
echo $query;
mysqli_query($connection,$query);

header('Location: pointOfSale.php');// redirect user
exit;
?>