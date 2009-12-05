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

# Get client data:
$expense_id = $_GET['expense_id'];
$get_expense = mysql_query("SELECT * FROM expenses WHERE expense_id = '$expense_id'");
$show_expense = mysql_fetch_array($get_expense);

# Obtain supplier and payment methods:
$get_expense_categories = mysql_query("SELECT * FROM expense_categories");
$get_suppliers = mysql_query("SELECT * FROM suppliers");
$get_payment_methods = mysql_query("SELECT * FROM payment_methods");

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_expense['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$category_id = $_POST['category_id'];
$supplier_id = $_POST['supplier_id'];
$method_id = $_POST['method_id'];
$amount = $_POST['amount'];
$reference = strtoupper($_POST['reference']);
$date_received = $_POST['date_received'];

$expense_id = $_POST['expense_id'];

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
$doSQL = "UPDATE expenses SET category_id = '$category_id', supplier_id = '$supplier_id', method_id = '$method_id', amount = '$amount', reference = '$reference', date_received = '$date_received', employee_id = '$employee_id' WHERE expense_id = '$expense_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# If the size of the file is greater than zero (0) process:
if($_FILES['file']['size'] > 0) {

$name = addslashes($_FILES['file']['name']);
$temp_name  = $_FILES['file']['tmp_name'];
$size = $_FILES['file']['size'];
$type = $_FILES['file']['type'];

$readfile = fopen($temp_name, 'r');
$content = fread($readfile, filesize($temp_name));
$content = addslashes($content);
fclose($readfile);

# Assign values to a database table:
$doSQL = "UPDATE expenses SET name = '$name', size = '$size', type = '$type', content = '$content' WHERE expense_id = '$expense_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# End if condition:
};

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Expense</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('amount').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/expenses.png" alt="Create Expense" width="16" height="16" /> Update Expense:</h1>
    <p>Record created by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Expense: <?php echo $show_expense['expense_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="create_payment" id="create_payment">
      <table class="fulltable">
        <tr>
          <td class="firstcell">category:</td>
          <td><select name="category_id" class="entrytext" id="campaign_id">
              <?php while($show_expense_category = mysql_fetch_array($get_expense_categories)) { ?>
              <option value="<?php echo $show_expense_category['category_id'] ?>"<?php if($show_expense['category_id'] == $show_expense_category['category_id']) { ?> selected="selected"<?php } ?>><?php echo $show_expense_category['name'] ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">supplier:</td>
          <td><select name="supplier_id" class="entrytext" id="supplier_id">
              <?php while($show_supplier = mysql_fetch_array($get_suppliers)) { ?>
              <option value="<?php echo $show_supplier['supplier_id'] ?>"<?php if($show_expense['supplier_id'] == $show_supplier['supplier_id']) { ?> selected="selected"<?php } ?>><?php echo strtoupper($show_supplier['company_name']) ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">method:</td>
          <td><select name="method_id" class="entrytext" id="method_id">
              <?php while($show_payment_method = mysql_fetch_array($get_payment_methods)) { ?>
              <option value="<?php echo $show_payment_method['method_id'] ?>"<?php if($show_expense['method_id'] == $show_payment_method['method_id']) { ?> selected="selected"<?php } ?>><?php echo $show_payment_method['name'] ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">attachment:<br />
            <a href="download_expense_file.php?expense_id=<?php echo $show_expense['expense_id'] ?>">download current</a></td>
          <td><input name="file" type="file" class="entrytext" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="amount" type="text" class="entrytext" id="amount" value="<?php echo $show_expense['amount'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">reference:</td>
          <td><input name="reference" type="text" class="entrytext" id="reference" value="<?php echo $show_expense['reference'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">date received:</td>
          <td><input name="date_received" type="text" class="entrytext" id="date_received" value="<?php echo date('Y-m-d') ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
          <input name="expense_id" type="hidden" id="expense_id" value="<?php echo $show_expense['expense_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
