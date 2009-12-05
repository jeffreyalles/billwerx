<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Delete file based on file_id:
$file_id = $_GET['file_id'];

# Delete the file:
$delete_company_file = mysql_query("DELETE FROM company_files WHERE file_id = '$file_id'");

# Return to screen:
header("Location: company_files.php")

?>