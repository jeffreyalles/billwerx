<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Delete file based on file_id:
$expense_id = $_GET['expense_id'];

# Delete the file:
$delete_expense = mysql_query("DELETE FROM expenses WHERE expense_id = '$expense_id'");

# Return to screen:
header("Location: expenses.php")

?>