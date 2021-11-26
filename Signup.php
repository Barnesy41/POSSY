<!DOCTYPE html>
<html>

<?php
/* CONNECT TO LOCAL HOST */
$servername = "localhost";
$username = "username";
$password = "password";

// Create a connection to the local host
$connection = new mysqli($servername, $username, $password);

/* Check the connection
if ($connection->connect_error) {
    die("The connection has failed: " . $connection->connect_error);
}
echo "Connected successfully"; */

?>

<head>
    <link rel="stylesheet" href="Styles.css" type="text/css"> <!-- link Signup Page to style sheet -->
</head>

<!-- toolbar -->
<div class="toolbar">
    <a href="HomePage.php">Home</a>
    <a href="MyMenus.php">MyMenus</a>
    <a class="current" style="float: right;" href="Signup.php">Sign up</a>
    <a style="float: right;" href="Login.php">Log-in</a>
</div>

<form action="process_signup.php"> <!-- send entered data into process_signup.php for processing -->
    <div class ="signup_container">

        <h1>Sign up</h1>

        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Enter Your Username Here" required>

        <br>

        <label for="password">Password</label>
        <input type="text" name="password" placeholder="Enter Your Password Here" required>

        <br>

        <label for="companyName">Company Name</label>
        <input type="text" name="companyName" placeholder="Enter Your Company Name Here" required>

        <br>

        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter Your Email Address Here">

        <br>

        <label for="phone">Phone</label>
        <input type="number" name="phone" placeholder="Enter Your Phone Number Here">

        <br>

        <button type="submit">Sign Up</button>
    </div>

</form>

</body>
</html>
