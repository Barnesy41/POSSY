<?php
$saleItemToEdit = $_GET['saleItemToEdit'];
$saleItemName = $_GET['saleItemName'];
$itemPrice = $_GET['saleItemPrice'];
$category = $_GET['saleItemCategory'];
$menuTableName = $_SESSION['companyMenuTableName'];

if($saleItemToEdit == '+new'){
    //SQL query creating new entry into the menu
    $query = "INSERT INTO $menuTableName(saleItemName,itemPrice,category)
              VALUES('$saleItemName','$itemPrice','$category')";
    mysqli_query($query);
}
else{
    //SQL query updating current entry in the menu
    $query = "UPDATE $menuTableName SET 
              saleItemName = $saleItemName,
              itemPrice = $itemPrice,
              category = $category
              WHERE saleItemName = $saleItemToEdit";
}

header('Location: pointOfSale.php');// redirect user
exit;
?>



