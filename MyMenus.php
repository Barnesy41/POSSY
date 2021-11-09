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
        <input type="text" name="existingMenuName" placeholder="Enter pre-existing menu name">
        <button type="submit">Confirm</button>
    </div>
</form>
<form action="process_newMenu.php"> <!-- send entered data into process_myMenu.php for processing -->
    <div class = "newMenuButtonContainer">

        <input type="text" name="MenuName" placeholder="Menu1" required>
        <button type="submit">+New Menu</button>
    </div>



</body>
</html>
