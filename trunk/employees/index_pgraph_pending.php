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

$get_invoices = mysql_query("SELECT SUM(due) as pending_due FROM invoices WHERE due != 0");

while($show_invoice = mysql_fetch_array($get_invoices)) {
$graph_data[] = array($show_invoice['pending_due'], $show_invoice['pending_due']);
}

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
$plot->SetTitle('Pending');
$plot->DrawGraph();

?>