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

# Get client data:
$employee_id = $_GET['employee_id'];
$get_employee = mysql_query("SELECT * FROM employees WHERE employee_id = '$employee_id'");
$show_employee = mysql_fetch_array($get_employee);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$home_number = $_POST['home_number'];
$work_number = $_POST['work_number'];
$mobile_number = $_POST['mobile_number'];
$pager_number = $_POST['pager_number'];
$fax_number = $_POST['fax_number'];
$email_address = strtolower($_POST['email_address']);
$account_password = $_POST['account_password'];

$hourly_rate = $_POST['hourly_rate'];

$access_level = $_POST['access_level'];

$billing_address = $_POST['billing_address'];
$billing_city = $_POST['billing_city'];
$billing_province = $_POST['billing_province'];
$billing_postal = $_POST['billing_postal'];
$billing_country = $_POST['billing_country'];

$employee_id = $_POST['employee_id'];

# Assign values to a database table:
$doSQL = "UPDATE employees SET first_name = '$first_name', last_name = '$last_name', home_number = '$home_number', work_number = '$work_number', mobile_number = '$mobile_number', pager_number = '$pager_number', fax_number = '$fax_number', email_address = '$email_address', account_password = '$account_password', hourly_rate = '$hourly_rate', access_level = '$access_level', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country' WHERE employee_id = '$employee_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_employee.php?employee_id=$employee_id");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?>- Update Employee</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/employees.png" alt="Update Employee" width="16" height="16" /> Update Employee: <?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></h1>
    <p>Record created <?php echo $show_employee['created'] ?>.</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="update_employee" name="update_employee" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Contact:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">first name:</td>
                <td><input name="first_name" type="text" class="entrytext" id="first_name" value="<?php echo $show_employee['first_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">last name:</td>
                <td><input name="last_name" type="text" class="entrytext" id="last_name" value="<?php echo $show_employee['last_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">home number:</td>
                <td><input name="home_number" type="text" class="entrytext" id="home_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_employee['home_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">work number:</td>
                <td><input name="work_number" type="text" class="entrytext" id="work_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_employee['work_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">mobile number:</td>
                <td><input name="mobile_number" type="text" class="entrytext" id="mobile_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_employee['mobile_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">pager number:</td>
                <td><input name="pager_number" type="text" class="entrytext" id="pager_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_employee['pager_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">fax number:</td>
                <td><input name="fax_number" type="text" class="entrytext" id="fax_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_employee['fax_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">e-mail address:</td>
                <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php echo $show_employee['email_address'] ?>" />
                  <a href="mailto:<?php echo $show_employee['email_address'] ?>"><img src="../images/icons/email.png" alt="E-mail" width="16" height="16" class="iconspacer" /></a></td>
              </tr>
              <tr>
                <td class="firstcell">account password:</td>
                <td><input name="account_password" type="password" class="entrytext" id="account_password" value="<?php echo $show_employee['account_password'] ?>" /></td>
              </tr>
            </table>
            <h2>Financials: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">hourly rate:</td>
                <td><input name="hourly_rate" type="text" class="entrytext" id="hourly_rate" value="<?php echo $show_employee['hourly_rate'] ?>" /></td>
              </tr>
            </table></td>
          <td class="halftopcell"><h2>Specifics:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">access level:</td>
                <td><select name="access_level" class="entrytext" id="access_level">
                    <option value="0" <?php if ($show_employee['access_level'] == 0) { ?>selected="selected"<?php }?>>0 - Revoked</option>
                    <option value="1" <?php if ($show_employee['access_level'] == 1) { ?>selected="selected"<?php }?>>1 - Employee</option>
                    <option value="2" <?php if ($show_employee['access_level'] == 2) { ?>selected="selected"<?php }?>>2 - Management</option>
                    <option value="3" <?php if ($show_employee['access_level'] == 3) { ?>selected="selected"<?php }?>>3 - Administration</option>
                  </select></td>
              </tr>
            </table>
            <h2>Billing:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:</td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" value="<?php echo $show_employee['billing_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing city:</td>
                <td><input name="billing_city" type="text" class="entrytext" id="billing_city" value="<?php echo $show_employee['billing_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing province:</td>
                <td><input name="billing_province" type="text" class="entrytext" id="billing_province" value="<?php echo $show_employee['billing_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing postal:</td>
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" value="<?php echo $show_employee['billing_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing country:</td>
                <td><input name="billing_country" type="text" class="entrytext" id="billing_country" value="<?php echo $show_employee['billing_country'] ?>" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><a href="javascript:openWindow('print_employee_billing.php?employee_id=<?php echo $show_employee['employee_id'] ?>')"><img src="../images/icons/shipping_label.png" alt="Billing Label" width="16" height="16" class="iconspacer" /></a> <a href="export_employee_vcard.php?employee_id=<?php echo $show_employee['employee_id'] ?>"><img src="../images/icons/vcard.png" alt="Export VCard" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('employee_access_logs.php?employee_id=<?php echo $show_employee['employee_id'] ?>')"><img src="../images/icons/login.png" alt="Employee Access Logs" class="iconspacer" /></a></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="employee_id" type="hidden" id="employee_id" value="<?php echo $show_employee['employee_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
