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

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$client_id = $_GET['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

# Setup query to obtain encrypted credit cards:
$get_credit_cards = mysql_query("SELECT credit_card_id, AES_DECRYPT(type, '$encryption_key') AS type, AES_DECRYPT(number, '$encryption_key') AS number, AES_DECRYPT(expiration, '$encryption_key') AS expiration, employee_id, created FROM credit_cards WHERE client_id = '$client_id'");

$total_records = mysql_num_rows($get_credit_cards);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['create'])) {

$type = $_POST['type'];
$number = $_POST['number'];
$expiration = $_POST['expiration'];

$client_id = $_POST['client_id'];

$employee_id = $_SESSION['employee_id'];

# Make MySQL statement:
$doSQL = "INSERT INTO credit_cards (client_id, type, number, expiration, employee_id) VALUES ($client_id, AES_ENCRYPT('$type', '$encryption_key'), AES_ENCRYPT('$number', '$encryption_key'), AES_ENCRYPT('$expiration', '$encryption_key'), '$employee_id')";

# Perform SQL command, show error (if any):
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: credit_cards.php?client_id=$client_id");

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Credit Cards</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
<script type="text/javascript" src="../scripts/tooltip.js"></script>
<script type="text/javascript" src="../scripts/modulus10.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/credit_cards.png" alt="Credit Cards" width="16" height="16" /> Credit Cards:</h1>
    <p>Found <?php echo $total_records ?> record(s).</p>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">type:</td>
          <td><select name="type" class="entrytext" id="type">
              <option value="VISA">VISA</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="firstcell">number:</td>
          <td><input name="number" type="text" class="entrytext" id="number" onblur="cleanNumber(this);modulus10Check()" /></td>
        </tr>
        <tr>
          <td class="firstcell">modulus 10:</td>
          <td><input name="modulus10_result" type="text" class="entrytext" id="modulus10_result" readonly="readonly" /></td>
        </tr>
        <tr>
          <td class="firstcell">expiration:</td>
          <td><input name="expiration" type="text" class="entrytext" id="expiration" onblur="cleanNumber(this)" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="create" type="submit" class="button" id="create" value="CREATE" />
            <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" />
          <input name="client_id" type="hidden" id="client_id" value="<?php echo $show_client['client_id'] ?>" /></td>
        </tr>
      </table>
      <table class="fulltable">
        <tr>
          <td width="10%" class="tabletop">&nbsp;</td>
          <td width="34%" class="tabletop">type:</td>
          <td class="tabletop">number:</td>
          <td width="20%" class="tabletop">expiration:</td>
        </tr>
        <?php while($show_credit_card = mysql_fetch_array($get_credit_cards)) { ?>
        <?php $get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_credit_card['employee_id'] . ""); ?>
        <?php $show_employee = mysql_fetch_array($get_employees) ?>
        <tr class="tablelist">
          <td class="tablerowborder"><a href="delete_credit_card.php?credit_card_id=<?php echo $show_credit_card['credit_card_id'] ?>"><img src="../images/icons/delete.png" alt="Delete Credit Card" width="16" height="16" class="iconspacer" /></a></td>
          <td class="tablerowborder"><?php echo $show_credit_card['type'] ?></td>
          <td class="tablerowborder"><a href="javascript:copyText('<?php echo $show_credit_card['number'] ?>')" onmouseover="tooltip(event, '<?php echo $show_credit_card['credit_card_id'] ?>')" onmouseout="tooltip(event, '<?php echo $show_credit_card['credit_card_id'] ?>')"><?php echo $show_credit_card['number'] ?></a>
            <div class="tooltip" id="<?php echo $show_credit_card['credit_card_id'] ?>">
              <table>
                <tr>
                  <td><span class="justred"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></span><br>
                    <span class="smalltext"><?php echo $show_credit_card['created'] ?></span></td>
                </tr>
              </table>
            </div></td>
          <td class="tablerowborder"><a href="javascript:copyText('<?php echo $show_credit_card['expiration'] ?>')"><?php echo $show_credit_card['expiration'] ?></a></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
</body>
</html>
