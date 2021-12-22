<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

// Initiate variables
$transactionID = $_GET["transactionID"];
$companyName = $_SESSION["companyName"];

/*
 * This function returns false if an item is out of stock,
 * and true if all saleItems are in stock
 * $DBconnection stores the connection to the SQL database
 * $companyName is the name of the company of the current user
 * $decuctFromStock is a boolean value to show whether or not you want stock levels to be decreased, where:
 * true = you would like the stock level to be decreased
 * false = you would not like the stock level to be decreased
 */
function updateStockManagementSystem($DBconnection,$companyName)
{
    // initiate variables
    $tableName = "transaction_" . $_GET["transactionID"] . "_" . $_SESSION['companyName'];
    $connection = $DBconnection;
    $transactionID = $_GET["transactionID"];
    $returnValue = true;

    // Select everything contained within the transaction table
    $query = "SELECT * FROM $tableName";
    $result = mysqli_query($connection, $query);

    if ($result != false) {
        $numRows = mysqli_num_rows($result); //returns the number of rows in the table
    }
    else{
        $numRows = 0;
    }

    // Loop through sale items
    for ($i = 0; $i < $numRows; $i++) {
        // fetch a new row from the SQL result
        $row = mysqli_fetch_assoc($result);

        // Get the table name for the current menu
        $menuID = $_SESSION['menuID'];
        $name = "menu_".$companyName."_".$menuID;
        $currentSaleItemName = $row['SaleItemName'];

        // Get the sale item id of the current product from the current menu table
        $query = "SELECT ID FROM $name WHERE saleItemName = '$currentSaleItemName'";
        $queryResult = mysqli_query($connection,$query);
        $rowID = mysqli_fetch_assoc($queryResult);

        // Store the table name for the contents of the current sale item
        $saleItemContentsTableName = "SaleItem_Contents_".$companyName."_".$rowID['ID'];

        // Select all rows for the contents of the current sale item
        $query = "SELECT * FROM $saleItemContentsTableName";
        $saleItemContentsResult = mysqli_query($connection, $query);

        // If result is false, number of rows = 0
        if($saleItemContentsResult == false){
            $numberOfRows = 0;
        }
        else{
            $numberOfRows = mysqli_num_rows($saleItemContentsResult);
        }

        // Loop through each product of a sale item
        for ($k = 0; $k < $numberOfRows; $k++) {
            $saleItemContentsRow = mysqli_fetch_assoc($saleItemContentsResult);

            // Select product name and current stock value from the stockmanagement table for the current product.
            // Stores result in $productQueryResult
            $stockManagementTableName = "stockmanagementtable_$companyName";
            $productName = $saleItemContentsRow['Product'];
            $query = "SELECT ProductName,CurrentStockValue FROM $stockManagementTableName
                      WHERE ProductName = '$productName'";
            $productQueryResult = mysqli_query($connection, $query);
            $productRow = mysqli_fetch_assoc($productQueryResult);

            // Produce an error if more than one result is returned
            // Produce an error if no results are returned
            if (mysqli_num_rows($productQueryResult) == 1) {

                // Checks if the stock management system has a great enough stock quantity of the product
                if ($productRow['CurrentStockValue'] - ($saleItemContentsRow['Quantity'] * $row['Quantity']) < 0) {

                    $returnValue = false;
                }

                // Calculate new stock value of product
                $productQuantity = $saleItemContentsRow['Quantity'];

                $query = "SELECT CurrentStockValue FROM $stockManagementTableName WHERE ProductName = '$productName'";
                $currentStockValueQueryResult = mysqli_query($connection,$query); // Run SQL query

                $currentStockValueQueryRow = mysqli_fetch_assoc($currentStockValueQueryResult);
                $newStockQuantity = $currentStockValueQueryRow['CurrentStockValue'] - ($productQuantity * $row['Quantity']);

                // Deducts from the stock management system
                $query = "UPDATE $stockManagementTableName 
                          SET CurrentStockValue = '$newStockQuantity'
                          WHERE ProductName = '$productName'";
                mysqli_query($connection, $query);

            } else if (mysqli_num_rows($productQueryResult) == 0) {
                echo "ERROR! A product was missing from your stock management database";
            } else {
                echo "ERROR! Duplicated product names! Remove duplicates from your stock management database";
            }

        }

    }

    /*
     *  If a product is not in stock, all products quantities should be added back to the stock management
     *  database, as the transaction will not be completed.
     *
     * This section could be severely simplified, probably by using a while loop and a selection statement
     * in the previous section
     */
    if ($returnValue == false) {

        // Select everything contained within the transaction table
        $query = "SELECT * FROM $tableName";
        $result = mysqli_query($connection, $query);

        // Loop through sale items
        for ($i = 0; $i < $numRows; $i++) {

            // fetch a new row from the SQL result
            $row = mysqli_fetch_assoc($result);

            // Store the table name for the contents of the current sale item
            $saleItemContentsTableName = "SaleItem_Contents_" . $companyName . "_" . $row['SaleItemID'];

            // Select all rows for the contents of the current sale item
            $query = "SELECT * FROM $saleItemContentsTableName";
            $saleItemContentsResult = mysqli_query($connection, $query);

            // Loop through each product of a sale item
            for ($k = 0; $k < mysqli_num_rows($saleItemContentsResult); $k++) {
                $saleItemContentsRow = mysqli_fetch_assoc($saleItemContentsResult);

                // Select product name and current stock value from the stockmanagement table for the current product.
                // Stores result in $productQueryResult
                $stockManagementTableName = "stockmanagementtable_$companyName";
                $productName = $saleItemContentsRow['Product'];
                $query = "SELECT ProductName,CurrentStockValue FROM $stockManagementTableName
                          WHERE ProductName = '$productName'";
                $productQueryResult = mysqli_query($connection, $query);

                // Produce an error if more than one result is returned
                // Produce an error if no results are returned
                if ($productQueryResult == false) {
                    echo "ERROR! A product was missing from your stock management database";
                }
                else if (mysqli_num_rows($productQueryResult) == 1) {

                    // Calculate new stock value of product
                    $productQuantity = $saleItemContentsRow['Quantity'];

                    $query = "SELECT CurrentStockValue FROM $stockManagementTableName WHERE ProductName = '$productName'";
                    $currentStockValueQueryResult = mysqli_query($connection,$query); // Run SQL query

                    $currentStockValueQueryRow = mysqli_fetch_assoc($currentStockValueQueryResult);
                    $newStockQuantity = $currentStockValueQueryRow['CurrentStockValue'] + ($productQuantity * $row['Quantity']);

                    // Increases quantities in the stock management system
                    $query = "UPDATE $stockManagementTableName 
                              SET CurrentStockValue = '$newStockQuantity'
                              WHERE ProductName = '$productName'";
                    mysqli_query($connection, $query);

                }
                else {
                    echo "ERROR! Duplicated product names! Remove duplicates from your stock management database";
                }

            }

        }
    }

    /*
     *  End the current transaction
     */
    if ($returnValue == true) {
        /* Set current transaction to complete */
        $transactionTotal = $_SESSION['total'];
        $tableName = "transactionhistory_" . $companyName;
        $query = "UPDATE $tableName SET Complete = 'yes', Total = '$transactionTotal' WHERE TransactionID = '$transactionID'";
        mysqli_query($connection, $query);

        /* Open a new transaction */
        $query = "INSERT INTO transactionhistory_$companyName(Complete) VALUES('no')";
        mysqli_query($connection, $query);

        // Create a new transaction table
        $newTransactionID = $transactionID + 1;
        $newTableName = "transaction_".$newTransactionID."_".$companyName;
        $query = "CREATE TABLE `users`.`$newTableName` ( `SaleItemID` INT(9) NOT NULL AUTO_INCREMENT ,
                `SaleItemName` TEXT NOT NULL , `Cost` DOUBLE NOT NULL , `Quantity` INT NOT NULL ,
                 PRIMARY KEY (`SaleItemID`))";
        mysqli_query($connection,$query);
    }

}

/*
 * MAIN
*/

/* Select everything from the current transaction table */
$tableName = "transaction_" . $_GET["transactionID"] . "_" . $_SESSION['companyName'];
$query = "SELECT * FROM $tableName";
$result = mysqli_query($connection,$query);

/*
 *   Only attempt to update the stock management system
 *   if are sale items contained within the current transaction
*/
if($result != false) {
    if(mysqli_num_rows($result) != 0) {
        updateStockManagementSystem($connection, $companyName);
    }
    else{
        echo "Error! Transaction is empty";
    }
}
else{
    echo "Error! Transaction is empty";
}


header('Refresh: 2; URL=pointOfSale.php');// redirect user
exit;