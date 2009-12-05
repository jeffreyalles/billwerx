<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$file_id = $_GET['file_id'];

# Delete the file:
$delete_company_file = mysql_query("DELETE FROM company_files WHERE file_id = '$file_id'");

# Return to screen:
header("Location: company_files.php")

?>