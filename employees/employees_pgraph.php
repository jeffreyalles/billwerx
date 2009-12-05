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
$get_invoices = mysql_query("SELECT employee_id, SUM(total) AS invoice_totals FROM invoices GROUP BY employee_id ORDER by invoice_totals DESC LIMIT 5");

while($show_invoice = mysql_fetch_array($get_invoices)) {
$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);
$graph_data[] = array(strtoupper($show_employee['last_name']), $show_invoice['invoice_totals']);
}

# Define plot:
$plot = new PHPlot(430, 150);
$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($graph_data);
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');
$plot->SetTitle('Top Employees By Sales');
$plot->DrawGraph();

?>