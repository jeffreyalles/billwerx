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
<title><?php echo $show_company['company_name'] ?> - Survey Complete</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/information.png" alt="Survey Complete" width="16" height="16" /> Survey Complete:</h1>
    <p>The information you provided is used to ensure we maintain a high quality of service and have met your expectations.</p>
    <p>We thank you for taking the time to share your thoughts - if required a member of management may contact you.</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td><input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
