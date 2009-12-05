<?php

// Connect to file that makes MySQL work:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");
include("../inc/phpmailer/class.phpmailer.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get company messages:
$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

$invoice_id = $_GET['invoice_id'];
$easypay_id = $_GET['easypay_id'];

# Ensure this user has permission to leave feedback for this invoice:
$get_invoices = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoices);

# Get employee from invoice:
$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

# Get employee from invoice:
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

# Ensure this user has not left feedback for this invoice before:
$get_surveys = mysql_query("SELECT * FROM surveys WHERE invoice_id = '$invoice_id'");
$show_survey = mysql_fetch_array($get_surveys);

if(($show_invoice['easypay_id'] != $easypay_id) or ($show_survey['invoice_id'] == $invoice_id)) {

# Return bad username or e-mail address:
header("Location: survey_error.php");
exit;
}

# Process form when $_POST data is found for the specified form:
if(isset($_POST['send'])) {

# Get survey POST values:
$rating = $_POST['rating'];
$comments = strtolower($_POST['comments']);

# Assign values to a database table:
$doSQL = "INSERT INTO surveys (invoice_id, rating, comments) VALUES ('$invoice_id', '$rating', '$comments')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

$search_values = array(
"[employee_first_name]",
"[employee_last_name]",
"[client_first_name]",
"[client_last_name]",
"[invoice_id]",
"[invoice_purpose]",
"[invoice_notes]",
"[survey_rating]",
"[survey_comments]",
);

$replacement_values = array(
$show_employee['first_name'],
$show_employee['last_name'],
$show_client['first_name'],
$show_client['last_name'],
$show_invoice['invoice_id'],
$show_invoice['purpose'],
$show_invoice['notes'],
$rating,
$comments,
);

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($show_employee['email_address']);
$mail->addBCC($show_company['email_address'], $show_company['company_name']); 
$mail->Subject = "Survey Result: Invoice #: $invoice_id - " . $show_invoice['purpose'];
$mail->Body = str_replace($search_values, $replacement_values, $show_company_message['survey_result']);

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
}

# Return bad username or e-mail address:
header("Location: survey_complete.php");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Survey</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/survey.png" alt="Survey" width="16" height="16" /> Survey:</h1>
    <p>Your feedback is valued and is used to improve our business.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td class="firstcell">rating:</td>
          <td><select name="rating" class="entrytext" id="rating">
            <option value="5">Excellent</option>
            <option value="4">Good</option>
            <option value="3" selected="selected">Average</option>
            <option value="2">Poor</option>
            <option value="1">Horrible</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="firstcell">comments:</td>
          <td><input name="comments" type="text" class="entrytext" id="comments" value="" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
