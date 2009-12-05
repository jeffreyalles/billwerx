<?php

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$invoice_item_id = $_GET['invoice_item_id'];
$get_invoice_item = mysql_query("SELECT * FROM invoice_items WHERE invoice_item_id = '$invoice_item_id'");
$show_invoice_item = mysql_fetch_array($get_invoice_item);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$name = strtoupper($_POST['name']);
$description = $_POST['description'];
$serial_number = strtoupper($_POST['serial_number']);

$invoice_item_id = $_POST['invoice_item_id'];

# Assign values to a database table:
$doSQL = "UPDATE invoice_items SET name = '$name', description = '$description', serial_number = '$serial_number' WHERE invoice_item_id = '$invoice_item_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Invoice Item</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>
<body onload="document.getElementById('serial_number').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h2>Update Invoice Item:</h2>
    <h3>You can serialize the item by providing a serial number.</h3>
  </div>
  <div id="content">
    <form id="update_invoice_item" name="update_invoice_item" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" value="<?php echo $show_invoice_item['name'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">description</td>
          <td><input name="description" type="text" class="entrytext" id="description" value="<?php echo $show_invoice_item['description'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">serial number:</td>
          <td><input name="serial_number" type="text" class="entrytext" id="serial_number" value="<?php echo $show_invoice_item['serial_number'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="invoice_item_id" type="hidden" id="invoice_item_id" value="<?php echo $show_invoice_item['invoice_item_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
