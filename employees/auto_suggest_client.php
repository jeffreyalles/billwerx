<?php

# Define page access level:
session_start();
$page_access = 1;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

# Get query:
$query = $_GET['query'];
$get_clients = mysql_query("SELECT * FROM clients WHERE active = 1 AND (last_name LIKE '%$query%') OR (first_name LIKE '%$query%') OR (company_name LIKE '%$query%') LIMIT 4"); ?>

<ul>
  <?php while($show_client = mysql_fetch_array($get_clients)) { ?>
  <li><a href="javascript:hideDiv('results')" onClick="document.getElementById('suggest').value = '<?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?>';document.getElementById('client_id').value = '<?php echo $show_client['client_id'] ?>';document.getElementById('purpose').focus()"><?php echo strtoupper($show_client['last_name']) ?>, <?php echo $show_client['first_name'] ?></a></li>
  <?php } ?>
  <li><a href="create_client.php">[Create Client] - No matches found.</a></li>
</ul>
