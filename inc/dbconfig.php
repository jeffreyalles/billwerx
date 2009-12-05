<?php

# MySQL database configuration:
$server = "localhost";
$user = "username";
$password = "password";
$dname = "billwerx";

# Encryption key (used for credit card storage):
# Changing this value after you use the product will render all previously stored credit cards useless!
$encryption_key = "myencryptionkey";

# Start MySQL connection:
$sqlconnection = mysql_connect($server, $user, $password);
mysql_select_db($dname, $sqlconnection) or die ("Unable to connect to the database: Contact Billwerx technical support at support@billwerx.com.");

?>