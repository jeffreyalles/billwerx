<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$client_id = $_SESSION['client_id'];
$get_client_files = mysql_query("SELECT * FROM client_files WHERE client_id = '$client_id'");
$get_company_files = mysql_query("SELECT * FROM company_files WHERE public = 1");

# Get invoices:
$get_invoices = mysql_query("SELECT * FROM invoices WHERE client_id = '$client_id' ORDER BY invoice_id DESC");

# Get unpaid invoices:
$get_unpaid_invoices = mysql_query("SELECT * FROM invoices WHERE client_id = '$client_id' AND due != 0 ORDER BY invoice_id DESC");

# Get payments:
$get_payments = mysql_query("SELECT * FROM payments WHERE client_id = '$client_id' ORDER BY invoice_id DESC");

# Setup query to obtain encrypted credit cards:
$get_client_notes = mysql_query("SELECT note_id, AES_DECRYPT(note, '$encryption_key') AS note, created AS created, employee_id AS employee_id FROM client_notes WHERE client_id = '$client_id'");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Client Files</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/tooltip.js"></script>
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
    <table class="fulltable">
      <tr>
        <td class="tabletop">filename:</td>
        <td width="14%" class="tabletop">date created:</td>
        <td width="14%" class="tabletop">size (kb):</td>
      </tr>
      <?php while($show_client_file = mysql_fetch_array($get_client_files)) { ?>
      <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_client_file['employee_id'] . ""); ?>
      <?php $show_employee = mysql_fetch_array($get_employees) ?>
      <tr class="tablelist">
        <td class="tablerowborder"><a href="../global/download_client_file.php?file_id=<?php echo $show_client_file['file_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_client_file['file_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_client_file['file_id'] ?>')"><?php echo $show_client_file['name'] ?></a><br />
          <span class="smalltext"><?php echo $show_client_file['type'] ?></span>
          <div class="tooltip" id="<?php echo $show_client_file['file_id'] ?>">
            <table>
              <tr>
                <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                  <span class="smalltext"><?php echo $show_invoice['created'] ?></span></td>
              </tr>
              <tr>
                <td><?php echo $show_client_file['description'] ?></td>
              </tr>
            </table>
          </div></td>
        <td class="tablerowborder"><span class="smalltext"><?php echo $show_client_file['created'] ?></span></td>
        <td class="tablerowborder"><?php echo number_format(($show_client_file['size'] / 1024), 2) ?></td>
      </tr>
      <?php } ?>
      <?php while($show_company_file = mysql_fetch_array($get_company_files)) { ?>
      <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_company_file['employee_id'] . ""); ?>
      <?php $show_employee = mysql_fetch_array($get_employees) ?>
      <tr class="tablelist">
        <td class="tablerowborder"><a href="../global/download_company_file.php?file_id=<?php echo $show_company_file['file_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_company_file['file_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_company_file['file_id'] ?>')"><?php echo $show_company_file['name'] ?></a><br />
          <span class="smalltext"><?php echo $show_company_file['type'] ?></span>
          <div class="tooltip" id="<?php echo $show_company_file['file_id'] ?>">
            <table>
              <tr>
                <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                  <span class="smalltext"><?php echo $show_company_file['created'] ?></span></td>
              </tr>
              <tr>
                <td><?php echo $show_company_file['description'] ?></td>
              </tr>
            </table>
          </div></td>
        <td class="tablerowborder"><span class="smalltext"><?php echo $show_company_file['created'] ?></span></td>
        <td class="tablerowborder"><?php echo number_format(($show_company_file['size'] / 1024), 2) ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
</body>
</html>
