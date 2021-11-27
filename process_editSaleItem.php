<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

$saleItemToEdit = $_GET['saleItemToEdit'];
$saleItemName = $_GET['saleItemName'];
$itemPrice = $_GET['saleItemPrice'];
$category = $_GET['saleItemCategory'];
$menuTableName = $_SESSION['companyMenuTableName'];

if($saleItemToEdit == '+new'){
    //SQL query creating new entry into the menu
    $query = "INSERT INTO $menuTableName(saleItemName,itemPrice,category)
              VALUES('$saleItemName','$itemPrice','$category')";
    mysqli_query($connection,$query);

    $query = "SELECT ID FROM $menuTableName
              WHERE saleItemName = '$saleItemName' ";
    $result = mysqli_query($connection,$query);

    $row = mysqli_fetch_assoc($result);
    $id = $row['ID'];

    $tablename = "SaleItem_Contents_".$id;

    $query = "CREATE TABLE `users`.`$tablename` ( `ID` INT(9) NOT NULL AUTO_INCREMENT , `Product` TEXT NOT NULL ,
             `Quantity` INT NOT NULL , PRIMARY KEY (`ID`))";
    mysqli_query($connection,$query);



}
else{
    //SQL query updating current entry in the menu
    $query = "UPDATE $menuTableName SET 
              saleItemName = $saleItemName,
              itemPrice = $itemPrice,
              category = $category
              WHERE saleItemName = $saleItemToEdit";
    mysqli_query($connection,$query);
}

header('Location: pointOfSale.php');// redirect user
exit;
?>



