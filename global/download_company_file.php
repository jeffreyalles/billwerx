<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get file_id from the URL:
$file_id = $_GET['file_id'];
$get_company_file = mysql_query("SELECT * FROM company_files WHERE file_id = '$file_id'");
$show_company_file = mysql_fetch_array($get_company_file);

# Include security check (URL manipulation):
# Security flaw patched RC 5.2 (2009/10/10 - only download company file you are authorized to):
if((!isset($_SESSION['employee_id'])) AND (!isset($_SESSION['client_id']))) {
header("Location: ../restricted.php");
exit;
};

# Define values:
$type = $show_company_file['type'];
$size = $show_company_file['size'];
$name = $show_company_file['name'];
$content = $show_company_file['content'];

# Send to browser:
header("Cache-control: private");
header("Content-Type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename = $name");
header("Pragma: public");
echo $content;

?>