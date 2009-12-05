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

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_item_categories = mysql_query("SELECT * FROM item_categories");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$category_id = $_POST['category_id'];
$name = strtoupper($_POST['name']);
$description = strtolower($_POST['description']);
$cost = $_POST['cost'];
$price = $_POST['price'];

# Calculate profit and markup from values:
$profit = ($price - $cost);
$markup = ($profit / $cost) * 100;

# Assign employee to invoice:
$employee_id = $_SESSION['employee_id'];

# Make MySQL statement:
$doSQL = "INSERT INTO items (category_id, name, description, cost, price, profit, markup, employee_id) VALUES ('$category_id', '$name', '$description', '$cost', '$price', '$profit', '$markup', '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Create Item</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('name').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1>Create Item:</h1>
    <p>Enter item details.</p>
  </div>
  <div id="content">
    <form id="create_item" name="create_item" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">category:</td>
          <td><select name="category_id" class="entrytext" id="category_id">
              <?php while($show_item_category = mysql_fetch_array($get_item_categories)) { ?>
              <option value="<?php echo $show_item_category['category_id'] ?>"><?php echo $show_item_category['name'] ?> </option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" /></td>
        </tr>
        <tr>
          <td class="firstcell">description:</td>
          <td><input name="description" type="text" class="entrytext" id="description" /></td>
        </tr>
        <tr>
          <td class="firstcell">cost:</td>
          <td><input name="cost" type="text" class="entrytext" id="cost" onchange="getPrice()" /></td>
        </tr>
        <tr>
          <td class="firstcell">price:</td>
          <td><input name="price" type="text" class="entrytext" id="price" onchange="getMarkup()" /></td>
        </tr>
        <tr>
          <td class="firstcell">markup %:</td>
          <td><input name="markup" type="text" class="entrytext" id="markup" onchange="getPrice()" value="<?php echo $show_company['markup_percent'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
