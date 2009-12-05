<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
$start = $_GET['start']; if (empty($start)) $start = 0;
$previous_page = ($start - $_SESSION['records_per_page']);
$next_page = ($start + $_SESSION['records_per_page']);

# Get invoice data:
$get_total_company_files = mysql_query("SELECT * FROM company_files");
$total_records = mysql_num_rows($get_total_company_files);
$get_company_files = mysql_query("SELECT * FROM company_files LIMIT $start, " . $_SESSION['records_per_page'] . "");

# Start search:
if(isset($_POST['query'])) {
$within = $_POST['within'];
$query = $_POST['query'];
$get_clients = mysql_query("SELECT * FROM clients WHERE $within LIKE '%$query%' LIMIT $start, " . $_SESSION['records_per_page'] . "");
};

# If the size of the file is greater than zero (0) process:
if($_FILES['file']['size'] > 0) {

# Define POST file variables:
$public = $_POST['public'];

$name = addslashes($_FILES['file']['name']);
$temp_name  = $_FILES['file']['tmp_name'];
$size = $_FILES['file']['size'];
$type = $_FILES['file']['type'];

$description = strtolower($_POST['description']);

$employee_id = $_SESSION['employee_id'];

$readfile = fopen($temp_name, 'r');
$content = fread($readfile, filesize($temp_name));
$content = addslashes($content);
fclose($readfile);

# Assign values to a database table:
$doSQL = "INSERT INTO company_files (name, size, type, content, public, description, employee_id) VALUES ('$name', '$size', '$type', '$content', '$public', '$description', '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: company_files.php");

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Company Files</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
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
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="company" id="company">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><table class="fulltable">
              <tr>
                <td class="firstcell">public:</td>
                <td><input name="public" type="checkbox" id="public" value="1" /></td>
              </tr>
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
                <td><input name="upload" type="submit" class="button" id="upload" value="UPLOAD" /></td>
              </tr>
            </table></td>
          <td class="halftopcell">&nbsp;</td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td class="tabletop">filename</td>
          <td width="14%" class="tabletop">data created</td>
          <td width="14%" class="tabletop">size (kb):</td>
          <td width="8%" class="tabletop">public:</td>
        </tr>
        <?php while($show_company_file = mysql_fetch_array($get_company_files)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_company_file['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_company_file.php?file_id=<?php echo $show_company_file['file_id'] ?>"><img src="../images/icons/delete.png" alt="Delete File" width="16" height="16" class="iconspacer" /></a></td>
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
          <td class="tablerowborder"><?php echo $show_company_file['created'] ?></td>
          <td class="tablerowborder"><?php echo number_format(($show_company_file['size'] / 1024), 2) ?></td>
          <td class="tablerowborder"><?php echo $show_company_file['public'] ?></td>
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
