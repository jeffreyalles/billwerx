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

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$company_name = $_POST['company_name'];
$tag_line = $_POST['tag_line'];
$work_number = $_POST['work_number'];
$fax_number = $_POST['fax_number'];
$email_address = strtolower($_POST['email_address']);

$markup_percent = $_POST['markup_percent'];
$payment_terms = $_POST['payment_terms'];
$currency_symbol = $_POST['currency_symbol'];
$business_number = $_POST['business_number'];
$tax1_name = $_POST['tax1_name'];
$tax1_percent = $_POST['tax1_percent'];
$tax2_name = $_POST['tax2_name'];
$tax2_percent = $_POST['tax2_percent'];

$records_per_page = $_POST['records_per_page'];

$session_timeout = $_POST['session_timeout'];
$ssl_certificate_html = str_replace('"',"'",$_POST['ssl_certificate_html']);

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

# Assign values to a database table:
$doSQL = "UPDATE company SET company_name = '$company_name', tag_line = '$tag_line', work_number = '$work_number', fax_number = '$fax_number', email_address = '$email_address', markup_percent = '$markup_percent', payment_terms = '$payment_terms', currency_symbol = '$currency_symbol', business_number = '$business_number', tax1_name = '$tax1_name', tax1_percent = '$tax1_percent', tax2_name = '$tax2_name', tax2_percent = '$tax2_percent', records_per_page = '$records_per_page', session_timeout = '$session_timeout', ssl_certificate_html = '$ssl_certificate_html', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_company.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Company</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/company.png" alt="Company" width="16" height="16" /> Update Company:</h1>
    <p>Record updated <?php echo $show_company['updated'] ?>.</p>
  </div>
  <div id="navbar">
    <?php include("navbar.php") ?>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="company" id="company">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Contact:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">company name:</td>
                <td><input name="company_name" type="text" class="entrytext" id="company_name" value="<?php echo $show_company['company_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">tag line:</td>
                <td><input name="tag_line" type="text" class="entrytext" id="tag_line" value="<?php echo $show_company['tag_line'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">work number:</td>
                <td><input name="work_number" type="text" class="entrytext" id="work_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_company['work_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">fax number:</td>
                <td><input name="fax_number" type="text" class="entrytext" id="fax_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_company['fax_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">e-mail address:</td>
                <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php echo $show_company['email_address'] ?>" /></td>
              </tr>
            </table>
            <h2>Financials:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">markup %:</td>
                <td><input name="markup_percent" type="text" class="entrytext" id="markup_percent" value="<?php echo $show_company['markup_percent'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">payment terms:</td>
                <td><input name="payment_terms" type="text" class="entrytext" id="payment_terms" value="<?php echo $show_company['payment_terms'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">currency symbol:</td>
                <td><input name="currency_symbol" type="text" class="entrytext" id="currency_symbol" value="<?php echo $show_company['currency_symbol'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">business number:</td>
                <td><input name="business_number" type="text" class="entrytext" id="business_number" value="<?php echo $show_company['business_number'] ?>" /></td>
              </tr>
              
              <tr>
                <td class="firstcell">tax 1 name:</td>
                <td><input name="tax1_name" type="text" class="entrytext" id="tax1_name" value="<?php echo $show_company['tax1_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">tax 1 %:</td>
                <td><input name="tax1_percent" type="text" class="entrytext" id="tax1_percent" value="<?php echo $show_company['tax1_percent'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">tax 2 name:</td>
                <td><input name="tax2_name" type="text" class="entrytext" id="tax2_name" value="<?php echo $show_company['tax2_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">tax 2 %:</td>
                <td><input name="tax2_percent" type="text" class="entrytext" id="tax2_percent" value="<?php echo $show_company['tax2_percent'] ?>" /></td>
              </tr>
            </table>
            <h2>Display:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">records per page:</td>
                <td><input name="records_per_page" type="text" class="entrytext" id="records_per_page" value="<?php echo $show_company['records_per_page'] ?>" /></td>
              </tr>
            </table>
            </td>
          <td class="halftopcell"><h2>Billing:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:<br />
                  <a href="javascript:copyShipping()">copy shipping information</a></td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" value="<?php echo $show_company['billing_address'] ?>" /></td>
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
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" value="<?php echo $show_company['billing_postal'] ?>" /></td>
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
                <td><input name="shipping_address" type="text" class="entrytext" id="shipping_address" value="<?php echo $show_company['shipping_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping city:</td>
                <td><input name="shipping_city" type="text" class="entrytext" id="shipping_city" value="<?php echo $show_company['shipping_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping province:</td>
                <td><input name="shipping_province" type="text" class="entrytext" id="shipping_province" value="<?php echo $show_company['shipping_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping postal:</td>
                <td><input name="shipping_postal" type="text" class="entrytext" id="shipping_postal" value="<?php echo $show_company['shipping_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping country:</td>
                <td><input name="shipping_country" type="text" class="entrytext" id="shipping_country" value="<?php echo $show_company['shipping_country'] ?>" /></td>
              </tr>
            </table>
            <h2>Security:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">session timeout:</td>
                <td><input name="session_timeout" type="text" class="entrytext" id="session_timeout" value="<?php echo $show_company['session_timeout'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">ssl certificate html:</td>
                <td><input name="ssl_certificate_html" type="text" class="entrytext" id="ssl_certificate_html" value="<?php echo $show_company['ssl_certificate_html'] ?>" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><a href="javascript:openWindow('company_messages.php')"><img src="../images/icons/note.png" alt="Messages" class="iconspacer" /></a> <a href="javascript:window.location='company_files.php'"><img src="../images/icons/files.png" alt="Files" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('company_logo.php')"><img src="../images/icons/logo.png" alt="Logo" class="iconspacer" /></a></td>
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
