<?php

// Connect to file that makes MySQL work:
include("inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Timeout</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/timeout.png" alt="Timeout" width="16" height="16" /> Easypay:</h1>
    <p>Thank you for taking the time to let us know how we did.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td class="firstcell">invoice #:</td>
          <td><input name="textfield4" type="text" class="entrytext" id="textfield4" /></td>
        </tr>
        <tr>
          <td class="firstcell">easypay number:</td>
          <td><input name="textfield" type="text" class="entrytext" id="textfield" /></td>
        </tr>
        <tr>
          <td class="firstcell">credit card number:</td>
          <td><input name="textfield2" type="text" class="entrytext" id="textfield2" /></td>
        </tr>
        <tr>
          <td class="firstcell">expiration:</td>
          <td><input name="textfield3" type="text" class="entrytext" id="textfield3" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="textfield5" type="text" class="entrytext" id="textfield5" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="send" type="submit" class="button" id="send" value="PAY" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
