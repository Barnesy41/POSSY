<?php

function isUserAllowedAccessToThisPage($accountType,$fileName){

    /* Allows only owner account types can access the dropdown.php file */
    if($fileName == "dropdown.php" and ($accountType == "unknown" or $accountType == "standard")){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }

    /* Allows only owner account types can access the EditProduct.php file */
    if($fileName == "EditProduct.php" and ($accountType == "unknown" or $accountType == "standard")){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }

    /* Allows only owner account types can access the editSaleItem.php file */
    if($fileName == "editSaleItem.php" and ($accountType == "unknown" or $accountType == "standard")){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }

    /* Allows only owner account types can access the refund.php file */
    if($fileName == "refund.php" and ($accountType == "unknown" or $accountType == "standard")){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }

    /* Allows owner and standard account types to access the MyMenus.php file */
    if($fileName == "MyMenus.php" and $accountType == "unknown"){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }

    /* Allows owner and standard account types to access the stockManagement.php file */
    if($fileName == "stockManagement.php" and $accountType == "unknown"){

        echo "<script>alert('your account type does not permit you to be here!')</script>"; //Outputs alert box message
        header('Refresh: 0; URL=HomePage.php'); //Redirects the user away from the page
        exit;
    }



}
?>