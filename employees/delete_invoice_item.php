<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$invoice_item_id = $_GET['invoice_item_id'];

// Search database based on client id:
$get_invoice_item = mysql_query("SELECT * FROM invoice_items WHERE invoice_item_id = '$invoice_item_id'");
$show_invoice_item = mysql_fetch_array($get_invoice_item);

# Define clientid to correctly forward:
$invoice_id = $show_invoice_item['invoice_id'];

// Search database based on client id:
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Define values to remove from invoice values:
$tax1_total = $show_invoice['tax1_total'] - $show_invoice_item['tax1_value'];
$tax2_total = $show_invoice['tax2_total'] - $show_invoice_item['tax2_value'];
$subtotal = $show_invoice['subtotal'] - $show_invoice_item['extended'];
$total = $show_invoice['total'] - $show_invoice_item['extended'] - $show_invoice_item['tax1_value'] - $show_invoice_item['tax2_value'];
$total_cost = $show_invoice['total_cost'] - $show_invoice_item['extended_cost'];
$total_profit = $show_invoice['total_profit'] - $show_invoice_item['extended_profit'];
$due = $total - $show_invoice['received'];

# Update the balance of the invoice table:
$doSQL = "UPDATE invoices SET tax1_total = '$tax1_total', tax2_total = '$tax2_total', subtotal = '$subtotal', total_cost = '$total_cost', total_profit = '$total_profit', total = '$total', due = '$due' WHERE invoice_id = '$invoice_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Delete the file:
$delete_invoice_item = mysql_query("DELETE FROM invoice_items WHERE invoice_item_id = '$invoice_item_id'");

# Return to screen:
header("Location: update_invoice.php?invoice_id=$invoice_id")

?>