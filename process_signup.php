<?php
/* Open Database Connection */
include "database_connect.php";
$connection = openConnection();

/* VALIDATE EMAIL */
/* If valid returns true */
/* If invalid returns false */
function validateEmail($email){
    $valid = false;

    /* search through every character in an email until @ is found */
    $count = 0;
    while ($count < strlen($email) AND $email[$count] != "@"){
        $count += 1;
    }
    if($email[$count] == "@") {
        $secondHalfOfEmail = substr($email, $count + 1, strlen($email) - $count);

        /* Search for dot (.) in second half of email */
        $count = 0;

        while ($count < strlen($secondHalfOfEmail) AND $secondHalfOfEmail[$count] != "."){
            $count += 1;
        }

        /* Check that count is not greater than the last index of the array of the string $secondHalfOfEmail */
        if($count != strlen($secondHalfOfEmail)) {

            if ($secondHalfOfEmail[$count] == ".") {
                $emailAfterDot = substr($secondHalfOfEmail, $count + 1, strlen($secondHalfOfEmail) - $count);

                /* Search for alphabetic character at the end of the email */
                $alphabetArray = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];

                $i = 0;
                while ($i < 26 and $valid == false) {

                    $alphabeticCharacter = $alphabetArray[$i];

                    /* Search for lowercase version */
                    if ($emailAfterDot[strlen($emailAfterDot) - 1] == $alphabeticCharacter) {
                        $valid = true;
                    }
                    /* Search for uppercase version */
                    if ($emailAfterDot[strlen($emailAfterDot) - 1] == strtoupper($alphabeticCharacter)) {
                        $valid = true;
                    }

                    $i += 1;

                }
            }
        }
    }


    return $valid;
}

/* retrieve entered details from form */
$username = $_GET['username'];
$password = $_GET['password'];
$email = $_GET['email'];
$companyName = $_GET['companyName'];
$phoneNumber = $_GET['phone'];

/* Check if username already exists */
$query = "SELECT Username FROM Credentials WHERE Username = '$username'";
$result = mysqli_query($connection, $query);
$numResults = mysqli_num_rows($result);

/* submit details to Credentials table if username is not in use and email is valid*/
if ($numResults == 0 && validateEmail($email) == true) {
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
        /* Calculate Table Name For This Company */
        $tableName = "stockmanagementTable_".$companyName;

        /* Create A Stock Management Table For This Company */
        mysqli_query($connection,
            "CREATE TABLE `users`.`$tableName` ( `ProductID` INT NOT NULL AUTO_INCREMENT ,
                    `ProductName` TEXT NOT NULL , `MinimumStockValue` INT NOT NULL , `CurrentStockValue` INT NOT NULL, 
                    `Ordered` TEXT NOT NULL DEFAULT 'off' ,`SupplierName` TEXT NULL DEFAULT NULL , `Phone` INT(11) NULL,
                     PRIMARY KEY (`ProductID`)); ");

        /* Insert the Table Name and Company Name into the Stock Management Hub table */
        $query = "INSERT INTO stockmanagementhub(TableName,CompanyName)
                  VALUES('$tableName', '$companyName')";
        mysqli_query($connection,$query);

        /* Create A Transaction History Table For This Company */
        $query = "CREATE TABLE `users`.`transactionhistory_$companyName` ( 
                  `TransactionID` INT(9) NOT NULL AUTO_INCREMENT , `Complete` TEXT NOT NULL DEFAULT 'no' , 
                  PRIMARY KEY (`TransactionID`))";
        mysqli_query($connection,$query);

        /* Create Initial Transaction Table */
        $query = "CREATE TABLE `users`.`transaction_1_$companyName` ( `SaleItemID` INT(9) NOT NULL ,
                `SaleItemName` TEXT NOT NULL , `Cost` DOUBLE NOT NULL , `Quantity` INT NOT NULL ,
                 PRIMARY KEY (`SaleItemID`))";
        mysqli_query($connection,$query);

        $query = "INSERT INTO transactionhistory_$companyName(Complete)
                  VALUES('no')";
        mysqli_query($connection,$query);

    }

    echo "Username Created";// temp
}
else{
    /* Output error messages */
    if($numResults != 0 ) {
        echo "Username already in use\n";// temp
    }
    else{
        echo "Email is invalid\n";// temp
    }
}

// header('Location: HomePage.php');// redirect to home page
// exit;
?>

