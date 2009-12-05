<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$client_id = $_GET['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Get files:
$get_client_files = mysql_query("SELECT * FROM client_files WHERE client_id = '$client_id'");

$total_records = mysql_num_rows($get_client_files);

# If the size of the file is greater than zero (0) process:
if((isset($_POST['upload'])) AND (($_FILES['file']['size'] > 0))) {

# Define POST file variables:
$name = addslashes($_FILES['file']['name']);
$temp_name  = $_FILES['file']['tmp_name'];
$size = $_FILES['file']['size'];
$type = $_FILES['file']['type'];

$description = strtolower($_POST['description']);

$client_id = $_POST['client_id'];

$employee_id = $_SESSION['employee_id'];

$readfile = fopen($temp_name, 'r');
$content = fread($readfile, filesize($temp_name));
$content = addslashes($content);
fclose($readfile);

# Assign values to a database table:
$doSQL = "INSERT INTO client_files (client_id, name, size, type, content, description, employee_id) VALUES ('$client_id', '$name', '$size', '$type', '$content', '$description', '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: client_files.php?client_id=$client_id");

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Client Files</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/files.png" alt="Client Files" width="16" height="16" /> Client Files:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="client_files" id="client_files">
      <table class="fulltable">
        <tr>
          <td class="firstcell">upload file:</td>
          <td><input name="file" type="file" class="button" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">description:</td>
          <td><input name="description" type="text" class="entrytext" id="description" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="upload" type="submit" class="button" id="upload" value="UPLOAD" />
            <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" />
          <input name="client_id" type="hidden" id="client_id" value="<?php echo $show_client['client_id'] ?>" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="10%" class="tabletop">&nbsp;</td>
          <td class="tabletop">filename:</td>
          <td width="20%" class="tabletop">size (kb):</td>
        </tr>
        <?php while($show_client_file = mysql_fetch_array($get_client_files)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_client_file['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_client_file.php?file_id=<?php echo $show_client_file['file_id'] ?>"><img src="../images/icons/delete.png" alt="Delete File" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="../global/download_client_file.php?file_id=<?php echo $show_client_file['file_id'] ?>" onmouseover="tooltip(event, '<?php echo $show_client_file['file_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_client_file['file_id'] ?>')"><?php echo $show_client_file['name'] ?></a>
            <div class="tooltip" id="<?php echo $show_client_file['file_id'] ?>">
              <table>
                <tr>
                  <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                    <span class="smalltext"><?php echo $show_client_file['created'] ?></span></td>
                </tr>
                <tr>
                  <td><?php echo $show_client_file['description'] ?></td>
                </tr>
              </table>
            </div></td>
          <td class="tablerowborder"><?php echo number_format(($show_client_file['size'] / 1024), 2) ?></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
