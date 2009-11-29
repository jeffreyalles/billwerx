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

$item_id = $_GET['item_id'];
$get_item = mysql_query("SELECT * FROM items WHERE item_id = '$item_id'");
$show_item = mysql_fetch_array($get_item);

$get_item_categories = mysql_query("SELECT * FROM item_categories");

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_item['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$category_id = $_POST['category_id'];

$name = strtoupper($_POST['name']);
$description = strtolower($_POST['description']);
$cost = $_POST['cost'];
$price = $_POST['price'];

# Calculate profit and markup from values:
$profit = ($price - $cost);
$markup = ($profit / $cost) * 100;

$item_id = $_POST['item_id'];

# Assign values to a database table:
$doSQL = "UPDATE items SET category_id = '$category_id', name = '$name', description = '$description', cost = '$cost', price = '$price', profit = '$profit', markup = '$markup' WHERE item_id = '$item_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Item</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('name').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/items.png" alt="Update Item" width="16" height="16" /> Update Item:</h1>
    <p>Record created <?php echo $show_item['created'] ?> by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Item: <?php echo $show_item['name'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</p>
  </div>
  <div id="content">
    <form id="update_item" name="update_item" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">category:</td>
          <td><select name="category_id" class="entrytext" id="category_id">
            <?php while($show_item_category = mysql_fetch_array($get_item_categories)) { ?>
            <option value="<?php echo $show_item_category['category_id'] ?>"<?php if($show_item['category_id'] == $show_item_category['category_id']) { ?> selected="selected"<?php } ?>><?php echo $show_item_category['name'] ?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" value="<?php echo $show_item['name'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">description:</td>
          <td><input name="description" type="text" class="entrytext" id="description" value="<?php echo $show_item['description'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">cost:</td>
          <td><input name="cost" type="text" class="entrytext" id="cost" value="<?php echo number_format($show_item['cost'], 2) ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">price:</td>
          <td><input name="price" type="text" class="entrytext" id="price" value="<?php echo number_format($show_item['price'], 2) ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
          <input name="item_id" type="hidden" id="item_id" value="<?php echo $show_item['item_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
