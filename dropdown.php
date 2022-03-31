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



/* Output users requested to your company */
?>
<br><br><h3> The Following Users Request Standard Permissions To Your Company:</h3>
<?php
$query = "SELECT * FROM Credentials WHERE Company = '$companyName' AND accountType = 'unknown'";
$result = mysqli_query($connection,$query);

/* Calculate the number of rows returned by the query */
$numRowsReturned = 0;
if($result == false){
    $numRowsReturned = 0;
}
else{
    $numRowsReturned = mysqli_num_rows($result);
}

/* Loop through each row of the query result outputting the username and a button to confirm that their account type
   can be updated from unkown to standard, allowing them access to the company's systems with standard permissions. */
for($i = 0; $i<$numRowsReturned; $i++) {
    $resultRow = mysqli_fetch_assoc($result);

    ?>
    <!-- Output a button that when pressed updates the intended user's account type from unkown to standard -->
    <!-- echo $resultRow['Username'] outputs the username of a user with account type unknown and company = the current user's company -->
    <form action="process_updateAccountType.php">
        <button type="submit">Allow User "<?php echo $resultRow['Username'] ?>" Standard Access To Your Company's System</button>
        <!-- Create a hidden input field to post the username of a user to the processing file -->
        <input type="hidden" value="<?php echo $resultRow['Username'] ?>" name="usernameToUpdate">
    </form>

    <?php
}

/* Check that the current account type is allowed on this web page */
require_once('functions.php');
isUserAllowedAccessToThisPage($_SESSION['accountType'],"dropdown.php");

?>