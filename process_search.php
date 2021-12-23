<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* retrieve variables from session */
$companyName = $_SESSION['companyName'];
$menuID = $_SESSION['menuID'];

/* Get the search value */
$searchValue = $_GET['searchBar'];

/* Search for query in current menu table */
$tableName = "menu_".$companyName."_".$menuID;
$_SESSION['menuQuery'] = "SELECT * FROM $tableName WHERE saleItemName LIKE '%$searchValue%'";

/* Set search to be true when the POS system is next opened */
$_SESSION['shouldSearch'] = true;

header('Refresh: 0; URL=pointOfSale.php');
exit;
