<?php
session_start();
include "database_connect.php";
$connect = openConnection();

$menuName = $_GET["existingMenuName"];
$companyName = $_SESSION['companyName'];

$_SESSION['menuName'] = $menuName;

$query = "SELECT MenuName FROM menus WHERE CompanyName = '$companyName' AND menuName = '$menuName'";
$result = mysqli_query($connect,$query);

if (mysqli_num_rows($result) !=0){

    header('Location: pointOfSale.php');// redirect to point-of-sale system
    exit;
}
else{
    header('Location: MyMenus.php');// redirect back to MyMenus page
    //display an alert
    exit;
}
?>