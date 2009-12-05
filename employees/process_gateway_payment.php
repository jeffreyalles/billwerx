<?php

# Define page access level:
session_start();
$page_access = 2;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# include_once security POST loop:
include_once("../global/make_safe.php");

# include_once Admeris Core API:
include_once "../inc/api/admeris/HttpsCreditCardService.php";

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Get client data:
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

# Get clients credit card data:
$get_credit_cards = mysql_query("SELECT credit_card_id, AES_DECRYPT(type, '$encryption_key') AS type, AES_DECRYPT(number, '$encryption_key') AS number, AES_DECRYPT(expiration, '$encryption_key') AS expiration, employee_id, created FROM credit_cards WHERE client_id = " . $show_invoice['client_id'] . "");

# Process form when $_POST data is found for the specified form:
if(isset($_POST['credit_card_id'])) {

$credit_card_id = $_POST['credit_card_id'];

# Get selected credit card number data:
$get_selected_card = mysql_query("SELECT AES_DECRYPT(number, '$encryption_key') AS number, AES_DECRYPT(expiration, '$encryption_key') AS expiration, AES_DECRYPT(cvv2, '$encryption_key') AS cvv2 FROM credit_cards WHERE credit_card_id = '$credit_card_id'");
$show_selected_card = mysql_fetch_array($get_selected_card);

# Define form values (temp for now):
$amount = $_POST['amount'];
$billing_address = $_POST['billing_address'];
$billing_postal = $_POST['billing_postal'];
$invoice_id = $_POST['invoice_id'];

# Define connection parameters to the Admeris CC gateway:
$url = "https://test.admeris.com/ccgateway/cc/processor.do";
$merchantId = "10174";
$apiToken = "ixj3pqipCuHxu90m";
$apiGateway = new HttpsCreditCardService($merchantId, $apiToken, $url);

# Format gateway request:
$creditCard = new CreditCard($show_selected_card['number'], $show_selected_card['expiration'], $show_selected_card['cvv2'], $billing_address, $billing_postal);

# Define credit card validation (security) checks:
$vr = new VerificationRequest(AVS_VERIFY_STREET_AND_ZIP, CVV2_PRESENT);

# Send gateway request:
#$resp = $apiGateway->singlePurchase($invoice_id, $creditCard, ($amount * 100), $vr);
$resp = $apiGateway->singlePurchase($invoice_id, $creditCard, ($amount * 100), $vr);

if($resp->params[APPROVED] == "true") {

#if($resp->isApproved()) {
print_r($resp->params);
} else {
// display error for debugging: it is recommended not to display full error
// message to external user
#echo "Error Code: " . $resp->getErrorCode() . "Message: " .
#$resp->getErrorMessage();
echo "D<script language='Javascript'> alert ('DECLINED:" . $resp->getErrorMessage() . "')</script>";
};
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Create Gateway Payment</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('amount').focus()" onunload="window.opener.location.reload()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/payments.png" alt="Create Payment" width="16" height="16" /> Process Gateway Payment:</h1>
    <p>Select a credit card to charge for invoice #: <a href="../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>"><?php echo $show_invoice['invoice_id'] ?></a>.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="create_payment" id="create_payment">
      <table class="fulltable">
        <tr>
          <td class="firstcell">amount:
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" />
            <input name="billing_address" type="hidden" id="billing_address" value="<?php echo $show_client['billing_address'] ?>" />
            <input name="billing_postal" type="hidden" id="billing_postal" value="<?php echo $show_client['billing_postal'] ?>" /></td>
          <td><input name="amount" type="text" class="entrytext" id="amount" value="<?php echo $show_invoice['due'] ?>" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="20%" class="tabletop">select card:</td>
          <td class="tabletop">card details:</td>
          <td width="40%" class="tabletop">created:</td>
        </tr>
        <?php while($show_credit_card = mysql_fetch_array($get_credit_cards)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_credit_card['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><input type="radio" name="credit_card_id" id="radio" onClick="this.form.submit();this.disabled=true" value="<?php echo $show_credit_card['credit_card_id'] ?>" /></td>
          <td class="tablerowborder"><?php echo $show_credit_card['type'] ?> (<?php echo substr($show_credit_card['number'], -4) ?>)<br />
            <span class="smalltext"><?php echo $show_credit_card['expiration'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_credit_card['created'] ?><br />
            <span class="smalltext"><a href="mailto:<?php echo $show_employee['email_address'] ?>"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></a></span></td>
        </tr>
        <?php } ?>
      </table>
      </form>
  </div>
</div>
</body>
</html>
