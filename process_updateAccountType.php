<?php
session_start(); //start the current session allowing session variables to be used

/* Open DB connection */
include "database_connect.php";
$connection = openConnection();

/* Retrieve the required user to update their account type using $_GET */
$usernameToUpdateAccountType = $_GET['usernameToUpdate'];

/* Update the retrieved user's account permissions to standard */
$query = "UPDATE Credentials SET accountType = 'standard' WHERE Username = '$usernameToUpdateAccountType'";
mysqli_query($connection, $query);

/* Refresh the user back to the drop-down menu */
header('refresh: 0; URL = dropdown.php'); //redirect the current user
exit;