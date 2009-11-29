<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

if(isset($_POST['message'])) { $message = $_POST['message']; } else { $message = "invoice_created"; };

# Setup query to obtain encrypted credit cards:
$get_company_messages = mysql_query("SELECT $message AS message FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$message = $_POST['message'];
$content = $_POST['content'];

# Make MySQL statement:
$doSQL = "UPDATE company_messages SET $message = '$content'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: company_messages.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Company Messages</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/note.png" alt="Client Files" width="16" height="16" /> Company Messages:</h1>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="company_messages.php">
      <table class="fulltable">
        <tr>
          <td><select name="message" class="entrytext" id="message" onchange="this.form.submit();">
            <option value="invoice_created">E-mail: Invoice Created</option>
            <option value="invoice_overdue">E-mail: Invoice Overdue</option>
            <option value="payment_received">E-mail: Payment Received</option>
            <option value="survey_invite">E-mail: Survey Invite</option>
            <option value="survey_result">E-mail: Survey Result</option>
            <option value="forgot_password">E-mail: Forgot Password</option>
            <option value="employee_notice">Notice: Employee Login</option>
            <option value="login_notice">Notice: Login</option>
            <option value="client_notice">Notice: Client Login</option>
          </select></td>
        </tr>
      </table>
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="client_files" id="client_files">
      <table class="fulltable">
        <tr>
          <td><textarea name="content" class="entrybox" id="content"><?php echo $show_company_message['message'] ?></textarea></td>
        </tr>
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" />
          <input name="message" type="hidden" id="message" value="<?php echo $message ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
