<?php
session_start(); // start the current session

$row = $_SESSION['returnedRows'];
$_SESSION['ProductID'] =  $row['ProductID']; // get the id of the current product

echo "<head>
        <link rel='stylesheet' href='Styles.css' type='text/css'>
      </head>";

/* Output data regarding product & supplier */
echo "Product Name: ".$row['ProductName']."<br>Minimum Stock Value: ".$row['MinimumStockValue']."<br>".
     "Current Stock Value: ".$row['CurrentStockValue']."<br>Supplier Name: ".$row['SupplierName']."<br>".
     "Phone Number: ".$row['Phone'];

/* Output:
 * Time & Date due input box
 * Amount due input box
 * Ordered form submit button
*/
echo " 
    <form style='display: inline' action='process_lowStock.php'>
    <br><br><label>Ordered Amount: <input type='number' name='orderAmount' step='1' value='0'></label><br>
    <label>Due Date & Time: <input type='datetime-local' name='dateTime'></label><br>
    <button type='submit'>Ordered</button> <!-- Output submit button -->
    </form>
    ";

/* Output ignore submit button */
echo "<form style='display: inline'  class='lowStock' action='pointOfSale.php'>
      <button type='submit' >Ignore</button>
      </form>";

?>