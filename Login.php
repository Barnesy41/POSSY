<?php
?>

<head>
    <link rel="stylesheet" href="Styles.css" type="text/css"> <!-- link Login Page to style sheet -->
</head>

<form action="process_login.php"> <!-- send entered data into process_login.php for processing -->

    <!-- toolbar -->
    <div class="toolbar">
        <a href="HomePage.php">Home</a>
        <a href="MyMenus.php">MyMenus</a>
        <a style="float: right;" href="Signup.php">Sign up</a>
        <a class="current" style="float: right;" href="Login.php">Log-in</a>
    </div>

    <div class ="loginContainer">

        <h1>Log in</h1>

        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Enter Your Username Here" required>

        <br>

        <label for="password">Password</label>
        <input type="text" name="password" placeholder="Enter Your Password Here" required>

        <br>

        <button type="submit">Log in</button>
    </div>

</form>

</body>
</html>