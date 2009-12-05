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
$client_id = $_GET['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# include_once FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Start PDF generation:
$pdf = new FPDF('L', 'mm', array(36, 89));
$pdf->SetMargins(2, 6, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(85, 4, strtoupper($show_client['first_name'] . ' ' . $show_client['last_name']), 0, 2, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(85, 6, strtoupper($show_client['company_name']), 0, 2, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(85, 5, strtoupper($show_client['billing_address']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_client['billing_city'] . ' ' . $show_client['billing_province'] . '  ' . $show_client['billing_postal']), 0, 2, 'L');
$pdf->Cell(85, 5, strtoupper($show_client['billing_country']), 0, 2, 'L');
$pdf->Output(strtolower($show_client['first_name']) . '_billing_label.pdf', 'D');

?> 