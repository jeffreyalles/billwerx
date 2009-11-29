<?php

# Define page access level:
if(!isset($_SESSION)) { session_start(); };

?>
<a href="profile.php">PROFILE</a> | <a href="index.php">SUMMARY</a> | <a href="csv_export.php">EXPORT</a> | <a href="files.php">FILES</a> | <a href="javascript:print();">PRINT</a> | <a href="../logout.php">LOGOUT</a>