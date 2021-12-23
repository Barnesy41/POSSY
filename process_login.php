<?php
session_start();

/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* Get username and password from HTML form */
$username = $_GET['username'];
$password = $_GET['password'];

/* Run SQLi query to see if there is a match in the database to username and password
   If a match is found, a new object of type User is created */
$query = "SELECT Username, Password FROM Credentials WHERE Username = '$username' AND Password = '$password'";
$result = mysqli_query($connection, $query);
$numResults = mysqli_num_rows($result);

if($numResults != 0){
    /* Get the values of companyName, email, and phoneNumber. If details are note provided, false is placed into the variables */
    $query = "SELECT Company FROM Credentials WHERE Username = '$username'";
    $result = mysqli_query($connection,$query);
    $row = $result->fetch_row();
    $companyName = $row[0] ?? false;

    $query = "SELECT Email FROM Credentials WHERE Username = '$username'";
    $result = mysqli_query($connection,$query);
    $email = $result->fetch_row()[0] ?? false;

    $query = "SELECT Phone FROM Credentials WHERE Username = '$username'";
    $result = mysqli_query($connection,$query);
    $phoneNumber = $result->fetch_row()[0] ?? false;

    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['companyName'] = $companyName;
    $_SESSION['email'] = $email;
    $_SESSION['phoneNumber'] = $phoneNumber;
    $_SESSION['shouldSearch'] = false; // set POS search bar as not actively searching

    echo "Account accessed";
}
else{
    echo "this is not your account!";// temp
}

closeConnection($connection);

header('Refresh: 3; URL=HomePage.php');
exit;

?>


