<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* retrieve entered details from form */
$username = $_GET['username'];
$password = $_GET['password'];
$email = $_GET['email'];
$companyName = $_GET['companyName'];
$phoneNumber = $_GET['phone'];

/* Check if username already exists */
$result = mysqli_query($connection, "SELECT Username FROM Credentials
                                 WHERE Username = '$username'");
$numResults = mysqli_num_rows($result);

/* submit details to Credentials table */
if ($numResults == 0) {
    echo "Username is valid\n";// temp

    if ($phoneNumber == null and $email != null) {
        mysqli_query($connection, "INSERT INTO Credentials (Username, Password, Company, Email) 
            VALUES ('$username', '$password', '$companyName', '$email')");
    } else if ($phoneNumber != null and $email == null) {
        mysqli_query($connection, "INSERT INTO Credentials (Username, Password, Company, Phone) 
            VALUES ('$username', '$password', '$companyName', '$phoneNumber')");
    } else if ($phoneNumber == null and $email == null) {
        mysqli_query($connection, "INSERT INTO Credentials (Username, Password, Company) 
            VALUES ('$username', '$password', '$companyName')");
    } else {
        mysqli_query($connection, "INSERT INTO Credentials (Username, Password, Company, Email, Phone) 
            VALUES ('$username', '$password', '$companyName', '$email', '$phoneNumber')");
    }

    /* Check if stock management table already exists for that company, if not create one. */
    $result = mysqli_query($connection, "SELECT Company FROM Credentials WHERE Company = '$companyName'");
    $numResults = mysqli_num_rows($result);

    if($numResults == 1){
        $tableName = "stockmanagementTable_".$companyName;

        mysqli_query($connection,
            "CREATE TABLE `users`.`$tableName` ( `ProductID` INT NOT NULL AUTO_INCREMENT ,
                    `ProductName` TEXT NOT NULL , `MinimumStockValue` INT NOT NULL , `CurrentStockValue` INT NOT NULL, `Ordered` INT NOT NULL ,
                    `SupplierName` TEXT NULL DEFAULT NULL , `Phone` INT(11) NULL ,
                     PRIMARY KEY (`ProductID`)); ");

        $query = "INSERT INTO stockmanagementhub(TableName,CompanyName)
                  VALUES('$tableName', '$companyName')";
        mysqli_query($connection,$query);

    }

    echo "Username Created";// temp
}
else{
    echo "Username already in use\n";// temp
}

// header('Location: HomePage.php');// redirect to home page
// exit;
?>

