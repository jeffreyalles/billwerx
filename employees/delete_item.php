<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$item_id = $_GET['item_id'];

# Delete the file:
$delete_item = mysql_query("DELETE FROM items WHERE item_id = '$item_id'");

# Return to screen:
header("Location: items.php")

?>