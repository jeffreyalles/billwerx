<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/code39.php');

# Start PDF generation:
$pdf = new PDF_Code39('L', 'mm', array(36, 89));
$pdf->SetAuthor($show_company['company_name']);
$pdf->SetMargins(2, 2, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(85, 6, strtoupper($show_client['last_name']) . ', ' . $show_client['first_name'], 1, 2, 'L');
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(28, 12, $show_invoice['client_id'], 1, 0, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(57, 6, $show_invoice['purpose'], 1, 2, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(57, 6, '(#' . $show_invoice['invoice_id'] . ') ' . $show_invoice['date_created'] . ' - ' . $show_invoice['date_due'], 1, 2, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(57, 6, $show_company['company_name'] . ' - ' . $show_company['work_number'], 1, 0, 'L');
$pdf->Code39(2, 22, $show_invoice['invoice_id'], 0.7, 12);
$pdf->Code39(30, 28, $show_client['last_name'], 0.8, 6);
$pdf->Output();

?> 