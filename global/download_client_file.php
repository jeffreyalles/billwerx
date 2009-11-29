<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get file_id from the URL:
$file_id = $_GET['file_id'];
$get_client_file = mysql_query("SELECT * FROM client_files WHERE file_id = '$file_id'");
$show_client_file = mysql_fetch_array($get_client_file);

if((!isset($_SESSION['employee_id'])) AND ($show_client_file['client_id'] != $_SESSION['client_id'])) {
header("Location: ../restricted.php");
exit;
};

# Define values:
$type = $show_client_file['type'];
$size = $show_client_file['size'];
$name = $show_client_file['name'];
$content = $show_client_file['content'];

# Send to browser:
header("Cache-control: private");
header("Content-Type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename = $name");
header("Pragma: public");
echo $content;

?>