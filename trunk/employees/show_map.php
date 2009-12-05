<?php

if($_SERVER['SERVER_PORT'] != '80') {
header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); exit();
}

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$client_id = $_GET['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?>- Show Map</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="../scripts/google_map.js"></script>
</head>
<body onLoad="initialize();codeAddress()">
<div id="smallwrap">
  <div id="header">
    <h2>Show Map:</h2>
    <h3>Viewing map for the client: <a href="mailto:<?php echo $show_client['email_address'] ?>"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a>.</h3>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="client_files" id="client_files">
      <table class="fulltable">
        <tr>
          <td><select name="address" class="entrytext" id="address" onchange="codeAddress()">
              <option value="<?php echo $show_client['billing_address'] ?> <?php echo $show_client['billing_city'] ?> <?php echo $show_client['billing_province'] ?> <?php echo $show_client['billing_country'] ?>">Billing Address (Default)</option>
              <option value="<?php echo $show_client['shipping_address'] ?> <?php echo $show_client['shipping_city'] ?> <?php echo $show_client['shipping_province'] ?> <?php echo $show_client['shipping_country'] ?>">Shipping Address</option>
            </select></td>
        </tr>
      </table>
    </form>
    <div id="map_canvas"></div>
  </div>
</div>
</body>
</html>
