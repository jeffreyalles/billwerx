<?php

# Create array to temporarily grab variables:
$user_input_data = array();

# Ensure values stored in database are not harmful:
foreach($_POST as $input => $user_input_data) { $_POST[$input] = mysql_real_escape_string(trim($user_input_data)); }

?>