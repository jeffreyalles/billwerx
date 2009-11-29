<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $show_company['records_per_page']);
$next_page = ($start + $show_company['records_per_page']);

# Get payment data:
$get_total_payments = mysql_query("SELECT * FROM payments");
$total_records = mysql_num_rows($get_total_payments);
$get_payments = mysql_query("SELECT * FROM payments ORDER BY payment_id DESC LIMIT $start, " . $show_company['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_payments = mysql_query("SELECT * FROM payments WHERE (invoice_id LIKE '%$query%') OR (amount LIKE '%$query%') OR (reference LIKE '%$query%')");
$total_records = mysql_num_rows($get_payments);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Payments</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/payments.png" alt="Payments" width="16" height="16" /> Payments:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="payments" name="payments" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Search: </h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">query:</td>
                <td><input name="query" type="text" class="entrytext" id="query" /></td>
              </tr>
              <tr>
                <td class="firstcell">&nbsp;</td>
                <td><input name="methods" type="button" class="button" id="methods" onclick="openWindow('update_payment_methods.php')" value="METHODS" /></td>
              </tr>
            </table></td>
          <td class="halftopcell"><img src="payments_pgraph_method.php" alt="Top Payment Methods" /> <img src="payments_pgraph_total.php" alt="Totals" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">options:</td>
          <td width="14%" class="tabletop">payment #:</td>
          <td width="10%" class="tabletop">invoice #:</td>
          <td width="18%" class="tabletop">entered by:</td>
          <td class="tabletop">client:</td>
          <td width="14%" class="tabletop">reference:</td>
          <td width="10%" class="tabletop">amount:</td>
        </tr>
        <?php while($show_payment = mysql_fetch_array($get_payments)) { ?>
        <?php $get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_payment['client_id'] . "") ?>
        <?php $show_client = mysql_fetch_array($get_clients) ?>
        <?php $get_payment_methods = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "") ?>
        <?php $show_payment_method = mysql_fetch_array($get_payment_methods) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_payment['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <?php $get_invoices = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_payment['invoice_id'] . ""); ?>
        <?php $show_invoice = mysql_fetch_array($get_invoices) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_payment.php?payment_id=<?php echo $show_payment['payment_id'] ?>" onClick="return confirm('Delete record #: <?php echo $show_payment['payment_id'] ?> (<?php echo $show_company['currency_symbol'] ?><?php echo $show_payment['amount'] ?> <?php echo $show_payment_method['name'] ?>)?')"><img src="../images/icons/delete.png" alt="Delete Payment" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('e-mail_payment_received.php?payment_id=<?php echo $show_payment['payment_id'] ?>')"><img src="../images/icons/email_attachment.png" alt="E-Mail Invoice" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="javascript:openWindow('update_payment.php?payment_id=<?php echo $show_payment['payment_id'] ?>')"><?php echo $show_payment['payment_id'] ?></a><br />
            <span class="smalltext"><?php echo $show_payment_method['name'] ?></span></td>
          <td class="tablerowborder"><a href="update_invoice.php?invoice_id=<?php echo $show_payment['invoice_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')"><?php echo $show_payment['invoice_id'] ?></a><br />
            <div class="tooltip" id="<?php echo $show_invoice['invoice_id'] ?>">
            <table>
              <tr>
                <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                  <span class="smalltext"><?php echo $show_invoice['created'] ?></span></td>
              </tr>
              <tr>
                <td><?php echo $show_invoice['notes'] ?></td>
              </tr>
            </table></div></td>
          <td class="tablerowborder"><a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Payment: <?php echo $show_payment['payment_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_payment['date_received'] ?></span></td>
          <td class="tablerowborder"><a href="update_client.php?client_id=<?php echo $show_client['client_id'] ?>"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_client['company_name'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_payment['reference'] ?></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo $show_payment['amount'] ?></span></td>
        </tr>
        <?php } ?>
      </table>
      <table class="fulltable">
        <tr>
          <td class="pagination"><?php if ($start > 0) { ?>
            <a href="?start=<?php echo $previous_page ?>"><img src="../images/icons/previous.png" alt="Prevous Page" width="16" height="16" class="iconspacer" /></a>
            <?php } ?>
            <?php if ($next_page < $total_records) { ?>
            <a href="?start=<?php echo $next_page ?>"><img src="../images/icons/next.png" alt="Next Page" width="16" height="16" class="iconspacer" /></a>
            <?php } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
