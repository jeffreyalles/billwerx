<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Get file_id from the URL:
$expense_id = $_GET['expense_id'];
$get_expense_file = mysql_query("SELECT * FROM expenses WHERE expense_id = '$expense_id'");
$show_expense_file = mysql_fetch_array($get_expense_file);

# Define values:
$type = $show_expense_file['type'];
$size = $show_expense_file['size'];
$name = $show_expense_file['name'];
$content = $show_expense_file['content'];

# Send to browser:
header("Cache-control: private");
header("Content-Type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename = $name");
header("Pragma: public");
echo $content;

?>