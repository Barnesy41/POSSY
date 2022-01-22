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
    <a href="pointOfSale.php">POS</a>
    <?php session_start();
    $username = $_SESSION['username']; // get username from login process
    ?>
    <a style="float: right;" class="current" href="dropdown.php"> <?php echo $username; ?></php></a>
</div>

<form action="refund.php">
    <button type="submit" name="refund">Refund</button>
</form>

<form action="process_dayEnd.php">
    <button type="submit" name="endOfDay">End Of Day</button>
</form>

<?php
/* Open DB connection */
include "database_connect.php";
$connection = openConnection();

/* Retrieve company name */
$companyName = $_SESSION['companyName'];

/* Retrieve data from transaction history table where transaction is complete */
$tableName = "transactionhistory_".$companyName; // Calculate table name
$query = "SELECT * FROM $tableName WHERE Complete = 'yes'";
$transactionhistoryResult = mysqli_query($connection,$query);

/* Calculate number of returned rows */
$numRows = 0;
if($transactionhistoryResult == false){
    $numRows = 0;
}
else{
    $numRows = mysqli_num_rows($transactionhistoryResult);
}

/* Loop through each row of the results to the query, adding to the variable total the total cost of each
 * transaction in the transaction history table that is complete
 */
$total = 0;
for ($i=0; $i<$numRows; $i++){
    $tableRow = mysqli_fetch_assoc($transactionhistoryResult);

    $total = $total + $tableRow['Total'];
}

echo "Daily Total: ".$total; // Ouput the daily total

require_once('functions.php');
isUserAllowedAccessToThisPage($_SESSION['accountType'],"dropdown.php");

?>