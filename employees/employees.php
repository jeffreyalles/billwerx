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

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $_SESSION['records_per_page']);
$next_page = ($start + $_SESSION['records_per_page']);

# Get invoice data:
$get_total_employees = mysql_query("SELECT * FROM employees");
$total_records = mysql_num_rows($get_total_employees);
$get_employees = mysql_query("SELECT * FROM employees LIMIT $start, " . $_SESSION['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_employees = mysql_query("SELECT * FROM employees WHERE (last_name LIKE '%$query%') OR (first_name LIKE '%$query%')");
$total_records = mysql_num_rows($get_employees);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Employees</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
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
    <form id="employees" name="employees" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h1><img src="../images/icons/employees.png" alt="Employees" width="16" height="16" /> Employees:</h1>
          <table class="fulltable">
              <tr>
                <td class="justred">Found <?php echo $total_records ?> record(s).</td>
              </tr>
              <tr>
                <td><input name="query" type="text" class="entrytext" id="query" onclick="this.value=''" value="search query" /></td>
              </tr>
              
              <tr>
                <td><input name="create" type="button" class="button" id="create" onclick="window.location='create_employee.php'" value="CREATE" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="employees_pgraph.php" alt="Top Employees By Sales" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="10%" class="tabletop">employee #:</td>
          <td class="tabletop">full name:</td>
          <td width="26%" class="tabletop">billing address:</td>
          <td width="12%" class="tabletop">work number:</td>
          <td width="12%" class="tabletop">mobile number:</td>
          <td width="12%" class="tabletop">access level:</td>
        </tr>
        <?php while($show_employee = mysql_fetch_array($get_employees)) { ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="export_employee_vcard.php?employee_id=<?php echo $show_employee['employee_id'] ?>"><img src="../images/icons/vcard.png" alt="Export VCard" width="16" height="16" class="iconspacer" /></a> <a href="mailto:<?php echo $show_employee['email_address'] ?>"><img src="../images/icons/email.png" alt="E-mail" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="invoices.php?employee_id=<?php echo $show_employee['employee_id'] ?>"><?php echo $show_employee['employee_id'] ?></a></td>
          <td class="tablerowborder"><a href="update_employee.php?employee_id=<?php echo $show_employee['employee_id'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_employee['email_address'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_employee['billing_address'] ?><br />
          <?php echo $show_employee['billing_city'] ?> <?php echo $show_employee['billing_postal'] ?><?php echo $show_employee['billing_postal'] ?><br />
          <?php echo $show_employee['billing_country'] ?></td>
          <td class="tablerowborder"><?php echo $show_employee['work_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_employee['mobile_number'] ?></td>
          <td class="tablerowborder"><span class="justred"><?php echo $show_employee['access_level'] ?></span></td>
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
