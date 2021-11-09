

<html>
<form action='process_newProduct.php'>
    <label for='ProductName'>Product Name:</label>
    <input type='text' name='ProductName' placeholder='Carrot' required>
    
    <br>
    
    <label for='CurrentStockValue'>Current Stock:</label>
    <input type='text' name='CurrentStockValue' placeholder='0' required>
    
    <br>
    
    <label for='MinimumStockValue'>Minimum Stock:</label>
    <input type='text' name='MinimumStockValue' placeholder='0' required>
    
    <br>
    
    <label for='Ordered'>On order:</label>
    <input type='hidden' value='off' name='Ordered'> <!-- to solve not being posted if off -->
    <input type='checkbox' name='Ordered' value='on'>
    
    <br>
    
    <label for='SupplierName'>Supplier Name:</label>
    <input type='text' name='SupplierName' placeholder='Supplier'>
    
    <br>
    
    <label for='PhoneNumber'>Phone Number:</label>
    <input type='tel' name='PhoneNumber'  pattern='[0-9]{11}' placeholder='07999123456'>
    
    <br>
    <button type='submit'>Done</button>
</form>