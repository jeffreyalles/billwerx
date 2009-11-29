<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include graph:
include('../inc/phplot/phplot.php');


# Get all invoices:
$get_invoices = mysql_query("SELECT client_id, SUM(total) AS invoice_totals FROM invoices GROUP BY client_id ORDER BY invoice_totals DESC LIMIT 5");

while($show_invoice = mysql_fetch_array($get_invoices)) {
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);
$graph_data[] = array(strtoupper($show_client['last_name']), $show_invoice['invoice_totals']);
}

# Define plot:
$plot = new PHPlot(430, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Clients');
$plot->DrawGraph();

?>