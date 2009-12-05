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

$get_expense_categories = mysql_query("SELECT * FROM expense_categories");

$total_records = mysql_num_rows($get_expense_categories);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$name = strtoupper($_POST['name']);

# Make MySQL statement:
$doSQL = "INSERT INTO expense_categories (name) VALUES ('$name')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_expense_categories.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Expense Categories</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('name').focus()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/expenses.png" alt="Update Expense Categories" width="16" height="16" /> Update Expense Categories:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
  </div>
  <div id="content">
    <form id="update_invoice_categories" name="update_invoice_categories" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="10%" class="tabletop">&nbsp;</td>
          <td class="tabletop">name:</td>
        </tr>
        <?php while($show_expense_category = mysql_fetch_array($get_expense_categories)) { ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_expense_category.php?category_id=<?php echo $show_expense_category['category_id'] ?>"><img src="../images/icons/delete.png" alt="Delete Item Category" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><?php echo $show_expense_category['name'] ?></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
