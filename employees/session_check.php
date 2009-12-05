<?php

session_start();

# Security check (patches security flaw with RC 5 2009/10/11):
# Check page security access level and ensures a employee_id session variable has been set:
if(($page_access > $_SESSION['access_level']) OR (!isset($_SESSION['employee_id']))) {
header("Location: ../restricted.php");
exit;
};

?>