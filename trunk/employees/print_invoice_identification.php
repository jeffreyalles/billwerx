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
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

# include_once FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/code39.php');

# Start PDF generation:
$pdf = new PDF_Code39('L', 'mm', array(36, 94));
$pdf->SetAuthor($show_company['company_name']);
$pdf->SetMargins(2, 2, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 6, strtoupper($show_client['last_name']) . ', ' . $show_client['first_name'], 1, 2, 'L');
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(33, 12, $show_invoice['employee_id'] . '-' . $show_invoice['invoice_id'], 1, 0, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(57, 6, $show_invoice['purpose'], 1, 2, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(57, 6, $show_invoice['date_created'] . ' - ' . $show_invoice['date_due'], 1, 2, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(57, 6, $show_company['company_name'] . ' - ' . $show_company['work_number'], 1, 0, 'L');
$pdf->Code39(2, 22, $show_invoice['invoice_id'], 0.85, 12);
$pdf->Code39(35, 28, $show_client['client_id'], 1, 6);
$pdf->Output(strtolower($show_invoice['invoice_id']) . '_identification_label.pdf', 'D');

?> 