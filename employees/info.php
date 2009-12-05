<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

# include_once session check and database connection:
include_once("../inc/license.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

mysql_select_db($dname);

$result = mysql_query("SHOW TABLE STATUS");
$dbsize = 0;
while( $row = mysql_fetch_array( $result ) ) {  
$dbsize += $row["Data_length"] + $row["Index_length"];
}


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
  <div id="header"><img src="../global/company_logo.php" alt="<?php echo $show_company['company_name'] ?> - powered by: Billwerx" /></div>
  <div id="logininfo">
    <?php include_once("login_info.php") ?>
  </div>
  <div id="navbar">
    <?php include_once("navbar.php") ?>
  </div>
  <div id="content">
    <form id="employees" name="employees" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>License:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">registered owner:</td>
                <td><?php echo $registered_owner ?></td>
              </tr>
              <tr>
                <td class="firstcell">operating domain:</td>
                <td><?php echo $operating_domain ?></td>
              </tr>
              <tr>
                <td class="firstcell">registration key:</td>
                <td><?php echo $registration_key ?></td>
              </tr>
          </table></td>
          <td class="halftopcell"><h2>Credits:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">script developer:</td>
                <td>Matthew Kinderwater</td>
              </tr>
              <tr>
                <td class="firstcell">accounting supervision:</td>
                <td>Julie Elliott</td>
              </tr>
              <tr>
                <td class="firstcell">icons and images:</td>
                <td>Mark James</td>
              </tr>
              <tr>
                <td class="firstcell">pdf creator:</td>
                <td>Olivier Christen</td>
              </tr>
              <tr>
                <td class="firstcell">html 2 pdf parser:</td>
                <td>Clément Lavoillotte</td>
              </tr>
              <tr>
                <td class="firstcell">e-mail class:</td>
                <td>Codeworx Technologies</td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td class="tabletop">operating version:</td>
          <td width="14%" class="tabletop">released version:</td>
          <td width="14%" class="tabletop">php version:</td>
          <td width="14%" class="tabletop">mysql version:</td>
          <td width="14%" class="tabletop">database name:</td>
          <td width="14%" class="tabletop">database size (kb):</td>
        </tr>
        <tr class="tablelist">
          <td class="tablerowborder"><?php echo $version ?></td>
          <td class="tablerowborder">-.--<br />
            <a href="#"><span class="smalltext">release notes</span></a></td>
          <td class="tablerowborder"><?php echo phpversion() ?><br />
            <a href="javascript:openWindow('phpinfo.php')"><span class="smalltext">phpinfo</span></a></td>
          <td class="tablerowborder"><?php echo mysql_get_server_info() ?></td>
          <td class="tablerowborder"><?php echo $dname ?></td>
          <td class="tablerowborder"><?php echo number_format(($dbsize / 1024), 2) ?></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="repair" type="button" class="button" id="repair" onclick="openWindow('repair_database.php')" value="REPAIR" />
          <input name="backup" type="button" class="button" id="backup" onclick="window.location='backup_database.php'" value="BACKUP" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
