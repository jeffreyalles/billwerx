<?php

// Connect to file that makes MySQL work:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Payment Complete</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h2>Payment Complete:</h2>
    <h3>Thank you for making an on-line payment.</h3>
    <p>For your security, all payments are manually reviewed for quality assurance prior to processing. Accounts will reflect credit card payments within 24 hours.</p>
    </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><input name="return" type="button" class="button" id="return" onclick="window.location='index.php'" value="RETURN" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
