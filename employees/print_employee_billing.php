<?php

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$employee_id = $_GET['employee_id'];
$get_employee = mysql_query("SELECT * FROM employees WHERE employee_id = '$employee_id'");
$show_employee = mysql_fetch_array($get_employee);

# include_once FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Start PDF generation:
$pdf = new FPDF('L', 'mm', array(36, 89));
$pdf->SetMargins(2, 6, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(85, 4, strtoupper($show_employee['first_name'] . ' ' . $show_employee['last_name']), 0, 2, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(85, 6, strtoupper($show_company['company_name']), 0, 2, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(85, 5, strtoupper($show_employee['billing_address']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_employee['billing_city'] . ' ' . $show_employee['billing_province'] . '  ' . $show_employee['billing_postal']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_employee['billing_country']), 0, 2, 'L');
$pdf->Output(strtolower($show_employee['first_name']) . '_billing_label.pdf', 'D');;

?> 