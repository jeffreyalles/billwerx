<?php

# Define page access level:
session_start();

# Include session check and database connection:
include("../inc/dbconfig.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

$get_employees = mysql_query("SELECT * FROM employees WHERE employee_id = " . $show_invoice['employee_id'] . "");
$show_employee = mysql_fetch_array($get_employees);

$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

$get_invoice_items = mysql_query("SELECT * FROM invoice_items WHERE invoice_id = " . $show_invoice['invoice_id'] . "");

# Include security check (URL manipulation):
if((!isset($_SESSION['employee_id'])) AND ($show_invoice['client_id'] != $_SESSION['client_id'])) {
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
<td width="750"><font face="Arial" size="10">INVOICE #: <?php echo $show_invoice['invoice_id'] ?></font></td>
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
<td width="200" bgcolor="#CCCCCC"><strong>employee:</strong></td>
<td width="150" align="RIGHT" bgcolor="#CCCCCC"><strong>purchase order:</strong></td>
<td width="150" align="RIGHT" bgcolor="#CCCCCC"><strong>tracking #:</strong></td>
<td width="120" align="RIGHT" bgcolor="#CCCCCC"><strong>date created:</strong></td>
<td width="130" align="RIGHT" bgcolor="#CCCCCC"><strong>date due:</strong></td>
</tr>
<tr>
<td width="200"><?php echo strtoupper($show_employee['last_name']) ?>, <?php echo $show_employee['first_name'] ?></td>
<td width="150" align="RIGHT"><?php if(empty($show_invoice['purchase_order'])) { ?>&nbsp;<?php } ?><?php echo $show_invoice['purchase_order'] ?></td>
<td width="150" align="RIGHT"><?php if(empty($show_invoice['tracking_number'])) { ?>&nbsp;<?php } ?><?php echo $show_invoice['tracking_number'] ?></td>
<td width="120" align="RIGHT"><?php echo $show_invoice['date_created'] ?></td>
<td width="130" align="RIGHT"><?php echo $show_invoice['date_due'] ?></td>
</tr>
</table>
<br />
<table>
<tr>
<td width="320" bgcolor="#000000"><font color="#FFFFFF"><strong>description:</strong></font></td>
<td width="90" align="right" bgcolor="#000000"><font color="#FFFFFF"><strong>quantity:</strong></font></td>
<td width="120" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>price:</strong></font></td>
<td width="100" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>discount:</strong></font></td>
<td width="120" align="RIGHT" bgcolor="#000000"><font color="#FFFFFF"><strong>extended:</strong></font></td>
</tr>
<?php while($show_invoice_item = mysql_fetch_array($get_invoice_items)) { ?>
<tr>
<td width="320"><font color="#000000" size="9" face="Arial"><?php echo $show_invoice_item['name'] ?></font></td>
<td width="90" align="right"><font color="#000000"><?php echo $show_invoice_item['quantity'] ?></font></td>
<td width="120" align="RIGHT"><font color="#000000"><font face="Arial" size="10"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['price'], 2) ?></font></td>
<td width="100" align="RIGHT" ><font color="#000000">(<?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['discount_value'], 2) ?>)</font></td>
<td width="120" align="RIGHT"><font color="#000000"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice_item['extended'], 2) ?></font></td>
</tr>
<tr>
<td width="320"><font color="#000000" size="8" face="Arial"><?php echo $show_invoice_item['description'] ?></font></td>
<td width="90" align="right">&nbsp;</td>
<td width="120" align="RIGHT">&nbsp;</td>
<td width="100" align="RIGHT" >&nbsp;</td>
<td width="120" align="RIGHT">&nbsp;</td>
</tr>
<?php } ?>
</table>
<br />
<hr />
<br />
<table>
<tr>
<td width="490"><font face="Arial" size="10"><?php echo $show_company['tax1_name'] ?> #: <?php echo $show_company['business_number'] ?></font></td>
<td width="130" align="RIGHT"><strong>subtotal:</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['subtotal'], 2) ?></td>
</tr>
<tr>
<td width="490">&nbsp;</td>
<td width="130" align="RIGHT"><strong><?php echo $show_company['tax1_name'] ?> (<?php echo $show_invoice['tax1_percent'] ?>%):</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['tax1_total'], 2) ?></td>
</tr>
<tr>
<td width="490">&nbsp;</td>
<td width="130" align="RIGHT"><strong><?php echo $show_company['tax2_name'] ?> (<?php echo $show_invoice['tax2_percent'] ?>%):</strong></td>
<td width="130" align="RIGHT"><?php echo $show_company['currency_symbol'] ?><?php echo number_format($show_invoice['tax2_total'], 2) ?></td>
</tr>
<tr>
<td width="490">&nbsp;</td>
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
<br />
<hr />
<br />
<table>
<tr>
<td width="750" bgcolor="#CCCCCC"><strong>Notes:</strong></td>
</tr>
</table>
<?php echo nl2br($show_invoice['notes']) ?>