<?php

# Connect to file that makes MySQL work:
include("inc/dbconfig.php");

# Include security POST loop:
include("global/make_safe.php");

# Ensure license file still exists:
$license_file = 'inc/license.php';

if(!file_exists($license_file)) {
header("Location: ../missing_license.php");
}

# Connect to file that makes MySQL work:
include("inc/license.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Enable SSL:
if(($_SERVER['SERVER_PORT'] != '443') and (!empty($show_company['ssl_certificate_html']))) {
header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); exit();
}

# Process form when $_POST data is found for the specified form:
if((isset($_POST['login'])) and (!empty($_POST['account_password']))) {

# Start session:
session_start();
session_name($_SERVER['SERVER_NAME']);

# Define variables from user_login.php form:
$email_address = $_POST['email_address'];
$account_password = $_POST['account_password'];

# Define access log variables:
$ipv4_address = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

# Store email address as cookie:
setcookie("billwerx", $email_address, time()+(60*60*24*365));

# Query the client database table:
$get_clients = mysql_query("SELECT * FROM clients WHERE email_address = '$email_address' OR billing_email_address = '$email_address'");
$show_client = mysql_fetch_array($get_clients);

# Query the employees database table:
$get_employees = mysql_query("SELECT * FROM employees WHERE email_address = '$email_address'");
$show_employee = mysql_fetch_array($get_employees);

# Forward if email and password match employee table:
if($show_client['account_password'] == $account_password) {
$_SESSION['client_id'] = $show_client['client_id'];
$_SESSION['client_first_name'] = $show_client['first_name'];
$_SESSION['client_last_name'] = $show_client['last_name'];
$doSQL = "INSERT INTO client_access_logs (client_id, ipv4_address, hostname) VALUES ('$_SESSION[client_id]', '$ipv4_address', '$hostname')";
mysql_query($doSQL) or die(mysql_error());
header("Location: clients/index.php");
exit;
};

# Forward if email and password match employee table:
if($show_employee['account_password'] == $account_password) {
$_SESSION['access_level'] = $show_employee['access_level'];
$_SESSION['employee_id'] = $show_employee['employee_id'];
$_SESSION['employee_first_name'] = $show_employee['first_name'];
$_SESSION['employee_last_name'] = $show_employee['last_name'];
$_SESSION['records_per_page'] = $show_employee['records_per_page'];
$doSQL = "INSERT INTO employee_access_logs (employee_id, ipv4_address, hostname) VALUES ('$_SESSION[employee_id]', '$ipv4_address', '$hostname')";
mysql_query($doSQL) or die(mysql_error());
header("Location: employees/index.php");
exit;
};

# Return bad username or e-mail address:
header("Location: unauthorized.php");
exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?>- Login</title>
<link href="billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('account_password').focus()">
<div id="floatingdiv"><span class="justitalic"><?php echo nl2br($show_company_message['login_notice']) ?></span>
  <p><a href="javascript:hideDiv('floatingdiv')">Close Window</a></p>
</div>
<script type="text/javascript" src="scripts/float_layer.js"></script>
<div id="smallwrap">
  <div id="header"><img src="global/company_logo.php" alt="<?php echo $show_company['company_name'] ?> - powered by: Billwerx" /></div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">e-mail address:</td>
          <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php if(isset($_COOKIE['billwerx'])) { echo $_COOKIE['billwerx']; } ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">account password:<br />
            <a href="forgot_password.php">forgot password</a></td>
          <td><input name="account_password" type="password" class="entrytext" id="account_password" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="login" type="submit" class="button" id="login" value="LOGIN" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td height="34" class="firstcell"><?php echo $show_company['ssl_certificate_html'] ?></td>
          <td class="topalign"><p><span class="justred">&copy; <?php echo date("Y") ?> <?php echo $show_company['company_name'] ?></span></p>
            <!--You are not permitted to alter this footer in agreement with the Billwerx usage license. -->
            <p><span class="credits"><a href="http://www.billwerx.com/">powered by Billwerx <?php echo $version ?><br />
              -  a free CRM with secure integrated online billing!</a></span></p></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
