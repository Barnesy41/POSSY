<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="Styles.css" type="text/css"> <!-- link HomePage to style sheet -->
</head>

<html>
<body>

<div class="toolbar">
    <a href="HomePage.php">Home</a>
    <a href="MyMenus.php">MyMenus</a>
    <a href="stockManagement.php">Stock Management</a>
    <a class="current" href="pointOfSale.php">POS</a>
    <!-- Search Bar -->
    <?php
    session_start();

    /* Open Database Connection */
    include "database_connect.php";
    $connection = openConnection();

    /* Search Bar*/
    echo "
    <style>
    .searchBar{
        width: 50%;
        height:50px;
        background-color: #3aff3a;
        border: none;
        outline: none;
        resize: none;
        color: white;
        font-weight: bold;
        font-size: 40px; 
        margin: 0 auto;
        float: left;
        overflow: hidden;
    }
    
    .searchBar:hover{
    background-color: #00ba00;
    }
    
    .searchSubmit{
        width: 10%;
        height: 55px;
        background-color: #3aff3a;
        border: none;
        outline: none;
        resize: none;
        color: white;
        font-weight: bold;
        font-size: 20px;
        text-align: center;
        float: left;
        overflow; hidden:
        
    }
    
    .searchSubmit:hover{
        background-color: #00ba00;
    }
    
    </style>
    <form style='height: 0; width: 0; display: inline'  action='process_search.php'>
    <textarea class='searchBar' name='searchBar' placeholder='Search:'></textarea>
    <button class='searchSubmit' type='submit'>Submit</button>
    </form>
    
    ";

    /* Check for re-stock having arrived */
    $tableName = "stockmanagementtable_".$_SESSION['companyName'];
    $query = "SELECT * FROM $tableName";
    $stockManagementTableResult = mysqli_query($connection,$query);

    /* Calculate the number of rows returned by query */
    $numRowsReturnedByQuery = 0;
    if ($stockManagementTableResult == false){
        $numRowsReturnedByQuery = 0;
    }
    else{
        $numRowsReturnedByQuery = mysqli_num_rows($stockManagementTableResult);
    }

    /* Calculate current date & time */
    date_default_timezone_set("GMT");
    $currentDateLong = date("c");
    $currentDateShort = substr($currentDateLong, 0, 16);
    $currentDateShort = str_replace("T"," ","$currentDateShort"); /* remove the letter T from the
                                                                                         string and replace with a space
                                                                                         character */

    /* Search through all rows returned by the query, with each result checking whether stock has been ordered
       and if it has been ordered, whether the arrival date of new stock is less than or equal to the current
       date and time */
    for($i=0; $i<$numRowsReturnedByQuery; $i++) {
        /* Fetch new row of the query result */
        $row = mysqli_fetch_assoc($stockManagementTableResult);

        /* Fetch arrival date and time from the current row */
        $arrivalDate = $row['ArrivalDate'];
        $arrivalDate = substr($arrivalDate, 0, 16); // shorten arrival date to remove seconds

        /* if date&time is not null, check whether the date and time of stock due to arrive is less than the
           current date and time */
        if (($arrivalDate <= $currentDateShort and $arrivalDate != null)) {

            /* Fetch data from each column of the result into variables */
            $arrivalQuantity = $row['ArrivalAmount'];
            $productID = $row['ProductID'];
            $productName = $row['ProductName'];
            $currentStock = $row['CurrentStockValue'];
            $minimumStock = $row['MinimumStockValue'];
            $ordered = $row['Ordered'];
            $supplierName = $row['SupplierName'];
            $phoneNumber = $row['Phone'];

            /* Calculate redirect URL */
            $redirectURL = "hasStockArrived.php?productID=".$productID."&productName=".$productName.
                           "&arrivalDate=".$arrivalDate."&arrivalQuantity=".$arrivalQuantity.
                           "&currentStock=".$currentStock."&minimumStock=".$minimumStock.
                           "&ordered=".$ordered."&supplierName=".$supplierName."&phoneNumber=".$phoneNumber;

            /* Redirect the user */
            header('refresh: 0; URL = '.$redirectURL);// redirect to home page
            exit;
        }
    }


    ?>

    <?php
    $username = $_SESSION['username']; // get username from login process
    ?>
    <a style="float: right;" href="dropdown.php"> <?php echo $username; ?></php></a>
