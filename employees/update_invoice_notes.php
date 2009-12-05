<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

# Get company items:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$notes = $_POST['notes'];
$invoice_id = $_POST['invoice_id'];

# Assign values to a database table:
$doSQL = "UPDATE invoices SET notes = '$notes' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_invoice_notes.php?invoice_id=$invoice_id");

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Invoice Notes</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/files.png" alt="Client Files" width="16" height="16" /> Invoice Notes #: <?php echo $show_invoice['invoice_id'] ?></h1>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="client_files" id="client_files">
      <table class="fulltable">
        <tr>
          <td><textarea name="notes" class="entrybox" id="notes"><?php echo $show_invoice['notes'] ?></textarea></td>
        </tr>
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" />
          <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
