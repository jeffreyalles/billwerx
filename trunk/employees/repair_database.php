<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

// Connect to file that makes MySQL work:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['repair'])) {

mysql_query("REPAIR TABLE `clients`, `client_files`, `client_notes`, `company`, `company_files`, `company_messages`, `credit_cards`, `employees`, `invoices`, `invoice_items`, `items`, `item_categories`, `payments`, `payment_methods`, `suppliers`, `supplier_notes`, `surveys`") or die(mysql_error());

mysql_query("ANALYZE TABLE `clients`, `client_files`, `client_notes`, `company`, `company_files`, `company_messages`, `credit_cards`, `employees`, `invoices`, `invoice_items`, `items`, `item_categories`, `payments`, `payment_methods`, `suppliers`, `supplier_notes`, `surveys`") or die(mysql_error());

mysql_query("OPTIMIZE TABLE `clients`, `client_files`, `client_notes`, `company`, `company_files`, `company_messages`, `credit_cards`, `employees`, `invoices`, `invoice_items`, `items`, `item_categories`, `payments`, `payment_methods`, `suppliers`, `supplier_notes`, `surveys`") or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Repair Tables</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/repair_database.png" alt="Repair Tables" width="16" height="16" /> Repair Database:</h1>
    <p>The tables which store data within your database can be corrupted due to power failures, disk failures, and connection issues. This process will attempt to repair, analyze, and optimize Billwerx database tables.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><h1>Warning:</h1>
          <p>Performing a table repair can result in the loss of data. Only perform a repair on database tables after you have performed a database backup.</p></td>
        </tr>
        <tr>
          <td><input name="repair" type="submit" class="button" id="repair" value="REPAIR" />
              <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
