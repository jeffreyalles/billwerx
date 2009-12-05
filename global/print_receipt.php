<?php

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Define invoice_id
$payment_id = $_GET['payment_id'];

# Generate invoice:
include_once("generate_receipt.php");

# Send generated invoice to browser:
$pdf->Output('receipt.pdf', 'I')

?>