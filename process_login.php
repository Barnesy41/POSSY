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
$result = mysqli_query($connection, "SELECT Username, Password FROM Credentials
                                 WHERE Username = '$username' AND Password = '$password'");
$numResults = mysqli_num_rows($result);

if($numResults != 0){
    /* Get the values of companyName, email, and phoneNumber. If details are note provided, false is placed into the variables */
    $result = mysqli_query($connection,"SELECT Company FROM Credentials
                                  WHERE Username = '$username'");
    $row = $result->fetch_row();
    $companyName = $row[0] ?? false;

    $result = mysqli_query($connection,"SELECT Email FROM Credentials
                                  WHERE Username = '$username'");
    $email = $result->fetch_row()[0] ?? false;

    $result = mysqli_query($connection,"SELECT Phone FROM Credentials
                                  WHERE Username = '$username'");
    $phoneNumber = $result->fetch_row()[0] ?? false;

    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['companyName'] = $companyName;
    $_SESSION['email'] = $email;
    $_SESSION['phoneNumber'] = $phoneNumber;
}
else{
    echo "this is not your account!";// temp
}

closeConnection($connection);


header('Location: HomePage.php');// redirect to home page
exit;

?>


