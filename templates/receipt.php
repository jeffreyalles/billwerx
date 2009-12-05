<?php

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_payments = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payments);

$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_payment['invoice_id'] . "");
$show_invoice = mysql_fetch_array($get_invoice);

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_payment['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_payment['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

$get_payment_items = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");

# Include security check (URL manipulation):
if((!isset($_SESSION['employee_id'])) AND ($show_payment['client_id'] != $_SESSION['client_id'])) {
header("Location: ../restricted.php");
exit;
};

?>
<table>
<tr>
<td width="400">&nbsp;</td>
<td width="350" align="RIGHT"><strong><?php echo $show_company['company_name'] ?></strong></td>
</tr>
<tr>
<td width="400">&nbsp;</td>
<td width="350" align="RIGHT"><?php echo $show_company['tag_line'] ?></td>
</tr>
<tr>
<td width="400">&nbsp;</td>
<td width="350" align="RIGHT"><?php echo $show_company['billing_address'] ?></td>
</tr>
<tr>
<td width="400">&nbsp;</td>
<td width="350" align="RIGHT"><?php echo $show_company['billing_city'] ?> <?php echo $show_company['billing_province'] ?> <?php echo $show_company['billing_postal'] ?></td>
</tr>
<tr>
<td width="400">&nbsp;</td>
<td width="350" align="RIGHT"><?php echo $show_company['billing_country'] ?></td>
</tr>
</table>
<table>
<tr>
<td width="750"><font face="Arial" size="18"><strong><?php echo $show_invoice['purpose'] ?></strong></font></td>
</tr>
<tr>
<td width="750"><font face="Arial" size="10">RECEIPT #: <?php echo $show_payment['invoice_id'] ?></font></td>
</tr>
</table>
<br>
<table>
<tr>
<td width="400" bgcolor="#CCCCCC"><strong>bill to:</strong></td>
<td width="350" align="RIGHT" bgcolor="#CCCCCC"><strong>ship to:</strong></td>
</tr>
</table>
<table>
<tr>
<td width="400"><?php echo $show_client['first_name'] ?> <?php echo $show_client['last_name'] ?></td>
<td width="350" align="RIGHT"><?php echo $show_client['first_name'] ?> <?php echo $show_client['last_name'] ?></td>
</tr>
<tr>
<td width="400"><?php echo $show_client['company_name'] ?></td>
<td width="350" align="RIGHT"><?php echo $show_client['company_name'] ?></td>
</tr>
<tr>
<td width="400"><?php echo $show_invoice['billing_address'] ?></td>
<td width="350" align="RIGHT"><?php echo $show_invoice['shipping_address'] ?></td>
</tr>
<tr>
<td width="400"><?php echo $show_invoice['billing_city'] ?> <?php echo $show_invoice['billing_province'] ?>  <?php echo $show_invoice['billing_postal'] ?></td>
<td width="350" align="RIGHT"><?php echo $show_invoice['shipping_city'] ?> <?php echo $show_invoice['shipping_province'] ?> <?php echo $show_invoice['shipping_postal'] ?></td>
</tr>
<tr>
<td width="400"><?php echo $show_invoice['billing_country'] ?></td>
<td width="350" align="RIGHT"><?php echo $show_invoice['shipping_country'] ?></td>
</tr>
</table>
<br />
<table>
<tr>
<td width="110" bgcolor="#000000"><font color="#FFFFFF"><strong>payment #:</strong></font></td>
<td width="180" bgcolor="#000000"><font color="#FFFFFF"><strong>method / reference:</strong></font></td>
<td width="150" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>date received:</strong></font></td>
<td width="180" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>created / processed:</strong></font></td>
<td width="130" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>amount:</strong></font></td>
</tr>
<?php while($show_payment_items = mysql_fetch_array($get_payment_items)) { ?>
<?php $get_payment_method = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment_items['method_id'] . "") ?>
<?php $show_payment_method = mysql_fetch_array($get_payment_method) ?>
<?php $get_employee = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_payment_items['employee_id'] . "") ?>
<?php $show_employee = mysql_fetch_array($get_employee) ?>
<tr>
<td width="110"><font color="#000000" size="10" face="Arial"><?php echo $show_payment_items['payment_id'] ?></font></td>
<td width="180"><?php echo $show_payment_method['name'] ?> (<?php echo $show_payment_items['reference'] ?>)</td>
<td width="150" align="RIGHT"><?php echo $show_payment_items['date_received'] ?></td>
<td width="180" align="RIGHT" ><?php echo $show_payment_items['created'] ?></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_payment_items['amount'], 2) ?></td>
</tr>
<tr>
<td width="110">&nbsp;</td>
<td width="180"><font color="#000000" size="8" face="Arial"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></font></td>
<td width="150" align="RIGHT">&nbsp;</td>
<td width="180" align="RIGHT" >&nbsp;</td>
<td width="130" align="RIGHT">&nbsp;</td>
</tr>
<?php } ?>
</table>
<br />
<hr />
<br />
<table>
<tr>
<td width="490"><font face="Arial" size="10"><?php echo $show_company['tax1_name'] ?> #: <?php echo $show_company['business_number'] ?></font></td>
<td width="130" align="RIGHT"><strong>total:</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['total'], 2) ?></td>
</tr>
<tr>
<td width="490">&nbsp;</td>
<td width="130" align="RIGHT"><strong>received:</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['received'], 2) ?></td>
</tr>
<tr>
<td width="490">&nbsp;</td>
<td width="130" align="RIGHT"><strong>due:</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['due'], 2) ?></td>
</tr>
</table>
