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

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $show_company['records_per_page']);
$next_page = ($start + $show_company['records_per_page']);

# Get invoice data:
$get_total_surveys = mysql_query("SELECT * FROM surveys");
$total_records = mysql_num_rows($get_total_surveys);
$get_surveys = mysql_query("SELECT * FROM surveys ORDER BY invoice_id DESC LIMIT $start, " . $show_company['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_surveys = mysql_query("SELECT * FROM surveys WHERE (invoice_id LIKE '%$query%') OR (rating LIKE '%$query%')");
$total_records = mysql_num_rows($get_suppliers);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Surveys</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/survey.png" alt="Surveys" width="16" height="16" /> Surveys:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="surveys" name="surveys" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Search:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">for query:</td>
                <td><input name="query" type="text" class="entrytext" id="query" /></td>
              </tr>
              <tr>
                <td class="firstcell">&nbsp;</td>
                <td><input name="draw" type="submit" class="button" id="draw" onclick="openWindow('survey_draw.php')" value="DRAW" /></td>
              </tr>
            </table></td>
          <td class="halftopcell"><img src="surveys_pgraph_history.php" alt="Results By Invoice" /> <img src="surveys_pgraph_average.php" alt="Average" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="8%" class="tabletop">invoice #:</td>
          <td width="20%" class="tabletop">employee:</td>
          <td width="22%" class="tabletop">client / created:</td>
          <td width="8%" class="tabletop">rating:</td>
          <td class="tabletop">comments:</td>
        </tr>
        <?php while($show_survey = mysql_fetch_array($get_surveys)) { ?>
        <?php $get_invoices = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_survey['invoice_id'] . "") ?>
        <?php $show_invoice = mysql_fetch_array($get_invoices) ?>
        <?php $get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . ""); ?>
        <?php $show_client = mysql_fetch_array($get_clients) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="mailto:<?php echo $show_client['email_address'] ?>?subject=<?php echo $show_invoice['purpose'] ?>"><img src="../images/icons/email.png" alt="E-mail" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="javascript:openWindow('../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>')" onmouseover="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_invoice['invoice_id'] ?>')"><?php echo $show_invoice['invoice_id'] ?></a>
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
          <td class="tablerowborder"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?><br />
            <span class="smalltext"><a href="mailto:<?php echo $show_employee['email_address'] ?>"><?php echo $show_employee['email_address'] ?></a></span></td>
          <td class="tablerowborder"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?><br />
            <span class="smalltext"><?php echo $show_survey['created'] ?></span></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_survey['rating'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_survey['comments'] ?></td>
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
