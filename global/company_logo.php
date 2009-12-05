<?php

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get file_id from the URL:
$get_company_logo = mysql_query("SELECT * FROM company");
$show_company_logo = mysql_fetch_array($get_company_logo);

# Include security check (URL manipulation):
#session_start();
#if((isset($_SESSION['client_id']) and ($_SESSION['client_id'] != $show_company_file['client_id'])) {
#header("Location: restricted.php");
#exit;
#};

# Define values:
#$type = $show_company_file['type'];
$size = $show_company_logo['logo_size'];
$name = $show_company_logo['logo_name'];
$content = $show_company_logo['logo_content'];
$type = $show_company_logo['logo_type'];

# Send to browser:
header("Content-type: $type");
header("Content-length: $size");
echo $content;

?>