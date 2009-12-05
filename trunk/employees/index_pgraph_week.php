<?php

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once graph:
include_once('../inc/phplot/phplot.php');

# Get all invoices:
$get_invoices = mysql_query("SELECT WEEK(date_created) AS week, MONTH(date_created) AS month, SUM(total) AS invoice_totals FROM invoices GROUP BY WEEK(date_created) ORDER BY week LIMIT 14");

while($show_invoice = mysql_fetch_array($get_invoices)) {
$graph_data[] = array($show_invoice['week'] . "-" . $show_invoice['month'], $show_invoice['invoice_totals']);
}

# Define plot:
$plot = new PHPlot(430, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickPos('none');
$plot->SetXTickLabelPos('none');
$plot->SetTitle('Weekly Sales Volume');
$plot->DrawGraph();

?>