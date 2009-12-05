<?php

# Define page access level:
session_start();

// Start recording data:
ob_start();

// Get the template:
include_once("../templates/invoice.php");

// Define invoice data:
$invoice_data = ob_get_contents();

// Stop getting data: 
ob_end_clean();

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetAuthor($show_company['company_name']);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

# Display the company logo:
$logo = 'http://billing.icubedev.com/global/company_logo.php';
$pdf->Image ($logo, 11, 11, 70, 0, 'jpeg');
$pdf->WriteHTML("$invoice_data");

?>