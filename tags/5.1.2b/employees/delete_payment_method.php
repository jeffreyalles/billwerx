<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$method_id = $_GET['method_id'];

# Delete the file:
$delete_payment_method = mysql_query("DELETE FROM payment_methods WHERE method_id = '$method_id'");

# Return to screen:
header("Location: update_payment_methods.php")

?>