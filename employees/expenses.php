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
$get_total_expenses = mysql_query("SELECT * FROM expenses");
$total_records = mysql_num_rows($get_total_expenses);
$get_expenses = mysql_query("SELECT * FROM expenses ORDER BY expense_id DESC LIMIT $start, " . $show_company['records_per_page'] . "");

# Start search:
if(isset($_POST['query'])) {
$query = $_POST['query'];
$get_expenses = mysql_query("SELECT * FROM expenses WHERE (expense_id LIKE '%$query%') OR (reference LIKE '%$query%') OR (amount LIKE '%$query%')");
$total_records = mysql_num_rows($get_expenses);
$next_page = $total_records;
};

# Start search:
if(isset($_GET['supplier_id'])) {
$supplier_id = $_GET['supplier_id'];
$get_expenses = mysql_query("SELECT * FROM expenses WHERE supplier_id = '$supplier_id'");
$total_records = mysql_num_rows($get_expenses);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Expenses</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/expenses.png" alt="Expenses" width="16" height="16" /> Expenses:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="expenses" name="expenses" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
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
                <td><input name="create" type="button" class="button" id="create" onclick="openWindow('create_expense.php')" value="CREATE" />
                  <input name="categories" type="button" class="button" id="categories" onclick="openWindow('update_expense_categories.php')" value="CATEGORIES" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="expenses_pgraph.php" alt="Expenses" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="10%" class="tabletop">expense #:</td>
          <td width="18%" class="tabletop">entered by:</td>
          <td class="tabletop">supplier:</td>
          <td width="20%" class="tabletop">category / method:</td>
          <td width="10%" class="tabletop">reference:</td>
          <td width="10%" class="tabletop">amount:</td>
        </tr>
        <?php while($show_expense = mysql_fetch_array($get_expenses)) { ?>
        <?php $get_suppliers = mysql_query("SELECT * FROM suppliers WHERE supplier_id = " . $show_expense['supplier_id'] . "") ?>
        <?php $show_supplier = mysql_fetch_array($get_suppliers) ?>
        <?php $get_expense_categories = mysql_query("SELECT * FROM expense_categories WHERE category_id = " . $show_expense['category_id'] . "") ?>
        <?php $show_expense_category = mysql_fetch_array($get_expense_categories) ?>
        <?php $get_payment_methods = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_expense['method_id'] . "") ?>
        <?php $show_expense_method = mysql_fetch_array($get_payment_methods) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_expense['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_expense.php?expense_id=<?php echo $show_expense['expense_id'] ?>" onClick="return confirm('Delete record #: <?php echo $show_expense['expense_id'] ?> (<?php echo $show_company['currency_symbol'] ?><?php echo $show_expense['amount'] ?> <?php echo $show_expense_method['name'] ?>)?')"><img src="../images/icons/delete.png" alt="Delete Payment" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="javascript:openWindow('update_expense.php?expense_id=<?php echo $show_expense['expense_id'] ?>')"><?php echo $show_expense['expense_id'] ?></a></td>
          <td class="tablerowborder"><a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Expense: <?php echo $show_expense['expense_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a><br />
          <span class="smalltext"><?php echo $show_expense['date_received'] ?></span></td>
          <td class="tablerowborder"><a href="update_client.php?client_id=<?php echo $show_client['client_id'] ?>"><?php echo strtoupper($show_supplier['last_name']) ?>, <?php echo $show_supplier['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_supplier['company_name'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_expense_category['name'] ?><br />
              <span class="smalltext"><?php echo $show_expense_method['name'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_expense['reference'] ?></a></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_expense['amount'], 2) ?></span></td>
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
