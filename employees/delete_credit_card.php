<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Delete file based on file_id:
$credit_card_id = $_GET['credit_card_id'];

// Search database based on client id:
$get_credit_card = mysql_query("SELECT * FROM credit_cards WHERE credit_card_id = '$credit_card_id'");
$show_credit_card = mysql_fetch_array($get_credit_card);

# Define clientid to correctly forward:
$client_id = $show_credit_card['client_id'];

# Delete the file:
$delete_credit_card = mysql_query("DELETE FROM credit_cards WHERE credit_card_id = '$credit_card_id'");

# Return to screen:
header("Location: credit_cards.php?client_id=$client_id")

?>