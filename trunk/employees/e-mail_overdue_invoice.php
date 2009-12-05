<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");
include("../inc/phpmailer/class.phpmailer.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get company messages:
$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Define query to pick out all overdue invoices:
$get_overdue_invoices = mysql_query("SELECT * FROM invoices WHERE due !=0 AND date_due < NOW()");

# Get total records:
$total_records = mysql_num_rows($get_overdue_invoices);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['email'])) {

# Define selected invoices from array:
for($i = 0; $i < $total_records; $i++) {
$selected_invoice_id = $_POST['invoice_id'][$i];

# Get only invoices which were clicked / greater than 0:
if($selected_invoice_id > 0) {
echo "This would then e-mail invoice $selected_invoice_id to the client.\n\n";
};};};


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?>- E-mail Overdue Notice</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/email.png" alt="E-mail Invoice" width="16" height="16" /> E-mail Overdue Notice:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td width="10%" class="tabletop">&nbsp;</td>
          <td class="tabletop">invoice / client::</td>
          <td width="20%" class="tabletop">due:</td>
        </tr>
        <?php while($show_overdue_invoice = mysql_fetch_array($get_overdue_invoices)) { ?>
        <?php $get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_overdue_invoice['client_id'] . "") ?>
        <?php $show_client = mysql_fetch_array($get_client) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_overdue_invoice['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><input name="invoice_id[]" type="checkbox" id="invoice_id[]" value="<?php echo $show_overdue_invoice['invoice_id'] ?>'" checked="checked" /></td>
          <td class="tablerowborder"><a href="javascript:openWindow('../global/print_invoice.php?invoice_id=<?php echo $show_overdue_invoice['invoice_id'] ?>')" onmouseover="tooltip(event, '<?php echo $show_overdue_invoice['invoice_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_overdue_invoice['invoice_id'] ?>')"><?php echo $show_overdue_invoice['invoice_id'] ?> - <?php echo $show_overdue_invoice['purpose'] ?></a><br />
            <span class="smalltext"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></span></td>
          <div class="tooltip" id="<?php echo $show_overdue_invoice['invoice_id'] ?>"><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br />
            <span class="smalltext"><?php echo nl2br($show_overdue_invoice['notes']) ?></span></div>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_overdue_invoice['due'], 2) ?></span><br />
            <span class="smalltext"><?php echo $show_overdue_invoice['date_due'] ?></span></td>
          <?php } ?>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="email" type="submit" class="button" id="email" value="E-MAIL" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
