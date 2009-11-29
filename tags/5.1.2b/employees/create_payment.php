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
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

$get_payment_methods = mysql_query("SELECT * FROM payment_methods");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$method_id = $_POST['method_id'];
$amount = $_POST['amount'];
$reference = strtoupper($_POST['reference']);
$date_received = $_POST['date_received'];

$client_id = $_POST['client_id'];

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

$invoice_id = $_POST['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

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
$doSQL = "INSERT INTO payments (client_id, invoice_id, method_id, amount, name, size, type, content, reference, date_received, employee_id) VALUES ('$client_id', '$invoice_id', '$method_id', '$amount', '$name', '$size', '$type', '$content', '$reference', '$date_received' , '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

// Get INSERT number as this is the payment_id:
$payment_id = mysql_insert_id();

# Calculate invoice values:
$received = $show_invoice['received'] + $amount;
$due = $show_invoice['due'] - $amount;

# Update the balance of the invoice table:
$doSQL = "UPDATE invoices SET received = '$received', due = '$due' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

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
<title><?php echo $show_company['company_name'] ?> - Create Payment</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('amount').focus()" onunload="window.opener.location.reload()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/payments.png" alt="Create Payment" width="16" height="16" /> Create Payment:</h1>
    <p>Apply a payment to invoice #: <a href="../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>"><?php echo $show_invoice['invoice_id'] ?></a>.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="create_payment" id="create_payment">
      <table class="fulltable">
        <tr>
          <td class="firstcell">method:</td>
          <td><select name="method_id" class="entrytext" id="method_id">
            <?php while($show_payment_method = mysql_fetch_array($get_payment_methods)) { ?>
            <option value="<?php echo $show_payment_method['method_id'] ?>"><?php echo $show_payment_method['name'] ?> </option>
            <?php } ?>
          </select> <a href="credit_cards.php?client_id=<?php echo $show_invoice['client_id'] ?>"><img src="../images/icons/information.png" alt="Client Details" width="16" height="16" class="iconspacer" /></a></td>
        </tr>
        <tr>
          <td class="firstcell">attachment:</td>
          <td><input name="file" type="file" class="entrytext" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="amount" type="text" class="entrytext" id="amount" value="<?php echo $show_invoice['due'] ?>" /></td>
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
            <input name="client_id" type="hidden" id="client_id" value="<?php echo $show_invoice['client_id'] ?>" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
