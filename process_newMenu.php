<?php
session_start();

include "database_connect.php";
$connection = openConnection();

$menuName = $_GET['MenuName'];

$companyName = $_SESSION['companyName'];

mysqli_query($connection, "INSERT INTO menus(MenuName,CompanyName)
                                           VALUES('$menuName','$companyName')");

/* Get the Menu ID */
$result = mysqli_query($connection,"SELECT MenuID FROM menus
          WHERE MenuName = '$menuName' AND CompanyName = '$companyName'");
$row = mysqli_fetch_assoc($result);
$menuID = $row['MenuID'];

/* Create a table to contain the data for the new menu */
$menuName = "menu_".$companyName."_".$menuID;

$query = "CREATE TABLE `users`.`$menuName` ( `ID` INT(9) NOT NULL AUTO_INCREMENT ,
          `saleItemName` TEXT NOT NULL , `price` DECIMAL(10,2) NOT NULL , `category` TEXT NOT NULL ,
           PRIMARY KEY (`ID`))";
mysqli_query($connection, $query);

closeConnection($connection);

/* Redirect user to MyMenus.php */
header('Location: MyMenus.php');
exit;
?>