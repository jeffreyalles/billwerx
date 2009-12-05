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
$get_items = mysql_query("SELECT * FROM items WHERE active = 1 AND (description LIKE '%$query%') OR (name LIKE '%$query%') LIMIT 4"); ?>

<ul>
  <?php while($show_item = mysql_fetch_array($get_items)) { ?>
  <li><a href="javascript:hideDiv('results')" onClick="document.getElementById('suggest').value = '<?php echo $show_item['name'] ?>';document.getElementById('item_id').value = '<?php echo $show_item['item_id'] ?>';document.getElementById('quantity').focus()"><?php echo $show_item['description'] ?></a></li>
  <?php } ?>
  <li><a href="javascript:openWindow('create_item.php')">[Create Item] - No matches found.</a></li>
</ul>
