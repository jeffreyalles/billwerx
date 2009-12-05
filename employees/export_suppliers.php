<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_suppliers = mysql_query("SELECT * FROM suppliers");
$columns = mysql_num_fields($get_suppliers);

# Get names for column:
for ($counter = 0; $counter < $columns; $counter++) {
$column_name = mysql_field_name($get_suppliers, $counter);
$out .= '"' . strtoupper($column_name) . '",';
}
$out .= "\n";
	
# Get table data:
while ($column_data = mysql_fetch_array($get_suppliers)) {
for ($counter = 0; $counter < $columns; $counter++) {
$out .='"'.$column_data["$counter"].'",';
}
$out .="\n";
}

# Send to browser:
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=suppliers_export.csv");
header("Pragma: public");
echo $out;

?>