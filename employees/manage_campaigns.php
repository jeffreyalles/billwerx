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

$get_campaigns = mysql_query("SELECT * FROM campaigns");
$total_records = mysql_num_rows($get_campaigns);

$get_total_clients = mysql_query("SELECT * FROM clients");
$show_total_clients = mysql_num_rows($get_total_clients);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$name = strtoupper($_POST['name']);
$description = strtolower($_POST['description']);

$employee_id = $_SESSION['employee_id'];

# Make MySQL statement:
$doSQL = "INSERT INTO campaigns (name, description, employee_id) VALUES ('$name', '$description', '$employee_id')";

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
<title><?php echo $show_company['company_name'] ?> - Manage Campaigns</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body onload="document.getElementById('name').focus()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/expenses.png" alt="Update Expense Categories" width="16" height="16" /> Manage Campaigns:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
  </div>
  <div id="content">
    <form id="update_campaigns" name="update_campaigns" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">name:</td>
          <td><input name="name" type="text" class="entrytext" id="name" /></td>
        </tr>
        <tr>
          <td class="firstcell">description:</td>
          <td><input name="description" type="text" class="entrytext" id="description" value="" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
          <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td class="tabletop">name:</td>
          <td width="16%" class="tabletop">quantity:</td>
          <td width="16%" class="tabletop">ratio:</td>
        </tr>
        <?php while($show_campaign = mysql_fetch_array($get_campaigns)) { ?>
        <?php $get_clients = mysql_query("SELECT * FROM clients WHERE campaign_id = " . $show_campaign['campaign_id'] . ""); ?>
        <?php $show_clients = mysql_num_rows($get_clients) ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_campaign['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="update_campaign.php?campaign_id=<?php echo $show_campaign['campaign_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_campaign['campaign_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_campaign['campaign_id'] ?>')"><?php echo $show_campaign['name'] ?></a>
            <div class="tooltip" id="<?php echo $show_campaign['campaign_id'] ?>">
              <table>
                <tr>
                  <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?> <?php echo $show_employee['first_name'] ?></span><br>
                    <span class="smalltext"><?php echo $show_campaign['created'] ?></span></td>
                </tr>
                <tr>
                  <td><?php echo $show_campaign['description'] ?></td>
                </tr>
              </table>
            </div></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_clients ?></span></td>
          <td class="tablerowborder"><span class="justred"><?php echo round(($show_clients / $show_total_clients) * 100) ?>%</span></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
