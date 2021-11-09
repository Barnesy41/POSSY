<?php
session_start();
include "database_connect.php";
$connect = openConnection();

$menuName = $_GET["existingMenuName"];
$companyName = $_SESSION['companyName'];

$result = mysqli_query($connect,"SELECT MenuName FROM menus
                                       WHERE CompanyName = '$companyName' AND menuName = '$menuName'");

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