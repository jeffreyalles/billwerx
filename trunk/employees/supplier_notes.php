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

# Get client data:
$supplier_id = $_GET['supplier_id'];
$get_suppliers = mysql_query("SELECT * FROM suppliers WHERE supplier_id = '$supplier_id'");
$show_supplier = mysql_fetch_array($get_suppliers);

# Setup query to obtain encrypted credit cards:
$get_supplier_notes = mysql_query("SELECT note_id, AES_DECRYPT(note, '$encryption_key') AS note, created AS created, employee_id AS employee_id FROM supplier_notes WHERE supplier_id = '$supplier_id'");

$total_records = mysql_num_rows($get_supplier_notes);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$note = $_POST['note'];
$supplier_id = $_POST['supplier_id'];

$employee_id = $_SESSION['employee_id'];

# Make MySQL statement:
$doSQL = "INSERT INTO supplier_notes (supplier_id, note, employee_id) VALUES ('$supplier_id', AES_ENCRYPT('$note', '$encryption_key'), '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: supplier_notes.php?supplier_id=$supplier_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Supplier Notes</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/note.png" alt="Supplier Notes" width="16" height="16" /> Supplier Notes:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="update_client" name="update_client" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="16%" class="tabletop">created:</td>
          <td class="tabletop">note:</td>
        </tr>
        <?php while($show_supplier_note = mysql_fetch_array($get_supplier_notes)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_supplier_note['employee_id'] . "") ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_supplier_note.php?note_id=<?php echo $show_supplier_note['note_id'] ?>"><img src="../images/icons/delete.png" alt="Delete Note" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><?php echo $show_supplier_note['created'] ?><br />
            <span class="smalltext"><a href="mailto:<?php echo $show_employee['email_address'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a></span></td>
          <td class="tablerowborder"><?php echo nl2br($show_supplier_note['note']) ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><textarea name="note" class="entrybox" id="note"></textarea></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
            <input name="back" type="button" class="button" id="back" onclick="javascript:history.go(-1)" value="BACK" />
          <input name="supplier_id" type="hidden" id="supplier_id" value="<?php echo $show_supplier['supplier_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
