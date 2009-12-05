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

$campaign_id = $_GET['campaign_id'];
$get_campaign = mysql_query("SELECT * FROM campaigns WHERE campaign_id = '$campaign_id'");
$show_campaign = mysql_fetch_array($get_campaign);

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_campaign['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['update'])) {

$name = strtoupper($_POST['name']);
$description = strtolower($_POST['description']);

$campaign_id = $_POST['campaign_id'];

# Assign values to a database table:
$doSQL = "UPDATE campaigns SET name = '$name', description = '$description' WHERE campaign_id = '$campaign_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: manage_campaigns.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Campaign</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('name').focus()">
<div id="smallwrap">
  <div id="header">
    <h2>Update Campaign:</h2>
    <h3>Record created <?php echo $show_campaign['created'] ?> by: <a href="mailto:<?php echo $show_employee['email_address'] ?>?subject=Campaign: <?php echo $show_campaign['name'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a>.</h3>
  </div>
  <div id="content">
    <form id="update_campaigns" name="update_campaigns" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" value="<?php echo $show_campaign['name'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">description:</td>
          <td><input name="description" type="text" class="entrytext" id="description" value="<?php echo $show_campaign['description'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
          <input name="campaign_id" type="hidden" id="campaign_id" value="<?php echo $show_campaign['campaign_id'] ?>" /></td>
        </tr>
      </table>
      </form>
  </div>
</div>
</body>
</html>
