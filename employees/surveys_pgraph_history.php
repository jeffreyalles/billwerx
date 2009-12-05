<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once graph:
include_once('../inc/phplot/phplot.php');


# Get all invoices:
$get_surveys = mysql_query("SELECT * FROM surveys ORDER BY invoice_id DESC LIMIT 9");

while($show_survey = mysql_fetch_array($get_surveys)) {
$graph_data[] = array($show_survey['invoice_id'], $show_survey['rating']);
}

# Define plot:
$plot = new PHPlot(300, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Results By Invoice');
$plot->DrawGraph();

?>