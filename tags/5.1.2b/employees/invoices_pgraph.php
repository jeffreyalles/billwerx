<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include graph:
include('../inc/phplot/phplot.php');

# Get all invoices:
#$get_invoices = mysql_query("SELECT MONTH(date_created) AS month, YEAR(date_created) AS year, SUM(total) AS invoice_totals FROM invoices GROUP BY MONTH(date_created) ORDER BY year, month LIMIT 6");
$get_total_profits = mysql_query("SELECT MONTH(date_created) AS month, YEAR(date_created) AS year, SUM(total_profit) AS total_profits FROM invoices GROUP BY MONTH(date_created) ORDER BY year, month LIMIT 6");

while($show_total_profit = mysql_fetch_array($get_total_profits)) {
$graph_data[] = array($show_total_profit['month'] . "-" . $show_total_profit['year'], $show_total_profit['total_profits']);
};

# Define plot:
$plot = new PHPlot(430, 150);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Monthly Sales Profits');
$plot->DrawGraph();

?>