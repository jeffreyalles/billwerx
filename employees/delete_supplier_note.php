<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$note_id = $_GET['note_id'];

// Search database based on client id:
$get_supplier_note = mysql_query("SELECT * FROM supplier_notes WHERE note_id = '$note_id'");
$show_supplier_note = mysql_fetch_array($get_supplier_note);

# Define clientid to correctly forward:
$supplier_id = $show_supplier_note['supplier_id'];

# Delete the file:
$delete_supplier_note = mysql_query("DELETE FROM supplier_notes WHERE note_id = '$note_id'");

# Return to screen:
header("Location: supplier_notes.php?supplier_id=$supplier_id")

?>