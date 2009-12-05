<?php

# Define page access level:
session_start();
$page_access = 3;

# include_once session (security check):
include_once("session_check.php");

# include_once session check and database connection:
include_once("../inc/dbconfig.php");

// Create SQL file header:
$sqlbackup .= "# MySQL backup of " . $dname . ":\n";
$sqlbackup .= "# Generated on " . date('Y-m-d') . " at " . date("H:i:s") . ".\n\n";

// Get the tables to backup:
$tables = mysql_list_tables($dname);
for($i = 0; $i < mysql_num_rows($tables); $i++) {

$table = mysql_tablename ($tables, $i);
$sqlbackup .= "# Data for $table: \n";

$tabledata = mysql_query("SELECT * FROM $table");
$num_fields = mysql_num_fields($tabledata);
$numrow = mysql_num_rows($tabledata);
while( $row = mysql_fetch_array($tabledata, MYSQL_NUM)) {

// Add commands so backup can be used by SQL:
$sqlbackup .= "INSERT INTO ".$table." VALUES(";
for($j = 0; $j < $num_fields; $j++) {
$row[$j] = addslashes($row[$j]);
$row[$j] = str_replace("\n","\\n",$row[$j]);
$row[$j] = str_replace("\r","",$row[$j]);
if (isset($row[$j]))
$sqlbackup .= "\"$row[$j]\"";
else
$sqlbackup .= "\"\"";
if ($j<($num_fields-1))
$sqlbackup .= ", "; }
$sqlbackup .= ");\n"; }
if ($i + 1 != mysql_num_rows($tables))
$sqlbackup .= "\n"; }

// Find out how long the file is for the browser:
$size = strlen($sqlbackup); 

// Construct file name:
$name = "db_backup_" . $dname . date("_m_d_Y") . ".sql";

# Send to browser:
header("Cache-control: private");
header("Content-type: text/sql");
header("Content-length: $size");
header("Content-Disposition: attachment; filename = $name");
header("Pragma: public");
echo $sqlbackup;

?>