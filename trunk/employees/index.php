<?php

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

$employee_id = $_SESSION['employee_id'];

//Find the search string and type from the database:
$get_invoices = mysql_query("SELECT * FROM invoices WHERE due != 0 AND employee_id = '$employee_id' ORDER BY invoice_id DESC");

# Setup different query for managers or administrators:

if($_SESSION['access_level'] > 1) {
$get_invoices = mysql_query("SELECT * FROM invoices WHERE due != 0 ORDER BY invoice_id DESC");
};

$total_records = mysql_num_rows($get_invoices);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Invoice Summary</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="floatingdiv"><span class="justitalic"><?php echo nl2br($show_company_message['employee_notice']) ?></span>
  <p><a href="javascript:hideDiv('floatingdiv')">Close Window</a></p>
</div>
<script type="text/javascript" src="../scripts/float_layer.js"></script>
<div id="wrap">
  <div id="header"><img src="../global/company_logo.php" alt="<?php echo $show_company['company_name'] ?> - powered by: Billwerx" /></div>
  <div id="logininfo">
    <?php include_once("login_info.php") ?>
  </div>
  <div id="navbar">
    <?php include_once("navbar.php") ?>
  </div>
  <div id="content">
    <form id="index" name="index" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><img src="index_pgraph_week.php" alt="Weekly Sales Volume" /></td>
          <td class="halftopcell"><img src="index_pgraph_day.php" alt="Daily Sales Volume" /> <img src="index_pgraph_pending.php" alt="Pending Invoices" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="8%" class="tabletop">invoice #:</td>
          <td class="tabletop">purpose:</td>
          <td width="22%" class="tabletop">client:</td>
          <td width="9%" class="tabletop">created:</td>
          <td width="9%" class="tabletop">sent:</td>
          <td width="9%" class="tabletop">total:</td>
          <td width="9%" class="tabletop">due:</td>
        </tr>
        <?php while($show_invoice = mysql_fetch_array($get_invoices)) { ?>
        <?php $get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "") ?>
        <?php $show_client = mysql_fetch_array($get_client) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="javascript:openWindow('e-mail_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/email_compose.png" alt="E-mail Invoice" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('create_payment.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/payments.png" alt="Post Payment" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="update_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')"><?php echo $show_invoice['invoice_id'] ?></a>
            <div class="tooltip" id="<?php echo $show_invoice['invoice_id'] ?>">
            <table>
              <tr>
                <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                  <span class="smalltext"><?php echo $show_invoice['created'] ?></span></td>
              </tr>
              <tr>
                <td><?php echo $show_invoice['notes'] ?></td>
              </tr>
            </table></td>
          <td class="tablerowborder"><?php echo $show_invoice['purpose'] ?></td>
          <td class="tablerowborder"><a href="update_client.php?client_id=<?php echo $show_client['client_id'] ?>"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_client['company_name'] ?><br />
            <a href="mailto:<?php echo $show_client['email_address'] ?>"><?php echo $show_client['email_address'] ?></a></span></td>
          <td class="tablerowborder"><?php echo $show_invoice['date_created'] ?></td>
          <td class="tablerowborder"><?php echo $show_invoice['date_sent'] ?></td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total'], 2) ?></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['due'], 2) ?></span><br />
            <span class="smalltext"><?php echo $show_invoice['date_due'] ?></span></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
