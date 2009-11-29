<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$expense_id = $_GET['expense_id'];

# Delete the file:
$delete_expense = mysql_query("DELETE FROM expenses WHERE expense_id = '$expense_id'");

# Return to screen:
header("Location: expenses.php")

?>