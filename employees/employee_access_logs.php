<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$employee_id = $_GET['employee_id'];
$get_employee_access_logs = mysql_query("SELECT * FROM employee_access_logs WHERE employee_id = '$employee_id'");

$total_records = mysql_num_rows($get_employee_access_logs);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?>- Employee Access Logs</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h2>Employee Access Logs:</h2>
    <h3>Found <?php echo $total_records ?> record(s).</h3>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="client_files" id="client_files">
      <table class="fulltable">
        <tr>
          <td class="tabletop">ip address / hostname:</td>
          <td width="32%" class="tabletop">created:</td>
        </tr>
        <?php while($show_employee_access_log = mysql_fetch_array($get_employee_access_logs)) { ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="javascript:copyText('<?php echo $show_employee_access_log['ipv4_address'] ?>')"><?php echo $show_employee_access_log['ipv4_address'] ?></a><br />
            <span class="smalltext"><?php echo $show_employee_access_log['hostname'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_employee_access_log['created'] ?></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
