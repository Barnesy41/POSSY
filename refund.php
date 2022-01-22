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

<form action = "process_refund.php">
    <label>Refund Amount:<input type="number" step="0.01" value = 0 name="refundAmount"></label>
    <button type="submit" name="submit">Submit</button>
</form>

<?php
require_once('functions.php');
isUserAllowedAccessToThisPage($_SESSION['accountType'],"refund.php");
?>