<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# If the size of the file is greater than zero (0) process:
if((isset($_POST['update'])) AND (($_FILES['file']['size'] > 0))) {

# Define POST file variables:
$logo_name = addslashes($_FILES['file']['name']);
$temp_name  = $_FILES['file']['tmp_name'];
$logo_size = $_FILES['file']['size'];
$logo_type = $_FILES['file']['type'];

$readfile = fopen($temp_name, 'r');
$logo_content = fread($readfile, filesize($temp_name));
$logo_content = addslashes($logo_content);
fclose($readfile);

# Assign values to a database table:
$doSQL = "UPDATE company SET logo_name = '$logo_name', logo_size = '$logo_size', logo_content = '$logo_content', logo_type = '$logo_type'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: company_logo.php");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Update Company Logo</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h2>Company Logo:</h2>
    <h3>You can update the company image used to create invoices.</h3>
    <p>For best results on invoices, receipts, and Billwerx pages uploaded company logo's should be 180px by 50px and in JPEG or GIF format.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table class="fulltable">
        <tr>
          <td class="firstcell">upload:<br />
            <a href="../global/company_logo.php">download current</a></td>
          <td><input name="file" type="file" class="button" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="update" type="submit" class="button" id="update" value="UPDATE" />
          <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
