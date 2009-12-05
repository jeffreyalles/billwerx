<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_campaigns = mysql_query("SELECT * FROM campaigns");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$home_number = $_POST['home_number'];
$work_number = $_POST['work_number'];
$mobile_number = $_POST['mobile_number'];
$fax_number = $_POST['fax_number'];
$primary_number = $_POST['primary_number'];

$email_address = strtolower($_POST['email_address']);

$payment_terms = $_POST['payment_terms'];
$discount = $_POST['discount'];
$billing_email_address = strtolower($_POST['billing_email_address']);

# Generate an account password:
$account_password = substr(md5(rand().rand()), 0, 5);

$campaign_id = $_POST['campaign_id'];

$billing_address = $_POST['billing_address'];
$billing_city = $_POST['billing_city'];
$billing_province = $_POST['billing_province'];
$billing_postal = $_POST['billing_postal'];
$billing_country = $_POST['billing_country'];

$shipping_address = $_POST['shipping_address'];
$shipping_city = $_POST['shipping_city'];
$shipping_province = $_POST['shipping_province'];
$shipping_postal = $_POST['shipping_postal'];
$shipping_country = $_POST['shipping_country'];

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

# Make MySQL statement:
$doSQL = "INSERT INTO clients (first_name, last_name, company_name, home_number, work_number, mobile_number, fax_number, primary_number, email_address, payment_terms, discount, billing_email_address, account_password, campaign_id, billing_address, billing_city, billing_province, billing_postal, billing_country, shipping_address, shipping_city, shipping_province, shipping_postal, shipping_country, employee_id) VALUES ('$first_name', '$last_name', '$company_name', '$home_number', '$work_number', '$mobile_number', '$fax_number', '$primary_number', '$email_address', '$payment_terms', '$discount', '$billing_email_address', '$account_password', '$campaign_id', '$billing_address', '$billing_city', '$billing_province', '$billing_postal', '$billing_country', '$shipping_address', '$shipping_city', '$shipping_province', '$shipping_postal', '$shipping_country', '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

// Get INSERT number as this is the invoiceid:
$client_id = mysql_insert_id();

# Return to screen:
header("Location: update_client.php?client_id=$client_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?>- Create Client</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
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
    <form id="create_client" name="create_client" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Contact:</h2>
          <table class="fulltable">
              <tr>
                <td class="firstcell">first name:</td>
                <td><input name="first_name" type="text" class="entrytext" id="first_name" /></td>
              </tr>
              <tr>
                <td class="firstcell">last name:</td>
                <td><input name="last_name" type="text" class="entrytext" id="last_name" /></td>
              </tr>
              <tr>
                <td class="firstcell">company name:</td>
                <td><input name="company_name" type="text" class="entrytext" id="company_name" /></td>
              </tr>
            </table>
            <table class="fulltable">
              <tr>
                <td class="firstcell">home number:</td>
                <td><input name="home_number" type="text" class="entrytext" id="home_number" onblur="cleanNumber(this);formatNumber(this);setHomePrimary()" /></td>
                <td class="lastcell"><input type="radio" name="get_primary" id="primary_home" /></td>
              </tr>
              <tr>
                <td class="firstcell">work number:</td>
                <td><input name="work_number" type="text" class="entrytext" id="work_number" onblur="cleanNumber(this);formatNumber(this);setWorkPrimary()" /></td>
                <td class="lastcell"><input type="radio" name="get_primary" id="primary_work" /></td>
              </tr>
              <tr>
                <td class="firstcell">mobile number:</td>
                <td><input name="mobile_number" type="text" class="entrytext" id="mobile_number" onblur="cleanNumber(this);formatNumber(this);setMobilePrimary()" /></td>
                <td class="lastcell"><input type="radio" name="get_primary" id="primary_mobile" /></td>
              </tr>
              <tr>
                <td class="firstcell">fax number:</td>
                <td colspan="2"><input name="fax_number" type="text" class="entrytext" id="fax_number3" onblur="cleanNumber(this);formatNumber(this)" /></td>
              </tr>
            </table>
            <table class="fulltable">
              <tr>
                <td class="firstcell">e-mail address:</td>
                <td><input name="email_address" type="text" class="entrytext" id="email_address" /></td>
              </tr>
            </table>
            <h2>Financials:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">payment terms:</td>
                <td><input name="payment_terms" type="text" class="entrytext" id="payment_terms" value="<?php echo $show_company['payment_terms'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">discount:</td>
                <td><input name="discount" type="text" class="entrytext" id="discount" value="0" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing e-mail address:<br />
                  <a href="javascript:copyEmail()">copy e-mail address</a><br /></td>
                <td><input name="billing_email_address" type="text" class="entrytext" id="billing_email_address" /></td>
              </tr>
            </table></td>
          <td class="halftopcell"><h2>Marketing:</h2>
          <table class="fulltable">
              <tr>
                <td class="firstcell">campaign:</td>
                <td><select name="campaign_id" class="entrytext" id="campaign_id">
                    <?php while($show_campaign = mysql_fetch_array($get_campaigns)) { ?>
                    <option value="<?php echo $show_campaign['campaign_id'] ?>"><?php echo $show_campaign['name'] ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
            </table>
            <h2>Billing:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:<br />
                  <a href="javascript:copyShipping()">copy shipping information</a></td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing city:</td>
                <td><input name="billing_city" type="text" class="entrytext" id="billing_city" value="<?php echo $show_company['billing_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing province:</td>
                <td><input name="billing_province" type="text" class="entrytext" id="billing_province" value="<?php echo $show_company['billing_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing postal:</td>
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing country:</td>
                <td><input name="billing_country" type="text" class="entrytext" id="billing_country" value="<?php echo $show_company['billing_country'] ?>" /></td>
              </tr>
            </table>
            <h2>Shipping:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">shipping address:<br />
                  <a href="javascript:copyBilling()">copy billing information</a></td>
                <td><input name="shipping_address" type="text" class="entrytext" id="shipping_address" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping city:</td>
                <td><input name="shipping_city" type="text" class="entrytext" id="shipping_city" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping province:</td>
                <td><input name="shipping_province" type="text" class="entrytext" id="shipping_province" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping postal:</td>
                <td><input name="shipping_postal" type="text" class="entrytext" id="shipping_postal" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping country:</td>
                <td><input name="shipping_country" type="text" class="entrytext" id="shipping_country" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
            <input name="primary_number" type="hidden" id="primary_number" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
