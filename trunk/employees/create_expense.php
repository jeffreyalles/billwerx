<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Obtain supplier and payment methods:
$get_expense_categories = mysql_query("SELECT * FROM expense_categories");
$get_suppliers = mysql_query("SELECT * FROM suppliers");
$get_payment_methods = mysql_query("SELECT * FROM payment_methods");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$category_id = $_POST['category_id'];
$supplier_id = $_POST['supplier_id'];
$method_id = $_POST['method_id'];
$amount = $_POST['amount'];
$reference = strtoupper($_POST['reference']);
$date_received = $_POST['date_received'];

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

# Define POST file variables:
$name = addslashes($_FILES['file']['name']);
$temp_name  = $_FILES['file']['tmp_name'];
$size = $_FILES['file']['size'];
$type = $_FILES['file']['type'];

$readfile = fopen($temp_name, 'r');
$content = fread($readfile, filesize($temp_name));
$content = addslashes($content);
fclose($readfile);

# Assign values to a database table:
$doSQL = "INSERT INTO expenses (category_id, supplier_id, method_id, amount, name, size, type, content, reference, date_received, employee_id) VALUES ('$category_id', '$supplier_id', '$method_id', '$amount', '$name', '$size', '$type', '$content', '$reference', '$date_received' , '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Create Expense</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('amount').focus()" onunload="window.opener.location.reload()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/expenses.png" alt="Create Expense" width="16" height="16" /> Create Expense:</h1>
    <p>You must enter a valid e-mail address and password to login.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="create_payment" id="create_payment">
      <table class="fulltable">
        <tr>
          <td class="firstcell">category:</td>
          <td><select name="category_id" class="entrytext" id="category_id">
            <?php while($show_expense_category = mysql_fetch_array($get_expense_categories)) { ?>
            <option value="<?php echo $show_expense_category['category_id'] ?>"><?php echo $show_expense_category['name'] ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="firstcell">supplier:</td>
          <td><select name="supplier_id" class="entrytext" id="supplier_id">
            <?php while($show_supplier = mysql_fetch_array($get_suppliers)) { ?>
            <option value="<?php echo $show_supplier['supplier_id'] ?>"><?php echo strtoupper($show_supplier['company_name']) ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="firstcell">method:</td>
          <td><select name="method_id" class="entrytext" id="method_id">
            <?php while($show_payment_method = mysql_fetch_array($get_payment_methods)) { ?>
            <option value="<?php echo $show_payment_method['method_id'] ?>"><?php echo $show_payment_method['name'] ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="firstcell">attachment:</td>
          <td><input name="file" type="file" class="entrytext" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="amount" type="text" class="entrytext" id="amount" /></td>
        </tr>
        <tr>
          <td class="firstcell">reference:</td>
          <td><input name="reference" type="text" class="entrytext" id="reference" /></td>
        </tr>
        <tr>
          <td class="firstcell">date received:</td>
          <td><input name="date_received" type="text" class="entrytext" id="date_received" value="<?php echo date('Y-m-d') ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
          <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
