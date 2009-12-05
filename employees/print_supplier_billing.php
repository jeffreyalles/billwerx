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
$supplier_id = $_GET['supplier_id'];
$get_supplier = mysql_query("SELECT * FROM suppliers WHERE supplier_id = '$supplier_id'");
$show_supplier = mysql_fetch_array($get_supplier);

# include_once FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Start PDF generation:
$pdf = new FPDF('L', 'mm', array(36, 89));
$pdf->SetMargins(2, 6, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(85, 4, strtoupper($show_supplier['first_name'] . ' ' . $show_supplier['last_name']), 0, 2, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(85, 6, strtoupper($show_supplier['company_name']), 0, 2, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(85, 5, strtoupper($show_supplier['billing_address']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_supplier['billing_city'] . ' ' . $show_supplier['billing_province'] . '  ' . $show_supplier['billing_postal']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_supplier['billing_country']), 0, 2, 'L');
$pdf->Output(strtolower($show_supplier['first_name']) . '_billing_label.pdf', 'D');

?> 