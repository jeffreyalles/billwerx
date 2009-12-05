<?php

# Include session check and database connection:
include("../inc/dbconfig.php");

#Obtain query to get all clients:
$get_total_clients = mysql_query("SELECT * FROM clients");

while($show_client = mysql_fetch_array($get_total_clients)) {

$client_id = $show_client['client_id'];

# Define current numbers:
$home_number = $show_client['home_number'];
$work_number = $show_client['work_number'];
$mobile_number = $show_client['mobile_number'];

# Define new number:
$primary_number = $show_client['primary_number'];

if(!empty($home_number)) {
$primary_number = $home_number;
};

if(!empty($work_number)) {
$primary_number = $work_number;
};

if(!empty($mobile_number)) {
$primary_number = $mobile_number;
};

$active = "1";

# Assign values to a database table:
$doSQL = "UPDATE clients SET active = '$active', primary_number = '$primary_number' WHERE client_id = '$client_id'";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

};

?>