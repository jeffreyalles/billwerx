<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

# Include session check and database connection:
include("../inc/license.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Info</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/information.png" alt="Information" width="16" height="16" /> Overdue:</h1>
    <p>The automated tasks are designed to simpilfy billing tasks.</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="employees" name="employees" method="post" action="e-mail_overdue_invoice.php">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><table class="fulltable">
            <tr>
              <td class="firstcell">invoice overdue:</td>
              <td><select name="days_late" class="entrytext" id="days_late">
                  <option value="0 days">0 days</option>
                  <option value="7 days">7 days</option>
                  <option value="14 days">14 days</option>
                  <option value="28 days">28 days</option>
              </select></td>
            </tr>
            <tr>
              <td class="firstcell">&nbsp;</td>
              <td><input name="create" type="submit" class="button" id="create" onclick="window.location='create_invoice.php'" value="REMIND" /></td>
            </tr>
          </table></td>
          <td class="halftopcell">&nbsp;</td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td class="tabletop">reoccuring invoice:</td>
          <td width="14%" class="tabletop">client:</td>
          <td width="14%" class="tabletop">php version:</td>
          <td width="14%" class="tabletop">expiration:</td>
          <td width="14%" class="tabletop">reoccuring amoutn:</td>
        </tr>
        <tr class="tablelist">
          <td class="tablerowborder">&nbsp;</td>
          <td class="tablerowborder">#11 - FIX DESKTOP COMPUTER</td>
          <td class="tablerowborder">&nbsp;</td>
          <td class="tablerowborder">&nbsp;</td>
          <td class="tablerowborder">&nbsp;</td>
          <td class="tablerowborder">&nbsp;</td>
        </tr>
      </table>
      </form>
  </div>
</div>
</body>
</html>
