<?php

# Define page access level:
session_start();

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Get client data:
$client_id = $_SESSION['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Get Invoices:
$get_unpaid_invoices = mysql_query("SELECT * FROM invoices WHERE client_id = '$client_id' AND due != 0 ORDER BY invoice_id DESC");
$get_invoices = mysql_query("SELECT * FROM invoices WHERE client_id = '$client_id' ORDER BY invoice_id DESC");
$total_invoices = mysql_num_rows($get_invoices);
$get_invoices_total = mysql_query("SELECT SUM(total) AS total FROM invoices WHERE client_id = '$client_id' ORDER BY invoice_id DESC");
$show_invoices_total = mysql_fetch_array($get_invoices_total);
$get_invoices_due = mysql_query("SELECT SUM(due) AS amount_due FROM invoices WHERE client_id = '$client_id' ORDER BY invoice_id DESC");
$show_invoices_due = mysql_fetch_array($get_invoices_due);

# Get Payments:
$get_payments = mysql_query("SELECT * FROM payments WHERE client_id = '$client_id' ORDER BY invoice_id DESC");
$total_payments = mysql_num_rows($get_payments);
$get_payments_total = mysql_query("SELECT SUM(amount) AS total FROM payments WHERE client_id = '$client_id'");
$show_payments_total = mysql_fetch_array($get_payments_total);

# Setup query to obtain encrypted notes:
$get_client_notes = mysql_query("SELECT note_id, AES_DECRYPT(note, '$encryption_key') AS note, created AS created, employee_id AS employee_id FROM client_notes WHERE client_id = '$client_id'");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Client Account Summary</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/tooltip.js"></script>
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="floatingdiv"><span class="smalltext"><?php echo nl2br($show_company_message['client_notice']) ?></span>
  <p><a href="javascript:hideDiv('floatingdiv')">Close Window</a></p>
</div>
<script type="text/javascript" src="../scripts/float_layer.js"></script>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/summary.png" alt="Summary" /> Summary: <?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></h1>
    <p><?php echo $show_client['first_name'] ?> <?php echo $show_client['last_name'] ?> your account was created on <?php echo $show_client['created'] ?>.</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <table class="fulltable">
      <tr>
        <td class="halftopcell"><h2>Payment:</h2>
          <form id="form1" name="form1" method="post" action="credit_card_payment.php">
            <table class="fulltable">
              <tr>
                <td class="firstcell">invoice and purpose:</td>
                <td><select name="invoice_id" class="entrytext" id="invoice_id">
                    <?php while($show_unpaid_invoice = mysql_fetch_array($get_unpaid_invoices)) { ?>
                    <option value="<?php echo $show_unpaid_invoice['invoice_id'] ?>"><?php echo $show_unpaid_invoice['invoice_id'] ?> - <?php echo $show_unpaid_invoice['purpose'] ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="firstcell">&nbsp;</td>
                <td><input name="pay" type="submit" class="button" id="pay" value="PAY" /></td>
              </tr>
            </table>
          </form></td>
        <td class="halftopcell"><img src="client_pgraph.php" alt="Monthly Sales History" /></td>
      </tr>
    </table>
    <table class="fulltable">
      <tr>
        <td class="halftopcell"><h2>Invoices:</h2>
          <table class="fulltable">
            <tr>
              <td width="16%" class="tabletop">invoice #:</td>
              <td class="tabletop">purpose / total:</td>
              <td width="20%" class="tabletop">due:</td>
            </tr>
            <?php while($show_invoice = mysql_fetch_array($get_invoices)) { ?>
            <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . ""); ?>
            <?php $show_employee = mysql_fetch_array($get_employees) ?>
            <tr class="tablelist">
              <td class="tablerowborder"><?php echo $show_invoice['invoice_id'] ?></td>
              <td class="tablerowborder"><a href="#" onclick="openWindow('../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')" onmouseover="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')"><?php echo $show_invoice['purpose'] ?></a><br />
                <span class="smalltext"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total'], 2) ?></span>
                <div class="tooltip" id="<?php echo $show_invoice['invoice_id'] ?>">
                  <table>
                    <tr>
                      <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                        <span class="smalltext"><?php echo $show_invoice['created'] ?></span></td>
                    </tr>
                    <tr>
                      <td><?php echo $show_invoice['notes'] ?></td>
                    </tr>
                  </table>
                </div></td>
              <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['due'], 2) ?></span></td>
            </tr>
            <?php } ?>
          </table>
          <table class="fulltable">
            <tr>
              <td width="16%" class="tabletop">quantity:</td>
              <td class="tabletop">invoice totals:</td>
              <td width="20%" class="tabletop">total due:</td>
            </tr>
            <tr class="tablelist">
              <td class="tablerowborder"><?php echo $total_invoices ?></td>
              <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoices_total['total'], 2) ?></td>
              <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoices_due['amount_due'], 2) ?></span></td>
            </tr>
          </table></td>
        <td class="halftopcell"><h2>Payments:</h2>
          <table class="fulltable">
            <tr>
              <td width="16%" class="tabletop">invoice #:</td>
              <td width="18%" class="tabletop">payment #:</td>
              <td class="tabletop">method / date:</td>
              <td width="20%" class="tabletop">reference:</td>
              <td width="16%" class="tabletop">amount:</td>
            </tr>
            <?php while($show_payment = mysql_fetch_array($get_payments)) { ?>
            <?php $get_payment_methods = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "") ?>
            <?php $show_payment_method = mysql_fetch_array($get_payment_methods) ?>
            <tr class="tablelist">
              <td class="tablerowborder"><?php echo $show_payment['invoice_id'] ?></td>
              <td class="tablerowborder"><?php echo $show_payment['payment_id'] ?></td>
              <td class="tablerowborder"><?php echo $show_payment_method['name'] ?><br />
                <span class="smalltext"><?php echo $show_payment['date_received'] ?></span></td>
              <td class="tablerowborder"><a href="../global/download_payment_file.php?payment_id=<?php echo $show_payment['payment_id'] ?>"><?php echo $show_payment['reference'] ?></a></td>
              <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_payment['amount'], 2) ?></span></td>
            </tr>
            <?php } ?>
          </table>
          <table class="fulltable">
            <tr>
              <td width="16%" class="tabletop">quantity:</td>
              <td class="tabletop">payment totals:</td>
            </tr>
            <tr class="tablelist">
              <td class="tablerowborder"><?php echo $total_payments ?></td>
              <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_payments_total['total'], 2) ?></td>
            </tr>
          </table></td>
      </tr>
    </table>
    <table class="fulltable">
      <tr>
        <td width="16%" class="tabletop">created:</td>
        <td class="tabletop">note:</td>
      </tr>
      <?php while($show_client_note = mysql_fetch_array($get_client_notes)) { ?>
      <?php $get_employee = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_client_note['employee_id'] . "") ?>
      <?php $show_employee = mysql_fetch_array($get_employee) ?>
      <tr class="tablelist">
        <td class="tablerowborder"><?php echo $show_client_note['created'] ?><br />
          <span class="smalltext"><a href="mailto:<?php echo $show_employee['email_address'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a></span></td>
        <td class="tablerowborder"><?php echo nl2br($show_client_note['note']) ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
</body>
</html>
