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
$get_client_note = mysql_query("SELECT * FROM client_notes WHERE note_id = '$note_id'");
$show_client_note = mysql_fetch_array($get_client_note);

# Define clientid to correctly forward:
$client_id = $show_client_note['client_id'];

# Delete the file:
$delete_client_note = mysql_query("DELETE FROM client_notes WHERE note_id = '$note_id'");

# Return to screen:
header("Location: client_notes.php?client_id=$client_id")

?>