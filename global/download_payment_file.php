<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get file_id from the URL:
$payment_id = $_GET['payment_id'];
$get_payment_file = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment_file = mysql_fetch_array($get_payment_file);

# Include security check (URL manipulation):
if((!isset($_SESSION['employee_id'])) AND ($show_payment_file['client_id'] != $_SESSION['client_id'])) {
header("Location: ../restricted.php");
exit;
};

# Define values:
$type = $show_payment_file['type'];
$size = $show_payment_file['size'];
$name = $show_payment_file['name'];
$content = $show_payment_file['content'];

# Send to browser:
header("Cache-control: private");
header("Content-Type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename = $name");
header("Pragma: public");
echo $content;

?>