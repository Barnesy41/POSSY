<?php
session_start();

$row = $_SESSION['returnedRows'];
$_SESSION['ProductID'] =  $row['ProductID'];

echo "<head>
        <link rel='stylesheet' href='Styles.css' type='text/css'>
      </head>";

echo "Product Name: ".$row['ProductName']."<br>Minimum Stock Value: ".$row['MinimumStockValue']."<br>".
     "Current Stock Value: ".$row['CurrentStockValue']."<br>Supplier Name: ".$row['SupplierName']."<br>".
     "Phone Number: ".$row['Phone'];

echo " <form action='process_lowStock.php'>
       <button type='submit'>Ordered</button>
       </form> ";

echo "<form class='lowStock' action='pointOfSale.php'>
      <button type='submit' >Ignore</button>
      </form>";
?>