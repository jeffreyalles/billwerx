<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("../session_check.php");

# Include session check and database connection:
include("../../inc/dbconfig.php");

# Include security POST loop:
include("../../global/make_safe.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$employee_id = $_SESSION['employee_id'];

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = '$employee_id'");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['generate'])) {

# Get and define POST variables:
$starting_date = $_POST['starting_date'];
$ending_date = $_POST['ending_date'];

$get_invoice_items = mysql_query("SELECT invoice_id, category_id, name, created, SUM(quantity) AS quantity FROM invoice_items WHERE employee_id = '$employee_id' AND created BETWEEN '$starting_date' AND '$ending_date' GROUP BY invoice_id, category_id ORDER BY invoice_id");

$get_all_invoice_items = mysql_query("SELECT category_id, SUM(quantity) AS total_quantity FROM invoice_items WHERE employee_id = '$employee_id' AND created BETWEEN '$starting_date' AND '$ending_date' GROUP BY category_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Employee Report</title>
<link href="../../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../scripts/form_assist.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../../images/icons/reports.png" alt="Invoices" width="16" height="16" /> Employee Report:</h1>
    <p>Generating for: <?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?>.</p>
  </div>
  <div id="content">
    <form id="report" name="report" method="post" action="<?php echo $_SERVER['../PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">starting date:</td>
          <td><input name="starting_date" type="text" class="entrytext" id="starting_date" value="<?php if(isset($starting_date)) echo $starting_date ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">ending date:</td>
          <td><input name="ending_date" type="text" class="entrytext" id="ending_date" value="<?php if(isset($ending_date)) echo $ending_date ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="generate" type="submit" class="button" id="generate" value="GENERATE" />
            <input name="print" type="button" class="button" id="print" onclick="javascript:window.print()" value="PRINT" /></td>
        </tr>
      </table>
      <?php if(isset($_POST['generate'])) { ?>
      <h1>Details:</h1>
      <table class="fulltable">
        <tr>
          <td width="18%" class="tabletop">invoice:</td>
          <td class="tabletop">item category:</td>
          <td width="18%" class="tabletop">quantity:</td>
        </tr>
        <?php while($show_invoice_item = mysql_fetch_array($get_invoice_items)) { ?>
        <?php $get_item_categories = mysql_query("SELECT * FROM item_categories WHERE category_id = " . $show_invoice_item['category_id'] . ""); ?>
        <?php $show_item_category = mysql_fetch_array($get_item_categories) ?>
        <tr>
          <td class="tablerowborder"><?php echo $show_invoice_item['invoice_id'] ?></td>
          <td class="tablerowborder"><?php echo $show_item_category['name'] ?></td>
          <td width="10%" class="tablerowborder"><?php echo $show_invoice_item['quantity'] ?></td>
        </tr>
        <?php } ?>
      </table>
      <h1>Summary:</h1>
      <table class="fulltable">
        <tr>
          <td class="tabletop">item category:</td>
          <td width="18%" class="tabletop">quantity:</td>
        </tr>
        <?php while($show_all_invoice_items = mysql_fetch_array($get_all_invoice_items)) { ?>
        <?php $get_item_categories = mysql_query("SELECT * FROM item_categories WHERE category_id = " . $show_all_invoice_items['category_id'] . ""); ?>
        <?php $show_item_category = mysql_fetch_array($get_item_categories) ?>
        <tr>
          <td class="tablerowborder"><?php echo $show_item_category['name'] ?></td>
          <td width="10%" class="tablerowborder"><?php echo $show_all_invoice_items['total_quantity'] ?></td>
        </tr>
        <?php } ?>
      </table>
      <?php } ?>
    </form>
  </div>
</div>
</body>
</html>
