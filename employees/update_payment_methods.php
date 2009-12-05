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

$get_payment_methods = mysql_query("SELECT * FROM payment_methods");

$total_records = mysql_num_rows($get_payment_methods);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$name = strtoupper($_POST['name']);

# Make MySQL statement:
$doSQL = "INSERT INTO payment_methods (name) VALUES ('$name')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: update_payment_methods.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Payment Methods</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('name').focus()">
<div id="smallwrap">
  <div id="header">
    <h2>Update Payment Methods:</h2>
    <h3>Found <?php echo $total_records ?> record(s).</h3>
  </div>
  <div id="content">
    <form id="update_payment_methods" name="update_payment_methods" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
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
        <?php while($show_payment_method = mysql_fetch_array($get_payment_methods)) { ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_payment_method.php?method_id=<?php echo $show_payment_method['method_id'] ?>"><img src="../images/icons/delete.png" alt="Delete Item Category" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><?php echo $show_payment_method['name'] ?></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
