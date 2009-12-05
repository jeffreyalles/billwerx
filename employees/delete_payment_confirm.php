<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

// Connect to file that makes MySQL work:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$payment_id = $_GET['payment_id'];
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

$get_payment_method = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "");
$show_payment_method = mysql_fetch_array($get_payment_method);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Confirm Delete Payment</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>
<body onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/payments.png" alt="Delete Payment" width="16" height="16" /> Confirm Delete Payment:</h1>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><h1>Warning:</h1>
          <p>Do you want to remove a <?php echo $show_payment_method['name'] ?> payment of <?php echo $show_company['currency_symbol'] ?><?php echo $show_payment['amount'] ?> for invoice #: <?php echo $show_payment['invoice_id'] ?>?</p>
          <p>Once this action is completed it cannot be undone.</p></td>
        </tr>
        <tr>
          <td><input name="delete" type="button" class="button" id="delete" value="DELETE" onclick="window.location='delete_payment.php?payment_id=<?php echo $show_payment['payment_id'] ?>'"/>
            <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
