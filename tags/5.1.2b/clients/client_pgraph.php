<?php

# Define page access level:
session_start();

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include graph:
include('../inc/phplot/phplot.php');

$client_id = $_SESSION['client_id'];

# Get all invoices:
$get_monthly_totals = mysql_query("SELECT MONTH(date_created) AS month, YEAR(date_created) AS year, SUM(total) AS total FROM invoices WHERE client_id = '$client_id' GROUP by month ORDER BY year, month LIMIT 6");

while($show_monthly_total = mysql_fetch_array($get_monthly_totals)) {
$graph_data[] = array($show_monthly_total['month'] . "-" . $show_monthly_total['year'], $show_monthly_total['total']);
};

# Define plot:
$plot = new PHPlot(430, 150);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Monthly Sales History');
$plot->DrawGraph();

?>