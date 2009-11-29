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

# Get company items:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Define client_id:
$client_id = $show_invoice['client_id'];

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Get invoice items:
$get_items = mysql_query("SELECT * FROM items");
$get_invoice_items = mysql_query("SELECT * FROM invoice_items WHERE invoice_id = '$invoice_id' ORDER BY invoice_item_id ASC");

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$purpose = strtoupper($_POST['purpose']);
$easypay_id = $_POST['easypay_id'];
$billing_email_address = $_POST['billing_email_address'];

$billing_address = $_POST['billing_address'];
$billing_city = $_POST['billing_city'];
$billing_province = $_POST['billing_province'];
$billing_postal = $_POST['billing_postal'];
$billing_country = $_POST['billing_country'];

$date_created = $_POST['date_created'];
$date_due = $_POST['date_due'];
$tracking_number = $_POST['tracking_number'];
$purchase_order = $_POST['purchase_order'];

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

$invoice_id = $_POST['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Assign values to a database table:
$doSQL = "UPDATE invoices SET purpose = '$purpose', easypay_id = '$easypay_id', billing_email_address = '$billing_email_address', billing_address = '$billing_address', billing_city = '$billing_city', billing_province = '$billing_province', billing_postal = '$billing_postal', billing_country = '$billing_country', tracking_number = '$tracking_number', date_created = '$date_created', date_due = '$date_due', purchase_order = '$purchase_order', shipping_address = '$shipping_address', shipping_city = '$shipping_city', shipping_province = '$shipping_province', shipping_postal = '$shipping_postal', shipping_country = '$shipping_country' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Start calculate of line item from items:
if(!empty($_POST['quantity'])) {

$item_id = $_POST['item_id'];
$get_item_details = mysql_query("SELECT * FROM items WHERE item_id = '$item_id'");
$show_item_detail = mysql_fetch_array($get_item_details);

$quantity = $_POST['quantity'];
$discount_percent = $_POST['discount_percent'];
$tax1 = $_POST['tax1'];
$tax2 = $_POST['tax2'];
$warranty = $_POST['warranty'];

$category_id = $show_item_detail['category_id'];
$name = $_POST['suggest'];
$description = $show_item_detail['description'];
$cost = $show_item_detail['cost'];
$price = $show_item_detail['price'];

# If tax is applied to the line item; calculate the value of the tax:
# 2009/08/10 RC5 - Fixed to insert 0 instead of NULL for taxX and taxX_value
$line_total = ($quantity * $price);
$discount_value = $line_total * ($discount_percent * .01);
$extended = $line_total - $discount_value;
if ($tax1 == 1) { $tax1_value = ($extended * (($show_company['tax1_percent']) * .01)); } else { $tax1 = 0; $tax1_value = 0; };
if ($tax2 == 1) { $tax2_value = ($extended * (($show_company['tax2_percent']) * .01)); } else { $tax2 = 0; $tax2_value = 0; };
$extended_cost = ($quantity * $cost);
$extended_profit = ($extended - $extended_cost);

# Assign correct vales to warranty items:
if ($warranty == 1) { $extended = 0; $tax1_value = 0; $tax2_value = 0; } else { $warranty = 0; };

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

# Insert line item values into database:
$doSQL = "INSERT INTO invoice_items (invoice_id, category_id, name, description, cost, price, quantity, tax1, tax2, warranty, discount_value, tax1_value, tax2_value, extended, extended_cost, extended_profit, employee_id) VALUES ('$invoice_id', '$category_id', '$name', '$description', '$cost', '$price', '$quantity', '$tax1', '$tax2', '$warranty', '$discount_value', '$tax1_value', '$tax2_value', '$extended', '$extended_cost', '$extended_profit', '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Calulate invoice amounts:
$tax1_total = $show_invoice['tax1_total'] + $tax1_value;
$tax2_total = $show_invoice['tax2_total'] + $tax2_value;
$subtotal = $show_invoice['subtotal'] + $extended;
$total =  $subtotal + $tax1_total + $tax2_total;
$due = $total - $show_invoice['received'];

# Calculate invoice profit:
$total_cost = ($show_invoice['total_cost'] + $extended_cost);
$total_profit = ($show_invoice['total_profit'] + $extended_profit);

# Update the balance of the invoice table:
$doSQL = "UPDATE invoices SET tax1_total = '$tax1_total', tax2_total = '$tax2_total', subtotal = '$subtotal', total = '$total', total_cost = '$total_cost', total_profit = '$total_profit', due = '$due' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

}

# Return to screen:
header("Location: update_invoice.php?invoice_id=$invoice_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Invoice</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/auto_suggest.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/invoices.png" alt="Update Invoice" width="16" height="16" /> Update Invoice: #<?php echo $show_invoice['invoice_id'] ?></h1>
    <p>Record created <?php echo $show_invoice['created'] ?> by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Invoice: <?php echo $show_invoice['invoice_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="update_invoice" name="update_invoice" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Summary: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">client:<br />
                  <a href="javascript:openWindow('change_invoiced_client.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')">change invoiced client</a></td>
                <td><input name="client" type="text" class="entrytext" id="client" readonly="readonly" value="<?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?>" />
                  <a href="update_client.php?client_id=<?php echo $show_invoice['client_id'] ?>"><img src="../images/icons/information.png" alt="Client Details" width="16" height="16" class="iconspacer" /></a></td>
              </tr>
              <tr>
                <td class="firstcell">purpose:</td>
                <td><input name="purpose" type="text" class="entrytext" id="purpose" value="<?php echo $show_invoice['purpose'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">easypay id:</td>
                <td><input name="easypay_id" type="text" class="entrytext" id="easypay_id" value="<?php echo $show_invoice['easypay_id'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing e-mail address:</td>
                <td><input name="billing_email_address" type="text" class="entrytext" id="billing_email_address" value="<?php echo $show_invoice['billing_email_address'] ?>" />
                  <a href="mailto:<?php echo $show_invoice['billing_email_address'] ?>"><img src="../images/icons/email.png" alt="E-mail" width="16" height="16" class="iconspacer" /></a></td>
              </tr>
            </table>
            <h2>Billing: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">billing address:<br />
                  <a href="javascript:copyShipping()">copy shipping information</a></td>
                <td><input name="billing_address" type="text" class="entrytext" id="billing_address" value="<?php echo $show_invoice['billing_address'] ?>" />
                  <a href="javascript:openWindow('show_map.php?client_id=<?php echo $show_client['client_id'] ?>')"><img src="../images/icons/map.png" alt="Show Map" width="16" height="16" class="iconspacer" /></a></td>
              </tr>
              <tr>
                <td class="firstcell">billing city:</td>
                <td><input name="billing_city" type="text" class="entrytext" id="billing_city" value="<?php echo $show_invoice['billing_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing province:</td>
                <td><input name="billing_province" type="text" class="entrytext" id="billing_province" value="<?php echo $show_invoice['billing_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing postal:</td>
                <td><input name="billing_postal" type="text" class="entrytext" id="billing_postal" value="<?php echo $show_invoice['billing_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">billing country:</td>
                <td><input name="billing_country" type="text" class="entrytext" id="billing_country" value="<?php echo $show_invoice['billing_country'] ?>" /></td>
              </tr>
            </table></td>
          <td class="halftopcell"><h2>Details: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">tracking number:</td>
                <td><input name="tracking_number" type="text" class="entrytext" id="tracking_number" value="<?php echo $show_invoice['tracking_number'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">purchase order:</td>
                <td><input name="purchase_order" type="text" class="entrytext" id="purchase_order" value="<?php echo $show_invoice['purchase_order'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">date created:</td>
                <td><input name="date_created" type="text" class="entrytext" id="date_created" value="<?php echo $show_invoice['date_created'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">date due:</td>
                <td><input name="date_due" type="text" class="entrytext" id="date_due" value="<?php echo $show_invoice['date_due'] ?>" /></td>
              </tr>
            </table>
            <h2>Shipping: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">shipping address:<br />
                  <a href="javascript:copyBilling()">copy billing information</a></td>
                <td><input name="shipping_address" type="text" class="entrytext" id="shipping_address" value="<?php echo $show_invoice['shipping_address'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping city:</td>
                <td><input name="shipping_city" type="text" class="entrytext" id="shipping_city" value="<?php echo $show_invoice['shipping_city'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping province:</td>
                <td><input name="shipping_province" type="text" class="entrytext" id="shipping_province" value="<?php echo $show_invoice['shipping_province'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping postal:</td>
                <td><input name="shipping_postal" type="text" class="entrytext" id="shipping_postal" value="<?php echo $show_invoice['shipping_postal'] ?>" /></td>
              </tr>
              <tr>
                <td class="firstcell">shipping country:</td>
                <td><input name="shipping_country" type="text" class="entrytext" id="shipping_country" value="<?php echo $show_invoice['shipping_country'] ?>" /></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="6%" class="tabletop">&nbsp;</td>
          <td class="tabletop">item &amp; description:</td>
          <td width="10%" class="tabletop">quantity:</td>
          <td width="8%" class="tabletop">price:</td>
          <td width="10%" class="tabletop">discount:</td>
          <td width="7%" class="tabletop"><?php echo $show_company['tax1_name'] ?>:</td>
          <td width="7%" class="tabletop"><?php echo $show_company['tax2_name'] ?>:</td>
          <td width="8%" class="tabletop">warranty:</td>
          <td width="10%" class="tabletop">extended:</td>
        </tr>
        <?php while($show_invoice_item = mysql_fetch_array($get_invoice_items)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice_item['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_invoice_item.php?invoice_item_id=<?php echo $show_invoice_item['invoice_item_id'] ?>" onClick="return confirm('Delete #: <?php echo $show_invoice_item['name'] ?>?')"><img src="../images/icons/delete.png" alt="Delete Invoice Item" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="javascript:openWindow('update_invoice_item.php?invoice_item_id=<?php echo $show_invoice_item['invoice_item_id'] ?>')" onmouseover="tooltip(event, '<?php echo $show_invoice_item['invoice_item_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_invoice_item['invoice_item_id'] ?>')"><?php echo $show_invoice_item['name'] ?></a><br />
            <span class="smalltext"><?php echo $show_invoice_item['description'] ?></span><br />
            <span class="smallredtext"><?php echo $show_invoice_item['serial_number'] ?></span>
            <div class="tooltip" id="<?php echo $show_invoice_item['invoice_item_id'] ?>">
              <table>
                <tr>
                  <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                    <span class="smalltext"><?php echo $show_invoice_item['created'] ?></span></td>
                </tr>
              </table>
            </div></td>
          <td class="tablerowborder"><?php echo $show_invoice_item['quantity'] ?></td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['price'], 2) ?></td>
          <td class="tablerowborder">(<?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['discount_value'], 2) ?>)</td>
          <td class="tablerowborder"><?php echo $show_invoice_item['tax1'] ?></td>
          <td class="tablerowborder"><?php echo $show_invoice_item['tax2'] ?></td>
          <td class="tablerowborder"><?php echo $show_invoice_item['warranty'] ?></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['extended'], 2) ?></span></td>
        </tr>
        <?php } ?>
        <tr>
          <td>&nbsp;</td>
          <td><input name="suggest" type="text" class="entrytext" id="suggest" autocomplete="off" onkeyup="javascript:autosuggest()" />
            <br />
            <div id="results"></div></td>
          <td><input name="quantity" type="text" class="entrytext" id="quantity" autocomplete="off" /></td>
          <td>&nbsp;</td>
          <td><input name="discount_percent" type="text" class="entrytext" id="discount_percent" value="<?php echo $show_client['discount'] ?>" autocomplete="off" /></td>
          <td><input name="tax1" type="checkbox" id="tax1" value="1" checked="checked" /></td>
          <td><input name="tax2" type="checkbox" id="tax2" value="1" /></td>
          <td><input name="warranty" type="checkbox" id="warranty" value="1" /></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td rowspan="6" class="topalign"><table class="fulltable">
              <tr>
                <td><a href="javascript:openWindow('update_invoice_notes.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/note.png" alt="Invoice Notes" class="iconspacer" /></a> <a href="javascript:openWindow('../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/print.png" alt="Print Invoice" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('e-mail_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/email_attachment.png" alt="E-mail Invoice" class="iconspacer" /></a> <a href="javascript:openWindow('print_invoice_shipping.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/shipping_label.png" alt="Print Shipping Label" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('print_invoice_identification.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/indentification_label.png" alt="Identification Label" class="iconspacer" /></a></td>
              </tr>
            </table>
            <table class="fulltable">
              <tr>
                <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
                  <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" />
                  <input name="item_id" type="hidden" id="item_id" />
                  <input name="suggest_type" type="hidden" id="suggest_type" value="item" /></td>
              </tr>
            </table></td>
          <td width="10%" class="tablerowborder">subtotal:</td>
          <td width="10%" class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['subtotal'], 2) ?></td>
        </tr>
        <tr>
          <td class="tablerowborder"><?php echo $show_company['tax1_name'] ?> (<?php echo $show_company['tax1_percent'] ?>%):</td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['tax1_total'], 2) ?></td>
        </tr>
        <tr>
          <td class="tablerowborder"><?php echo $show_company['tax2_name'] ?> (<?php echo $show_company['tax2_percent'] ?>%):</td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['tax2_total'], 2) ?></td>
        </tr>
        <tr>
          <td class="tablerowborder">total:</td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total'], 2) ?></td>
        </tr>
        <tr>
          <td class="tablerowborder">received:</td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['received'], 2) ?></td>
        </tr>
        <tr>
          <td class="tablerowborder">due:</td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['due'], 2) ?></span></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
