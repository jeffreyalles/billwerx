<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

// Show all information, defaults to INFO_ALL
phpinfo();

?>