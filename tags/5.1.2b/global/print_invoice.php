<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Define invoice_id
$invoice_id = $_GET['invoice_id'];

# Get invoice data:
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Include security check (URL manipulation):
if((!isset($_SESSION['employee_id'])) AND ($show_invoice['client_id'] != $_SESSION['client_id'])) {
header("Location: ../restricted.php");
exit;
};

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

// Start recording data:
ob_start();

// Get the template:
include("../templates/invoice.php");

// Define invoice data:
$html = ob_get_contents();

// Stop getting data: 
ob_end_clean();

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetAuthor($show_company['company_name']);
$pdf->SetMargins(2, 2, 2);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("$html");
$pdf->Output();
?> 