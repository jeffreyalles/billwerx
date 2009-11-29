<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$payment_id = $_GET['payment_id'];
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

$invoice_id = $show_payment['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Adjust amount due to reflect payment deletion:
$received = $show_invoice['received'] - $show_payment['amount'];
$due = $show_invoice['due'] + $show_payment['amount'];

# Update the balance of the invoice table:
$doSQL = "UPDATE invoices SET received = '$received', due = '$due' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Delete the file:
$delete_payment = mysql_query("DELETE FROM payments WHERE payment_id = '$payment_id'");

# Return to screen:
header("Location: payments.php")

?>