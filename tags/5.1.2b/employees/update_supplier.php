<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$supplier_id = $_GET['supplier_id'];
$get_supplier = mysql_query("SELECT * FROM suppliers WHERE supplier_id = '$supplier_id'");
$show_supplier = mysql_fetch_array($get_supplier);

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_supplier['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$home_number = $_POST['home_number'];
$work_number = $_POST['work_number'];
$mobile_number = $_POST['mobile_number'];
$fax_number = $_POST['fax_number'];
$email_address = strtolower($_POST['email_address']);

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

$supplier_id = $_POST['supplier_id'];

# Assign values to a database table:
$doSQL = "UPDATE suppliers SET first_name = '$first_name', last_name = '$last_name', company_name = '$company_name', home_number = '$home_number', work_number = '$work_number', mobile_number = '$mobile_number', fax_number = '$fax_number', email_address = '$email_address', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country' WHERE supplier_id = '$supplier_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_supplier.php?supplier_id=$supplier_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Supplier</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/suppliers.png" alt="Update Supplier" width="16" height="16" /> Update Supplier: <?php echo strtoupper($show_supplier['last_name']) ?>, <?php echo $show_supplier['first_name'] ?></h1>
    <p>Record created <?php echo $show_supplier['created'] ?> by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Supplier: <?php echo strtoupper($show_supplier['last_name']) ?>, <?php echo $show_supplier['first_name'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="update_client" name="update_client" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Contact: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">first name:</td>
                <td><input name="first_name" type="text" class="entrytext" id="first_name" value="<?php echo $show_supplier['first_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">last name:</td>
                <td><input name="last_name" type="text" class="entrytext" id="last_name" value="<?php echo $show_supplier['last_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">company name:</td>
                <td><input name="company_name" type="text" class="entrytext" id="company_name" value="<?php echo $show_supplier['company_name'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">home number:</td>
                <td><input name="home_number" type="text" class="entrytext" id="home_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_supplier['home_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">work number:</td>
                <td><input name="work_number" type="text" class="entrytext" id="work_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_supplier['work_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">mobile number:</td>
                <td><input name="mobile_number" type="text" class="entrytext" id="mobile_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_supplier['mobile_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">fax number:</td>
                <td><input name="fax_number" type="text" class="entrytext" id="fax_number" onblur="cleanNumber(this);formatNumber(this)" value="<?php echo $show_supplier['fax_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">e-mail address:</td>
                <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php echo $show_supplier['email_address'] ?>" />
                  <a href="mailto:<?php echo $show_supplier['email_address'] ?>"><img src="../images/icons/email.png" alt="E-mail" width="16" height="16" class="iconspacer" /></a></td>
              </tr>
            </table></td>
          <td class="halftopcell"><h2>Billing: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:<br />
                  <a href="javascript:copyShipping()">copy shipping information</a></td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" value="<?php echo $show_supplier['billing_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing city:</td>
                <td><input name="billing_city" type="text" class="entrytext" id="billing_city" value="<?php echo $show_supplier['billing_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing province:</td>
                <td><input name="billing_province" type="text" class="entrytext" id="billing_province" value="<?php echo $show_supplier['billing_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing postal:</td>
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" value="<?php echo $show_supplier['billing_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing country:</td>
                <td><input name="billing_country" type="text" class="entrytext" id="billing_country" value="<?php echo $show_supplier['billing_country'] ?>" /></td>
              </tr>
            </table>
            <h2>Shipping: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">shipping address:<br />
                  <a href="javascript:copyBilling()">copy billing information</a></td>
                <td><input name="shipping_address" type="text" class="entrytext" id="shipping_address" value="<?php echo $show_supplier['shipping_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping city:</td>
                <td><input name="shipping_city" type="text" class="entrytext" id="shipping_city" value="<?php echo $show_supplier['shipping_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping province:</td>
                <td><input name="shipping_province" type="text" class="entrytext" id="shipping_province" value="<?php echo $show_supplier['shipping_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping postal:</td>
                <td><input name="shipping_postal" type="text" class="entrytext" id="shipping_postal" value="<?php echo $show_supplier['shipping_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping country:</td>
                <td><input name="shipping_country" type="text" class="entrytext" id="shipping_country" value="<?php echo $show_supplier['shipping_country'] ?>" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><a href="javascript:window.location='supplier_notes.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>'"><img src="../images/icons/note.png" alt="Notes" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('print_supplier_billing.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>')"><img src="../images/icons/shipping_label.png" alt="Shipping Label" width="16" height="16" class="iconspacer" /></a> <a href="export_supplier_vcard.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>"><img src="../images/icons/vcard.png" alt="Export VCard" width="16" height="16" class="iconspacer" /></a></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
            <input name="supplier_id" type="hidden" id="supplier_id" value="<?php echo $show_supplier['supplier_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
