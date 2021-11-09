<?php
session_start();

include "database_connect.php";
$connection = openConnection();

$query = "UPDATE ".$_SESSION['TableName']." SET Ordered = 'on' WHERE ProductID = ".$_SESSION['ProductID']."";
mysqli_query($connection,$query);
?>