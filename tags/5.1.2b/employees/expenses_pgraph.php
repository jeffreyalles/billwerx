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

# Get all items:
$get_expenses = mysql_query("SELECT category_id, SUM(amount) AS amount FROM expenses GROUP BY category_id ORDER BY amount DESC LIMIT 3");

while($show_expense = mysql_fetch_array($get_expenses)) {
$get_expense_categories = mysql_query("SELECT * FROM expense_categories WHERE category_id = " . $show_expense['category_id'] . "");
$show_expense_category = mysql_fetch_array($get_expense_categories);
$graph_data[] = array($show_expense_category['name'], $show_expense['amount']);
}

# Define plot:
$plot = new PHPlot(430, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Expenses By Category');
$plot->DrawGraph();

?>