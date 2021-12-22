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


// echo "Daily Total: $total";