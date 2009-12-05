<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Delete file based on file_id:
$category_id = $_GET['category_id'];

# Delete the file:
$delete_item_category = mysql_query("DELETE FROM item_categories WHERE category_id = '$category_id'");

# Return to screen:
header("Location: update_item_categories.php")

?>