</div>

<style>
    span.leftGUI{
        display: inline-block;
        float: left;
        width: 20%;
        height: 655px;
        padding-top: 10px;
        border: 3px solid black;
        background-color: #cbcbcb;
    }

    button.newSaleItem{
        display: inline-grid;
        width: 22%;
        height: 15%;
        border: 2px solid black;
        background-color: lime;
        margin: 10px;
    }

    span.midGUI{
        margin: auto;
        display: inline-block;
        width: 71%;
        height: 655px;
        padding-bottom: 5px;
        padding-top: 13px;
        padding-left 10px;
        border-top: 3px solid black;
        border-bottom: 3px solid black;
        background-color: white;
        font-size: 18px; /* Change to zero, testing with above 0 */
    }

    span.rightGUI{
        display: inline-block;
        width: 8%;
        height: 655px;
        padding-top: 10px;
        float:right;
        border: 3px solid black;
        background-color: #0404cb;
        padding-left: 5px;
    }

    button.saleItem{
        display: inline-grid;
        width: 22%;
        height: 15%;
        border: 2px solid black;
        background-color: white;
        margin: 10px;
    }

    button.completeTransaction{
        background-color: red;
        border: none;
        width: 300px;
        height: 50px;
        color: white;
        font-size: larger;
        font-weight: bold;
    }
</style>

<div>

    <!-- Transaction Column -->
    <span class='leftGUI'>
        <?php
            $companyName = $_SESSION['companyName'];

            /* Check for low stock, and trigger an alert if necessary */
            checkForLowStock($connection,$companyName);

            //Set the transactionID to the default transaction if there is not an already existing transactionID
            if($_SESSION['transactionID'] == "") {
                $query = "SELECT TransactionID FROM transactionhistory_$companyName WHERE Complete = 'no'";

                $result = mysqli_query($connection, $query);
                $row = mysqli_fetch_assoc($result);

                $transactionID = $row['TransactionID'];
            }
?>

            <style>
                .displayInline{
                    display: inline-block;
                }

                .displayInlineH2{
                    padding-left: 10%;
                    display: inline-block;
                }
            </style>

            <?php
            /* Check if any transactionID values have been posted */
            if(!empty($_POST)){
                /* Update the transactionID session variable with the posted transactionID */
                $_SESSION['transactionID'] = $_POST['transactionID'];

                /* If there is not a transaction with the chosen ID, open a new transaction with that ID */

                /* Otherwise, if there is an existing transaction but it was closed, output that the chosen
                   transaction number is invalid */
            }
            // Set the transactionID to the required transactionID */
            $transactionID = $_SESSION['transactionID'];

            /* search for a transaction with the current transactionID */
            $transactionHistoryTable = "transactionhistory_".$companyName;
            $query = "SELECT TransactionID FROM $transactionHistoryTable WHERE TransactionID = '$transactionID'";
            $result = mysqli_query($connection,$query);

            /* Calculate the umber of rows returned by the query */
            $numRows = 0;
            if($result == false){
                $numRows = 0;
            }
            else{
                $numRows = mysqli_num_rows($result);
            }

            /* If no transaction ID is returned, create a new transaction with the given transactionID */
            if ($numRows == 0){
                /* Open a new transaction with the provided transactionID */
                $query = "INSERT INTO transactionhistory_$companyName(TransactionID,Complete) VALUES('$transactionID','no')";
                mysqli_query($connection, $query);

                // Create a new transaction table
                $newTransactionID = $transactionID;
                $newTableName = "transaction_".$newTransactionID."_".$companyName;
                $query = "CREATE TABLE `users`.`$newTableName` ( `SaleItemID` INT(9) NOT NULL AUTO_INCREMENT ,
                `SaleItemName` TEXT NOT NULL , `Cost` DOUBLE NOT NULL , `Quantity` INT NOT NULL ,
                 PRIMARY KEY (`SaleItemID`))";
                mysqli_query($connection,$query);
            }

            ?>

            <!-- Outpupt the transaction ID -->
            <h2 class="displayInlineH2" > Transaction ID: </h2>
            <form class="displayInline" method="post">
                <input class="displayInline" type='number' value='<?php echo $transactionID; ?>' step='1' size='3' name="transactionID">
                <button class="displayInline" type='submit'>✓</button>
            </form>

