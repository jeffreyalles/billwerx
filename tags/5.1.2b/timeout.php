<?php

// Connect to file that makes MySQL work:
include("inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Destroy session data:
session_start();
session_destroy();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Timeout</title>
<link href="billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="images/icons/timeout.png" alt="Timeout" width="16" height="16" /> Timeout:</h1>
    <p>Your account has been inactive longer than permitted.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><input name="login" type="button" class="button" id="login" onclick="window.location='index.php'" value="LOGIN" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>