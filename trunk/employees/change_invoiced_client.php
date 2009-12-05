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

$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Define client_id:
$client_id = $show_invoice['client_id'];

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$purpose = strtoupper($_POST['purpose']);
$client_id = $_POST['client_id'];
$invoice_id = $_POST['invoice_id'];

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

$billing_email_address = $show_client['billing_email_address'];

$billing_address = $show_client['billing_address'];
$billing_city = $show_client['billing_city'];
$billing_province = $show_client['billing_province'];
$billing_postal = $show_client['billing_postal'];
$billing_country = $show_client['billing_country'];

# Calulate invoice due date:
$payment_terms = $show_client['payment_terms'];
$date_due = date('Y-m-d', strtotime("+ $payment_terms"));

$shipping_address = $show_client['shipping_address'];
$shipping_city = $show_client['shipping_city'];
$shipping_province = $show_client['shipping_province'];
$shipping_postal = $show_client['shipping_postal'];
$shipping_country = $show_client['shipping_country'];

# Assign values to a database table:
$doSQL = "UPDATE invoices SET client_id = '$client_id', purpose = '$purpose', billing_email_address = '$billing_email_address', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', date_due = '$date_due', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country' WHERE invoice_id = '$invoice_id'";

mysql_query($doSQL) or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Change Invoiced Client</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/auto_suggest.js"></script>
</head>
<body onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/clients.png" alt="Update Invoice Client" width="16" height="16" /> Change Invoiced Client:</h1>
    <p>Can you quickly change the person or company billed for this invoice by selecting another client from the form below.</p>
  </div>
  <div id="content">
    <form id="update_invoice_client" name="update_invoice_client" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="suggest" type="text" class="entrytext" id="suggest" value="<?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?>" autocomplete="off" onclick="this.value=''" onkeyup="javascript:autosuggest()" />
            <br />
            <div id="results"></div></td>
        </tr>
        <tr>
          <td class="firstcell">purpose:</td>
          <td><input name="purpose" type="text" class="entrytext" id="purpose" value="<?php echo $show_invoice['purpose'] ?>" autocomplete="off" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="client_id" type="hidden" id="client_id" />
            <input name="suggest_type" type="hidden" id="suggest_type" value="client" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
