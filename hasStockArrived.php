Has the re-stock of product:
<?php echo "<br>".$_GET['productName']."<br>"; ?>
Arrived? <br>
Quantity Due: <?php echo $_GET['arrivalQuantity']."<br>"; ?>

<style>
.displayInline {
    display: inline-block;
}
</style>

<!-- submit user to processing page if stock has arrived -->
<form action='process_stockArrived.php' class="displayInline">
    <input name='productID' type="hidden" value="<?php echo $_GET['productID']; ?>">
    <input name='currentStockValue' type="hidden" value="<?php echo $_GET['currentStock']; ?>">
    <input name='arrivalQuantity' type="hidden" value="<?php echo $_GET['arrivalQuantity']; ?>">
    <button type='submit' value=''>Stock Has Arrived</button>
</form>

<!-- redirect user to the product edit page for a product if a re-stock has not yet arrived -->
<form action='EditProduct.php' class="displayInline">
    <input name='ProductID' type="hidden" value="<?php echo $_GET['productID']; ?>">
    <input name='TableName' type="hidden" value="<?php echo "stockmanagementtable_".$_SESSION['companyName']; ?>">
    <input name='ProductName' type="hidden" value="<?php echo $_GET['productName']; ?>">
    <input name='CurrentStockValue' type="hidden" value="<?php echo $_GET['currentStock']; ?>">
    <input name='MinimumStockValue' type="hidden" value="<?php echo $_GET['minimumStock']; ?>">
    <input name='Ordered' type="hidden" value="<?php echo $_GET['ordered']; ?>">
    <input name='SupplierName' type="hidden" value="<?php echo $_GET['supplierName']; ?>">
    <input name='PhoneNumber' type="hidden" value="<?php echo $_GET['phoneNumber']; ?>">
    <button type='submit' value=''>Stock Hasn't Arrived</button>
</form>
