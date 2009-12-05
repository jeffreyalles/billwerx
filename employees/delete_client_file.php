<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$file_id = $_GET['file_id'];

// Search database based on client id:
$get_client_file = mysql_query("SELECT * FROM client_files WHERE file_id = '$file_id'");
$show_client_file = mysql_fetch_array($get_client_file);

# Define clientid to correctly forward:
$client_id = $show_client_file['client_id'];

# Delete the file:
$delete_client_file = mysql_query("DELETE FROM client_files WHERE file_id = '$file_id'");

# Return to screen:
header("Location: client_files.php?client_id=$client_id")

?>