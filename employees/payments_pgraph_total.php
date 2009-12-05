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
$get_payments = mysql_query("SELECT SUM(amount) AS amount_totals FROM payments");
$show_payment = mysql_fetch_array($get_payments);

$graph_data[] = array($show_payment['amount_totals'], $show_payment['amount_totals']);

# Define plot:
$plot = new PHPlot(130, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataColors('red');
$plot->SetDataValues($graph_data);
$plot->SetXTickPos('none');
$plot->SetXTickLabelPos('none');
$plot->SetYTickPos('none');
$plot->SetYTickLabelPos('none');
$plot->SetTitle('Totals');
$plot->DrawGraph();

?>