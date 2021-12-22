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
    <?php session_start();
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

            /* Open Database Connection */
            include "database_connect.php";
            $connection = openConnection();

            /* Check for low stock, and trigger an alert if necessary */
            checkForLowStock($connection,$companyName);

            //Open the current transaction
            //And display the transaction number
            $query = "SELECT TransactionID FROM transactionhistory_$companyName WHERE Complete = 'no'";

            $result = mysqli_query($connection,$query);
            $row = mysqli_fetch_assoc($result);

            $transactionID = $row['TransactionID'];

            echo "<h2 align='center'> Transaction ID: ".$transactionID."</h2>";



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

                    echo "
                    <p align='center'>$saleItemName <br>
                    Quantity: $quantity <br>
                    $saleItemCost</p>
                    
                    <style>             
                    .button{
                    margin: 0px;
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
                    <input type='hidden' value='$saleItemName' name='saleItem'>
                    </form>
                    
                    <!-- Decrement button -->
                    <form action='process_saleItem_decrement.php'>
                    <button class='button' style='transform: translate(-2200%, -165%);
                    background-color: red' type='submit'>-</button>
                    <input type='hidden' value='$saleItemName' name='saleItem'>
                    </form>";

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
            echo "<h3 align='center'>Total: $total</h3>";
            $_SESSION['total'] = $total;

            /*Complete Transaction Button */
            $_SESSION['transactionID'] = $transactionID;
            echo "<h3 align='center'><form action='process_completeTransaction.php'>
                  <button class='completeTransaction' type='submit'>Close Transaction</button>
                  <input type='hidden' name='transactionID' value='$transactionID'>
                  </form></h3>";


            function checkForLowStock($connection,$companyName){
                $tableName = "stockmanagementtable_".$companyName;
                $query = "SELECT ProductID,ProductName,MinimumStockValue,CurrentStockValue,Ordered,SupplierName,Phone 
                      FROM $tableName WHERE CurrentStockValue < MinimumStockValue AND Ordered='off'";
                $result = mysqli_query($connection, $query);

                if(mysqli_num_rows($result) > 0) {
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
        if(!empty($_GET) && $_GET['category'] != "all"){
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