<?php


            //Display the current items on the transaction
            $tableName = "transaction_".$transactionID."_".$companyName;
            $query = "SELECT SaleItemName,Cost,Quantity FROM $tableName";
            $result = mysqli_query($connection,$query);

            $total = 0; // Stores the total for the current transaction

            /* Only runs if the SQL query produces results */
            if($result != false) {

                /* Loops through each row of the results from the SQL table */
                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                    $row = mysqli_fetch_assoc($result);
                    $saleItemName = $row['SaleItemName'];
                    $saleItemCost = "£" . $row['Cost'];
                    $quantity = $row['Quantity'];

                    ?>
                    <p align='center'><?php echo $saleItemName; ?><br>
                    Quantity: <?php echo $quantity; ?> <br>
                    <?php echo $saleItemCost; ?> </p>
                    
                    <style>             
                    .button{
                    margin: 0;
                    left: 50%;
                    position: absolute;
                    border: none;
                    font-weight: bold;
                    color: white;
                    width: 25px;
                    height: 25px;
                    font-size: 16px;
                    }
                    
                    </style>

                    <!-- Increment Button -->
                    <form action='process_saleItem_increment.php'>
                    <button class='button' style='transform: translate(-2200%, -265%);
                    background-color: lime;' type='submit'>+</button>
                    <input type='hidden' value='<?php echo $saleItemName; ?>' name='saleItem'>
                    </form>
                    
                    <!-- Decrement button -->
                    <form action='process_saleItem_decrement.php'>
                    <button class='button' style='transform: translate(-2200%, -165%);
                    background-color: red' type='submit'>-</button>
                    <input type='hidden' value='<?php echo $saleItemName; ?>' name='saleItem'>
                    </form>
                    <?php
                }

                /* Calculate total cost of transaction */
                $query = "SELECT Quantity, Cost FROM $tableName";
                $result = mysqli_query($connection, $query);

                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                    $row = mysqli_fetch_assoc($result);
                    $total += $row['Cost'] * $row['Quantity'];

                }
            }
            /* Output total cost of transaction */
            ?>
            <h3 align='center'>Total: <?php echo $total; ?></h3>
            <?php
            $_SESSION['total'] = $total;

            /*Complete Transaction Button */
            $_SESSION['transactionID'] = $transactionID;
            ?>
            <h3 align='center'><form action='process_completeTransaction.php'>
                  <button class='completeTransaction' type='submit'>Close Transaction</button>
                  <input type='hidden' name='transactionID' value='<?php echo $transactionID; ?>'>
                  </form></h3>

            <?php
            function checkForLowStock($connection,$companyName){
                $tableName = "stockmanagementtable_".$companyName;
                $query = "SELECT ProductID,ProductName,MinimumStockValue,CurrentStockValue,Ordered,SupplierName,Phone 
                      FROM $tableName WHERE CurrentStockValue < MinimumStockValue AND Ordered='off'";
                $result = mysqli_query($connection, $query);

                /* Calculate the umber of rows returned by the query */
                $numRows = 0;
                if($result == false){
                    $numRows = 0;
                }
                else{
                    $numRows = mysqli_num_rows($result);
                }

                if($numRows > 0) {
                    $_SESSION['returnedRows'] = mysqli_fetch_assoc($result);
                    $_SESSION['companyName'] = $companyName;
                    $_SESSION['tableName'] = $tableName;

                    header('Location: lowStock.php');// redirect user
                    exit;

                }
            }
            ?>
    </span>

    <!-- Sale Item Column -->
    <span class = 'midGUI' >
        <?php
        $menuName = $_SESSION['menuName'];

        $query = "SELECT MenuID FROM menus WHERE MenuName = '$menuName' AND CompanyName = '$companyName'";
        $result = mysqli_query($connection, $query);

        $row = mysqli_fetch_assoc($result);
        $menuID = $row['MenuID'];

        $tableName = "menu_".$companyName."_".$menuID;
        $_SESSION['companyMenuTableName'] = $tableName;

        /* Checks if a category has been selected that is not all */
        if($_SESSION['shouldSearch'] == true){
            // Retrieve query from process_search.php
            // only shows research for entered search
            $query = $_SESSION['menuQuery'];
            $_SESSION ['shouldSearch'] = false; // set POS system not to search on next reload
                                                // unless search is re-submitted
        }
        else if(!empty($_GET) && $_GET['category'] != "all"){
            $category = $_GET['category'];
            // Only show results for given category
            $query = "SELECT saleItemName,ID,price FROM $tableName WHERE Category = '$category'";
        }
        else{
            // show results for all categories
            $query = "SELECT saleItemName,ID,price FROM $tableName";
        }
        /* Run query selecting which sale items to display */
        $result = mysqli_query($connection, $query);
        $numRows = mysqli_num_rows($result);

        for($i=0;$i<$numRows; $i++){

            $row = mysqli_fetch_assoc($result);
            $saleItemName = $row['saleItemName'];
            $saleItemID = $row['ID'];
            $displayPrice = "£".$row['price'];
            $price = $row['price'];

            echo "<form action = 'process_buttonClick_saleItemAdd.php'>
                    <input type='hidden' name='transactionID' value='$transactionID'>
                    <input type='hidden' name='saleItemName' value='$saleItemName'> <!-- type= hidden means this is not visable but can send necissary data to processing--->
                    <input type='hidden' name='saleItemID' value='$saleItemID'>
                    <input type='hidden' name='saleItemCost' value='$price'>
                    <button class= 'saleItem' type='submit'>$saleItemName <br> $displayPrice</button>
                    
                  </form>";
        }

        echo "<form action='editSaleItem.php'>
              <button class= 'newSaleItem' type='submit'>+New <br> Sale Item</button></form>";
        ?>
    </span>

    <!-- Category Column -->
    <span class='rightGUI'>
        <style>
            .category{
                background-color: #0404cb;
                border: none;
                width: 100%;
                height: 8%;
                color: white;
                font-weight: bold;
                font-size: 18px;
            }

            .category:hover button{
                background-color: blue;
                height: 50%;
            }



        </style>
        <?php



        /* Create 9 category buttons */

        // Display default tab
        echo "
            <form class='category' action='pointOfSale.php'>
            <input type='hidden' value='all' name='category'>
            <button class='category' type='submit'>All</button>
            </form>";


        /* Search for all categories */
        $tableName = "menu_".$companyName."_".$menuID;
        $query = "SELECT DISTINCT Category FROM $tableName"; // Select only unique values
        $categoryResult = mysqli_query($connection,$query);

        /* Calculate number of rows returned by query */
        $numRows = 0;
        if($categoryResult == false){
            $numRows = 0;
        }
        else{
            $numRows = mysqli_num_rows($categoryResult);
        }

        /* Display category tabs */
        for($i = 0; $i<$numRows; $i++) {

            $categoryRow = mysqli_fetch_assoc($categoryResult); // fetch the next row from results

            $tabName = $categoryRow['Category'];
            echo "
                <form class ='category' action='pointOfSale.php'>
                <input type='hidden' value='$tabName' name='category'>
                <button class='category' type='submit'>$tabName</button>
                </form>
            ";
        }
        ?>

    </span>

</div>


</body>
</html>

