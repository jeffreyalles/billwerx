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

# Get all items:
$get_expenses = mysql_query("SELECT supplier_id, SUM(amount) AS amount FROM expenses GROUP BY supplier_id ORDER BY amount DESC LIMIT 3");

while($show_expense = mysql_fetch_array($get_expenses)) {
$get_suppliers = mysql_query("SELECT * FROM suppliers WHERE supplier_id = " . $show_expense['supplier_id'] . "");
$show_suppliers = mysql_fetch_array($get_suppliers);
$graph_data[] = array(strtoupper($show_suppliers['last_name']), $show_expense['amount']);
}

# Define plot:
$plot = new PHPlot(430, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Suppliers');
$plot->DrawGraph();

?>