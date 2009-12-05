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

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_payment['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

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
    <h2>Confirm Delete Item:</h2>
    <h3>Record created by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Payment: <?php echo $show_payment['payment_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</h3>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
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
