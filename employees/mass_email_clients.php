<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");
include_once("../inc/dbconfig.php");
include_once("../global/make_safe.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_campaigns = mysql_query("SELECT * FROM campaigns");

# If the size of the file is greater than zero (0) process:
if((isset($_POST['send'])) AND (($_FILES['html_body']['size'] > 0))) {

# Define POST variables:
$campaign_id = $_POST['campaign_id'];
$subject = $_POST['subject'];

# Define POST file variables:
$html_body  = $_FILES['html_body']['tmp_name'];
$read_html_body = fopen($html_body, 'r');
$html_body_content = fread($read_html_body, filesize($html_body));
fclose($read_html_body);

# Obtain clients to e-mail:
$get_clients = mysql_query("SELECT * FROM clients WHERE active = 1 AND email_address REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND campaign_id = '$campaign_id'");
while($show_client = mysql_fetch_array($get_clients)) {

# Setup PHPMailer values:
require("../inc/phpmailer/class.phpmailer.php");
$mail = new PHPMailer();

$mail->IsSMTP();
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($show_client['email_address']);
$mail->WordWrap = 50;
$mail->IsHTML(true);

$mail->Subject = $subject;
$mail->Body = $html_body_content;

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo;
};

};

# Return to screen:
header("Location: email_sent.php");
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?>- Mass E-mail Clients</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h2>Mass E-mail Clients:</h2>
    <h3>Select the campaign, subject, body, and attachment (if any).</h3>
  </div>
  <div id="content">
    <form action="" method="post" enctype="multipart/form-data" name="email" id="email">
      <table class="fulltable">
        <tr>
          <td class="firstcell">campaign:</td>
          <td><select name="campaign_id" class="entrytext" id="campaign_id">
              <?php while($show_campaign = mysql_fetch_array($get_campaigns)) { ?>
              <option value="<?php echo $show_campaign['campaign_id'] ?>"><?php echo $show_campaign['name'] ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">subject:</td>
          <td><input name="subject" type="text" class="entrytext" id="subject" /></td>
        </tr>
        <tr>
          <td class="firstcell">html body:</td>
          <td><input name="html_body" type="file" class="entrytext" id="html_body" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
