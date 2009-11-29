<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include security POST loop:
include("../global/make_safe.php");

# Include session check and database connection:
include("../inc/dbconfig.php");
include("../inc/phpmailer/class.phpmailer.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['random'])) {

# Defind POST data:
$starting_date = $_POST['starting_date'];
$ending_date = $_POST['ending_date'];

# Get invoice data:
$get_surveys = mysql_query("SELECT * FROM surveys WHERE created BETWEEN '$starting_date' AND '$ending_date' ORDER BY RAND() LIMIT 1");
$show_survey = mysql_fetch_array($get_surveys);
$get_invoices = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_survey['invoice_id'] . "");
$show_invoice = mysql_fetch_array($get_invoices);
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Survey Draw</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('starting_date').focus()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/survey.png" alt="Survey Draw" width="16" height="16" /> Survey Draw:</h1>
    <p>Enter the message you wish to send to your clients.</p>
  </div>
  <div id="content">
    <form id="E-mail" name="E-mail" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">starting date:</td>
          <td><input name="starting_date" type="text" class="entrytext" id="starting_date" value="" /></td>
        </tr>
        <tr>
          <td class="firstcell">ending date:</td>
          <td><input name="ending_date" type="text" class="entrytext" id="ending_date" value="" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="random" type="submit" class="button" id="random" value="RANDOM" />
          <input name="print" type="button" class="button" id="print" onclick="javascript:window.print()" value="PRINT" /></td>
        </tr>
      </table>
<?php if(isset($_POST['random'])) { ?>
      <h1>Client:</h1>
      <table class="fulltable">
        <tr>
          <td class="tabletop">client:</td>
          <td width="60%" class="tabletop">billing  address:</td>
        </tr>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="print_client_billing.php?client_id=<?php echo $show_client['client_id'] ?>"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a><br />
              <?php echo $show_client['company_name'] ?><br />
              <span class="smalltext"><a href="mailto:<?php echo $show_client['email_address'] ?>"><?php echo $show_client['email_address'] ?></a></span></td>
          <td class="tablerowborder"><?php echo $show_client['billing_address'] ?><br />
            <span class="smalltext"><?php echo $show_client['billing_city'] ?> <?php echo $show_client['billing_province'] ?> <?php echo $show_client['billing_postal'] ?><br />
            <?php echo $show_client['billing_country'] ?></span></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr class="tabletop">
          <td width="25%">home number:</td>
          <td width="25%">work number:</td>
          <td width="25%">mobile number:</td>
          <td width="25%">fax number:</td>
        </tr>
        <tr class="tablelist">
          <td class="tablerowborder"><?php echo $show_client['home_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_client['work_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_client['mobile_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_client['fax_number'] ?></td>
        </tr>
      </table>
      <h1>Invoice:</h1>
      <table class="fulltable">
        <tr>
          <td class="tabletop">invoice:</td>
        </tr>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>"><?php echo $show_invoice['invoice_id'] ?> - <?php echo $show_invoice['purpose'] ?></a></td>
        </tr>
      </table>
      <?php } ?>
    </form>
  </div>
</div>
</body>
</html>
