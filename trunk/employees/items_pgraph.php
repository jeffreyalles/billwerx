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
$get_invoice_items = mysql_query("SELECT category_id, SUM(extended) AS extended FROM invoice_items GROUP BY category_id ORDER BY extended DESC LIMIT 3");

while($show_invoice_item = mysql_fetch_array($get_invoice_items)) {
$get_item_categories = mysql_query("SELECT * FROM item_categories WHERE category_id = " . $show_invoice_item['category_id'] . "");
$show_item_category = mysql_fetch_array($get_item_categories);
$graph_data[] = array($show_item_category['name'], $show_invoice_item['extended']);
}

# Define plot:
$plot = new PHPlot(430, 140);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Items By Category');
$plot->DrawGraph();

?>