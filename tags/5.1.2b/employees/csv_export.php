<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

$get_invoices = mysql_query("SELECT invoice_id, date_created, purpose, purchase_order, tax1_percent, tax2_percent, tax1_total, tax2_total, total, received, due FROM invoices");
$columns = mysql_num_fields($get_invoices);

# Get names for column:
for ($counter = 0; $counter < $columns; $counter++) {
$column_name = mysql_field_name($get_invoices, $counter);
$out .= '"' . strtoupper($column_name) . '",';
}
$out .= "\n";
	
# Get table data:
while ($column_data = mysql_fetch_array($get_invoices)) {
for ($counter = 0; $counter < $columns; $counter++) {
$out .='"'.$column_data["$counter"].'",';
}
$out .="\n";
}

# Send to browser:
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=invoice_export.csv");
header("Pragma: public");
echo $out;

?>