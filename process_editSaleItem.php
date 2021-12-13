<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* Start the session */
session_start();

/* Init Variables */
$saleItemToEdit = $_GET['saleItemToEdit'];
$saleItemName = $_GET['saleItemName'];
$itemPrice = $_GET['saleItemPrice'];
$category = $_GET['saleItemCategory'];
$menuTableName = $_SESSION['companyMenuTableName'];
$companyName = $_SESSION['companyName'];
$productString = $_GET['products'];

function productStringToArray($productString){

    $productArray = []; //An array that stores the name of each product
    $tempString = ""; //A temporary string to store a product name when being initially formed,
                      //to later be inserted into the $productArray

    for ($i=0; $i<strlen($productString); $i++){

        /* Search for a comma */
        if($productString[$i] == ","){

            /* If there is a space before the next comma, remove it from the tempString */
            while($tempString[strlen($tempString)-1] == " "){
                $tempString = substr($tempString,0,strlen($tempString)-1);
            }

            $productArray[] = $tempString; //Append product into array
            $tempString = ""; //Empty string
        }
        /* Do Nothing if a space is found after a comma */
        else if($productString[$i] == " " and $productString[$i - 1] == ","){
            //do nothing - BAD PRACTICE
        }
        else{

            $tempString = $tempString.$productString[$i]; //Append character to $productString
        }
    }
    $productArray[] = $tempString; //Append final product into array

    return $productArray;
}

/* Checks if a floating point number is to 2 decimal places */
/* Returns true if number is to 2 decimal places */
/* Returns false if number is not to 2 decimal places */
function isTwoDecimalPlaces($value){

    /* Calculate the length of $value */
    $value = (string) $value; //cast $value to string
    $lengthValue = strlen($value);

    /* loop through $stringValue until the decimal point is found */
    /* Store the number of decimal places into variable $numberOfPlacesAfterDecimal */
    $decimalPointFound = false;
    $numberOfPlacesAfterDecimal = 0;
    for($i=0; $i<$lengthValue; $i++){
        if ($value[$i] == "."){
            $decimalPointFound = true;
        }
        else if($decimalPointFound == true){
            $numberOfPlacesAfterDecimal += 1;
        }
    }

    /* Return false if number of decimals is not 2 */
    if($numberOfPlacesAfterDecimal != 2){
        return false;
    }
    else{
        return true;
    }
}

if($saleItemToEdit == '+new' AND isTwoDecimalPlaces($itemPrice) == true){

    /* SQL query creating new entry into the menu */
    $query = "INSERT INTO $menuTableName(saleItemName,price,category)
              VALUES('$saleItemName','$itemPrice','$category')";
    mysqli_query($connection,$query);

    /* SQL query selecting the ID of the newly created sale item
       from the company's menu table                             */
    $query = "SELECT ID FROM $menuTableName
              WHERE saleItemName = '$saleItemName' ";
    $result = mysqli_query($connection,$query);

    $row = mysqli_fetch_assoc($result);
    $id = $row['ID'];
    echo "ID:".$id;

    $tablename = "SaleItem_Contents_".$companyName."_".$id;

    $query = "CREATE TABLE `users`.`$tablename` ( `ID` INT(9) NOT NULL AUTO_INCREMENT , `Product` TEXT NOT NULL ,
             `Quantity` INT NOT NULL , PRIMARY KEY (`ID`))";
    mysqli_query($connection,$query);

    $productArray = productStringToArray($productString);

    /* Inserting products into SaleItem_Contents table for the particular sale item */
    $sizeOfProductArray = count($productArray);

    for($i=0; $i<$sizeOfProductArray; $i++){

        $query = "SELECT Product FROM $tablename WHERE Product = '$productArray[$i]'";
        $result = mysqli_query($connection,$query);
        $numResults = mysqli_num_rows($result); //Fetch the number of results the query produced

        /* Insert product into SaleItem_Contents table if product does not already exist in the table */
        if($numResults == 0) {
            $query = "INSERT INTO $tablename(Product,Quantity) VALUES('$productArray[$i]', '1')";
            mysqli_query($connection,$query);

        }
        /* Otherwise, update the quantity of such item */
        else{
            $query = "UPDATE $tablename SET(Quantity = 'Quantity + 1') WHERE Product = $productArray[$i]";
            mysqli_query($connection,$result);

        }
    }


}
else if(isTwoDecimalPlaces($itemPrice) == false){
    echo "Incorrect number of decimal places";
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


?>



