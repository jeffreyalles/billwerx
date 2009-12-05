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
<title><?php echo $show_company['company_name'] ?> - Unauthorized</title>
<link href="billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="images/icons/unauthorized.png" alt="Unauthorized" width="16" height="16" /> Unauthorized:</h1>
    <p>The e-mail address and account password you provided cannot be authenticated. Please check your account details and try again.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><input name="login" type="button" class="button" id="login" onclick="window.location='index.php'" value="LOGIN" />
          <input name="forgot_password" type="button" class="button" id="forgot_password" onclick="window.location='forgot_password.php'" value="FORGOT PASSWORD" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
