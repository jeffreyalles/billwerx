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
$get_invoices = mysql_query("SELECT MONTH(date_created) as month, DAY(date_created) AS day, SUM(total) AS invoice_totals FROM invoices GROUP BY month, day ORDER BY month DESC, day DESC LIMIT 9");

while($show_invoice = mysql_fetch_array($get_invoices)) {
$graph_data[] = array($show_invoice['month'] . "-" . $show_invoice['day'], $show_invoice['invoice_totals']);
#$graph_data[] = array($show_invoice['day'], $show_invoice['invoice_totals']);
}

# Define plot:
$plot = new PHPlot(300, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetYDataLabelPos('plotright');
$plot->SetTitle('Daily Sales Volume');
$plot->DrawGraph();

?>