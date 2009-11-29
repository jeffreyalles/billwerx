<?php

# Define page access level:
if(!isset($_SESSION)) { session_start(); };
$page_access = 1;

# Include session (security check):
include("session_check.php");

?>

<a href="profile.php">PROFILE</a> | <a href="index.php">SUMMARY</a> | <a href="suppliers.php">SUPPLIERS</a> | <a href="expenses.php">EXPENSES</a> | <a href="clients.php">CLIENTS</a> | <a href="items.php">ITEMS</a> | <a href="invoices.php">INVOICES</a> | <a href="payments.php">PAYMENTS</a> | <a href="surveys.php">SURVEYS</a> | <a href="update_company.php">COMPANY</a> | <a href="employees.php">EMPLOYEES</a> | <a href="info.php">INFO</a> | <a href="../logout.php">LOGOUT</a>