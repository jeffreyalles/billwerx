<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup query to obtain encrypted credit cards:
$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$login_notice = $_POST['login_notice'];
$client_notice = $_POST['client_notice'];
$employee_notice = $_POST['employee_notice'];
$invoice_created = $_POST['invoice_created'];
$payment_received = $_POST['payment_received'];
$survey_invite = $_POST['survey_invite'];
$survey_result = $_POST['survey_result'];
$forgot_password = $_POST['forgot_password'];

# Make MySQL statement:
$doSQL = "UPDATE company_messages SET login_notice = '$login_notice', client_notice = '$client_notice', employee_notice = '$employee_notice', invoice_created = '$invoice_created', payment_received = '$payment_received', survey_invite = '$survey_invite', survey_result = '$survey_result', forgot_password = '$forgot_password'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: email_templates.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - E-mail Templates</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header"><img src="../global/company_logo.php" alt="<?php echo $show_company['company_name'] ?> - powered by: Billwerx" /></div>
  <div id="logininfo">
    <?php include_once("login_info.php") ?>
  </div>
  <div id="navbar">
    <?php include_once("navbar.php") ?>
  </div>
  <div id="content">
    <form id="email_templates" name="email_templates" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell">&nbsp;</td>
          <td class="halftopcell">&nbsp;</td>
        </tr>
        <tr>
          <td class="halftopcell"><h2>Login Notice:</h2>
            <textarea name="login_notice" class="entrybox" id="login_notice"><?php echo $show_company_message['login_notice'] ?></textarea></td>
          <td class="halftopcell"><h2>Client Login:</h2>
            <textarea name="client_notice" class="entrybox" id="client_notice"><?php echo $show_company_message['client_notice'] ?></textarea></td>
        </tr>
        <tr>
          <td class="halftopcell"><h2>Employee Notice:</h2>
            <textarea name="employee_notice" class="entrybox" id="employee_notice"><?php echo $show_company_message['employee_notice'] ?></textarea></td>
          <td class="halftopcell"><h2>Invoice Created:</h2>
            <textarea name="invoice_created" class="entrybox" id="invoice_created"><?php echo $show_company_message['invoice_created'] ?></textarea></td>
        </tr>
        <tr>
          <td class="halftopcell"><h2>Payment Received:</h2>
            <textarea name="payment_received" class="entrybox" id="payment_received"><?php echo $show_company_message['payment_received'] ?></textarea></td>
          <td class="halftopcell"><h2>Survey Invite:</h2>
            <textarea name="survey_invite" class="entrybox" id="survey_invite"><?php echo $show_company_message['survey_invite'] ?></textarea></td>
        </tr>
        <tr>
          <td class="halftopcell"><h2>Forgot Password:</h2>
            <textarea name="forgot_password" class="entrybox" id="forgot_password"><?php echo $show_company_message['forgot_password'] ?></textarea></td>
          <td class="halftopcell"><h2>Survey Result:</h2>
            <textarea name="survey_result" class="entrybox" id="survey_result"><?php echo $show_company_message['survey_result'] ?></textarea></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
