<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$payment_id = $_GET['payment_id'];
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

# Setup SQL query to obtain list of payment methods;
$get_payment_methods = mysql_query("SELECT * FROM payment_methods");

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_payment['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$method_id = $_POST['method_id'];
$amount = $_POST['amount'];
$reference = strtoupper($_POST['reference']);
$date_received = $_POST['date_received'];

$payment_id = $_POST['payment_id'];
$invoice_id = $_POST['invoice_id'];
$original_amount = $_POST['original_amount'];

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

# Assign values to a database table:
$doSQL = "UPDATE payments SET method_id = '$method_id', amount = '$amount', reference = '$reference', date_received = '$date_received', employee_id = '$employee_id' WHERE payment_id = '$payment_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Obtain current invoice values:
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Adjust invoice values to reflect updated payment values:
$received = $show_invoice['received'] - $original_amount + $amount;
$due = $show_invoice['due'] + $original_amount - $amount;

# Update the balance of the invoice table:
$doSQL = "UPDATE invoices SET received = '$received', due = '$due' WHERE invoice_id = '$invoice_id'";

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
$doSQL = "UPDATE payments SET name = '$name', size = '$size', type = '$type', content = '$content' WHERE payment_id = '$payment_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# End if condition:
};

# Return to screen:
header("Location: e-mail_payment_received.php?payment_id=$payment_id");

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Payment</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h2>Update Payment:</h2>
    <h3>Record created by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Payment: <?php echo $show_payment['payment_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</h3>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="create_payment" id="create_payment">
      <table class="fulltable">
        <tr>
          <td class="firstcell">method:</td>
          <td><select name="method_id" class="entrytext" id="method_id">
              <?php while($show_payment_method = mysql_fetch_array($get_payment_methods)) { ?>
              <option value="<?php echo $show_payment_method['method_id'] ?>"<?php if($show_payment['method_id'] == $show_payment_method['method_id']) { ?> selected="selected"<?php } ?>><?php echo $show_payment_method['name'] ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">attachment:<br />
            <a href="download_payment_file.php?payment_id=<?php echo $show_payment['payment_id'] ?>">download current</a></td>
          <td><input name="file" type="file" class="entrytext" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="amount" type="text" class="entrytext" id="amount" value="<?php echo $show_payment['amount'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">reference:</td>
          <td><input name="reference" type="text" class="entrytext" id="reference" value="<?php echo $show_payment['reference'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">date received:</td>
          <td><input name="date_received" type="text" class="entrytext" id="date_received" value="<?php echo $show_payment['date_received'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="payment_id" type="hidden" id="payment_id" value="<?php echo $show_payment['payment_id'] ?>" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_payment['invoice_id'] ?>" />
            <input name="original_amount" type="hidden" id="original_amount" value="<?php echo $show_payment['amount'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
