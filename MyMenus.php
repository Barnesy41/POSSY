<?php
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="Styles.css" type="text/css"> <!-- link HomePage to style sheet -->
</head>

<html>
<body>

<!-- toolbar -->
<div class="toolbar">
    <a class="current" href="HomePage.php">Home</a>
    <a href="MyMenus.php">MyMenus</a>
    <a style="float: right;" href="Signup.php">Sign up</a>
    <a style="float: right;" href="Login.php">Log-in</a>
</div>

<form action="process_existingMenu.php">
    <div class = "menuButtonContainer">

        <label for="existingMenuName">Existing Menu Name:
        <input type="text" name="existingMenuName" id="existingMenuName" placeholder="Enter pre-existing menu name" required></label>

        <button type="submit" name="submit">Confirm</button>
    </div>
</form>

<form action="process_newMenu.php"> <!-- send entered data into process_myMenu.php for processing -->
    <div class = "newMenuButtonContainer">

        <label for="MenuName">New Menu Name:
        <input type="text" name="MenuName" placeholder="Menu1" required></label>

        <button type="submit" name="submit">+New Menu</button>
    </div>
</form>


</body>
</html>

<?php
session_start();

require_once('functions.php');
isUserAllowedAccessToThisPage($_SESSION['accountType'],"MyMenus.php");
?>
