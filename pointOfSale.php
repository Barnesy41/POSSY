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
    <a style="float: right;" href="Signup.php">Sign up</a>
    <a style="float: right;" href="Login.php">Log-in</a>
</div>

<style>
    span.leftGUI{
        display: inline-block;
        float: left;
        width: 20%;
        height: 655px;
        padding-top: 10px;
        border: 1px solid blue;
        background-color: yellow;
    }

    span.saleItem{
        display: inline-grid;
        width: 22%;
        height: 15%;
        border: 1px solid blue;
        background-color: yellow;
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
        border: 1px solid blue;
        background-color: yellow;
        font-size: 18px; /* Cbange to zero, testing with above 0 */
    }

    span.rightGUI{
        display: inline-block;
        width: 8%;
        height: 655px;
        padding-top: 10px;
        float:right;
        border: 1px solid blue;
        background-color: yellow;
    }

    button.saleItem{
        display: inline-grid;
        width: 22%;
        height: 15%;
        border: 1px solid blue;
        background-color: yellow;
        margin: 10px;
    }
</style>

<div>
    <span class='leftGUI'>
        <?php

            session_start();
            $companyName = $_SESSION['companyName'];



            /* Open Database Connection */
            include "database_connect.php";
            $connection = openConnection();

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

            for($i=0;$i<mysqli_num_rows($result);$i++){
                $row = mysqli_fetch_assoc($result);
                $saleItemName = $row['SaleItemName'];
                $saleItemCost = "£".$row['Cost'];
                $quantity = $row['Quantity'];

                echo "<p align='center'>$saleItemName <br> Quantity: $quantity <br> $saleItemCost</p>";
            }
        ?>
    </span>

    <span class = 'midGUI' >
        <?php
        $menuName = $_SESSION['menuName'];

        $query = "SELECT MenuID FROM menus WHERE MenuName = '$menuName' AND CompanyName = '$companyName'";
        $result = mysqli_query($connection, $query);

        $row = mysqli_fetch_assoc($result);
        $menuID = $row['MenuID'];

        $tableName = "menu_".$companyName."_".$menuID;

        $query = "SELECT saleItemName,ID,price FROM $tableName";
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
        ?>
    </span>

    <span class='rightGUI'>
        Test
    </span>

</div>


</body>
</html>
