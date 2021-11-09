<?php
session_start();

include "database_connect.php";
$connection = openConnection();

$menuName = $_GET['MenuName'];

$companyName = $_SESSION['companyName'];

mysqli_query($connection, "INSERT INTO menus(MenuName,CompanyName)
                                           VALUES('$menuName','$companyName')");

closeConnection($connection);
?>