<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Setup pagination:
# 2009/08/10 RC 5 Corrected undefined variable:
if(isset($_GET['start'])) { $start = $_GET['start']; } else { $start = 0; };
$previous_page = ($start - $show_company['records_per_page']);
$next_page = ($start + $show_company['records_per_page']);

# Get invoice data:
$get_total_suppliers = mysql_query("SELECT * FROM suppliers");
$total_records = mysql_num_rows($get_total_suppliers);
$get_suppliers = mysql_query("SELECT * FROM suppliers ORDER BY supplier_id DESC LIMIT $start, " . $show_company['records_per_page'] . "");

# Start search:
if(isset($_GET['query'])) {
$query = $_GET['query'];
$get_suppliers = mysql_query("SELECT * FROM suppliers WHERE (last_name LIKE '%$query%') OR (first_name LIKE '%$query%') OR (company_name LIKE '%$query%')");
$total_records = mysql_num_rows($get_suppliers);
$next_page = $total_records;
};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Suppliers</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><img src="../images/icons/suppliers.png" alt="Suppliers" width="16" height="16" /> Suppliers:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
    <div id="navbar">
      <?php include("navbar.php") ?>
    </div>
  </div>
  <div id="content">
    <form id="suppliers" name="suppliers" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="halftopcell"><h2>Search:</h2>
            <table class="fulltable">
              <tr>
                <td class="firstcell">for query:</td>
                <td><input name="query" type="text" class="entrytext" id="query" /></td>
              </tr>
              <tr>
                <td class="firstcell">&nbsp;</td>
                <td><input name="create" type="button" class="button" id="create" onclick="window.location='create_supplier.php'" value="CREATE" />
                  <input name="email" type="button" class="button" id="email" onclick="openWindow('mass_email_suppliers.php')" value="E-MAIL" /></td>
              </tr>
          </table></td>
          <td class="halftopcell"><img src="suppliers_pgraph.php" alt="Suppliers" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="8%" class="tabletop">&nbsp;</td>
          <td width="8%" class="tabletop">supplier #:</td>
          <td class="tabletop">full name:</td>
          <td width="22%" class="tabletop">billing address:</td>
          <td width="12%" class="tabletop">work number:</td>
          <td width="12%" class="tabletop">mobile number:</td>
          <td width="12%" class="tabletop">fax number:</td>
        </tr>
        <?php while($show_supplier = mysql_fetch_array($get_suppliers)) { ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="export_supplier_vcard.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>"><img src="../images/icons/vcard.png" alt="Export VCard" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><a href="expenses.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>"><?php echo $show_supplier['supplier_id'] ?></a></td>
          <td class="tablerowborder"><a href="update_supplier.php?supplier_id=<?php echo $show_supplier['supplier_id'] ?>"><?php echo strtoupper($show_supplier['last_name']) ?>, <?php echo $show_supplier['first_name'] ?></a><br />
            <span class="smalltext"><?php echo $show_supplier['company_name'] ?><br />
            <a href="mailto:<?php echo $show_supplier['email_address'] ?>"><?php echo $show_supplier['email_address'] ?></a></span></td>
          <td class="tablerowborder"><?php echo $show_supplier['billing_address'] ?><br />
            <span class="smalltext"><?php echo $show_supplier['billing_city'] ?> <?php echo $show_supplier['billing_province'] ?> <?php echo $show_supplier['billing_postal'] ?><br />
            <?php echo $show_supplier['billing_country'] ?></span></td>
          <td class="tablerowborder"><?php echo $show_supplier['work_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_supplier['mobile_number'] ?></td>
          <td class="tablerowborder"><?php echo $show_supplier['fax_number'] ?></td>
        </tr>
        <?php } ?>
      </table>
      <table class="fulltable">
        <tr>
          <td class="pagination"><?php if ($start > 0) { ?>
              <a href="?start=<?php echo $previous_page ?>"><img src="../images/icons/previous.png" alt="Prevous Page" width="16" height="16" class="iconspacer" /></a>
              <?php } ?>
              <?php if ($next_page < $total_records) { ?>
              <a href="?start=<?php echo $next_page ?>"><img src="../images/icons/next.png" alt="Next Page" width="16" height="16" class="iconspacer" /></a>
              <?php } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
