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

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get company data:
$get_employees = mysql_query("SELECT * FROM employees");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$client_id = $_POST['client_id'];
$purpose = strtoupper($_POST['purpose']);
$employee_id = $_POST['employee_id'];
$tracking_number = $_POST['tracking_number'];
$purchase_order = $_POST['purchase_order'];

# Make MySQL statement:
$doSQL = "INSERT INTO invoices (client_id, purpose, tracking_number, purchase_order, date_created, employee_id) VALUES ('$client_id', '$purpose', '$tracking_number', '$purchase_order', NOW(), '$employee_id')";

mysql_query($doSQL) or die(mysql_error());

// Get INSERT number as this is the invoiceid:
$invoice_id = mysql_insert_id();

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Generate an account password:
$easypay_id = substr(md5(rand().rand()), 0, 5) . $invoice_id;

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

# Get current company assigned tax values:
$tax1_percent = $show_company['tax1_percent'];
$tax2_percent = $show_company['tax2_percent'];

# Assign values to a database table:
$doSQL = "UPDATE invoices SET easypay_id = '$easypay_id', billing_email_address = '$billing_email_address', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', date_due = '$date_due', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country', tax1_percent = '$tax1_percent', tax2_percent = '$tax2_percent' WHERE invoice_id = '$invoice_id'";

mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_invoice.php?invoice_id=$invoice_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Create Invoice</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/auto_suggest.js"></script>
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
    <form id="create_invoice" name="create_invoice" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Required: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">client:</td>
                <td><input name="suggest" type="text" class="entrytext" id="suggest" autocomplete="off" onkeyup="javascript:autosuggest()" />
                  <br />
                  <div id="results"></div></td>
              </tr>
              <tr>
                <td class="firstcell">purpose:</td>
                <td><input name="purpose" type="text" class="entrytext" id="purpose" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><h2>Optional:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">employee:</td>
                <td><select name="employee_id" class="entrytext" id="employee_id">
                    <?php while($show_employee = mysql_fetch_array($get_employees)) { ?>
                    <option value="<?php echo $show_employee['employee_id'] ?>"<?php if($_SESSION['employee_id'] == $show_employee['employee_id']) { ?> selected="selected"<?php } ?>><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td class="firstcell">tracking number:</td>
                <td><input name="tracking_number" type="text" class="entrytext" id="tracking_number" /></td>
              </tr>
              <tr>
                <td class="firstcell">purchase order:</td>
                <td><input name="purchase_order" type="text" class="entrytext" id="purchase_order" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
            <input name="client_id" type="hidden" id="client_id" />
            <input name="suggest_type" type="hidden" id="suggest_type" value="client" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
