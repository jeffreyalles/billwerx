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

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $_SESSION['records_per_page']);
$next_page = ($start + $_SESSION['records_per_page']);

$employee_id = $_SESSION['employee_id'];

# Get invoice data:
$get_total_invoices = mysql_query("SELECT * FROM invoices");
$total_records = mysql_num_rows($get_total_invoices);
$get_invoices = mysql_query("SELECT * FROM invoices WHERE employee_id = '$employee_id' ORDER BY invoice_id DESC LIMIT $start, " . $_SESSION['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_invoices = mysql_query("SELECT * FROM invoices WHERE (invoice_id = '$query') OR (purpose LIKE '%$query%')");
$total_records = mysql_num_rows($get_invoices);
$next_page = $total_records;
};

# Start search:
if(isset($_GET['client_id'])) {
$client_id = $_GET['client_id'];
$get_invoices = mysql_query("SELECT * FROM invoices WHERE client_id = '$client_id' ORDER BY invoice_id DESC");
$total_records = mysql_num_rows($get_invoices);
$next_page = $total_records;
};

# Start search:
if(isset($_GET['employee_id'])) {
$employee_id = $_GET['employee_id'];
$get_invoices = mysql_query("SELECT * FROM invoices WHERE employee_id = '$employee_id' LIMIT $start, " .  $_SESSION['records_per_page'] . "");
$total_records = mysql_num_rows($get_invoices);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Invoices</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
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
    <form id="invoices" name="invoices" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h1><img src="../images/icons/invoices.png" alt="Invoices" width="16" height="16" /> Invoices:</h1>
          <table class="fulltable">
              <tr>
                <td class="justred">Found <?php echo $total_records ?> record(s).</td>
              </tr>
              <tr>
                <td><input name="query" type="text" class="entrytext" id="query" onclick="this.value=''" value="search query" /></td>
              </tr>
              
              <tr>
                <td><input name="create" type="button" class="button" id="create" onclick="window.location='create_invoice.php'" value="CREATE" />
                  <input name="export" type="button" class="button" id="export" onclick="window.location='export_invoices.php'" value="EXPORT" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="invoices_pgraph.php" alt="Monthly Sales Profits" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="8%" class="tabletop">invoice #:</td>
          <td class="tabletop">invoice / purpose:</td>
          <td width="10%" class="tabletop">date due:</td>
          <td width="24%" class="tabletop">client:</td>
          <td width="10%" class="tabletop">total / cost:</td>
          <td width="10%" class="tabletop">due / profit:</td>
        </tr>
        <?php while($show_invoice = mysql_fetch_array($get_invoices)) { ?>
        <?php $get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "") ?>
        <?php $show_client = mysql_fetch_array($get_client) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="javascript:openWindow('../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/print.png" alt="Print" width="16" height="16" class="iconspacer" /></a> <a href="javascript:openWindow('e-mail_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')"><img src="../images/icons/email_compose.png" alt="E-mail" class="iconspacer" /></a></td>
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
              </table>
            </div></td>
          <td class="tablerowborder"><?php echo $show_invoice['purpose'] ?><br />
            <span class="smalltext"><?php echo $show_invoice['created'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_invoice['date_due'] ?></td>
          <td class="tablerowborder"><a href="update_client.php?client_id=<?php echo $show_client['client_id'] ?>"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_client['company_name'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total'], 2) ?><br />
            <span class="smalltext"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total_cost'], 2) ?></span></td>
          <td class="tablerowborder"><span class="smalltext"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['due'], 2) ?></span><br />
            <span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total_profit'], 2) ?></span></td>
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
