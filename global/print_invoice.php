<?php

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Define invoice_id
$invoice_id = $_GET['invoice_id'];

# Generate invoice:
include_once("generate_invoice.php");

# Send generated invoice to browser:
$pdf->Output('invoice.pdf', 'I')

?>