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
$get_surveys = mysql_query("SELECT AVG(rating) as survey_averages FROM surveys");
$show_survey = mysql_fetch_array($get_surveys);

$graph_data[] = array($show_survey['survey_averages'], $show_survey['survey_averages']);

# Define plot:
$plot = new PHPlot(130, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataColors('red');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetYTickPos('none');
$plot->SetYTickLabelPos('none');
$plot->SetTitle('Average');
$plot->DrawGraph();

?>