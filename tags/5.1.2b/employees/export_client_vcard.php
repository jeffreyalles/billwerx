<?php

# Define page access level:
session_start();
$page_access = 1;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include vcard class:
require_once('../inc/vcard/class_vcard.php');

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get invoice data:
$client_id = $_GET['client_id'];
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = '$client_id'");
$show_client = mysql_fetch_array($get_client);

$vc = new vcard();

/*
filename is the name of the .vcf file that will be sent to the user if you
call the download() method. If you leave this blank, the class will 
automatically build a filename using the contact's data.
*/
#$vc->filename = "";

/*
If you leave this blank, the current timestamp will be used.
*/
#$vc->revision_date = "";

/*
Possible values are PUBLIC, PRIVATE, and CONFIDENTIAL. If you leave class
blank, it will default to PUBLIC.
*/
#$vc->class = "PUBLIC";

/*
Contact's name data.
If you leave display_name blank, it will be built using the first and last name.
*/
#$vc->data['display_name'] = "";
$vc->data['first_name'] = $show_client['first_name'];
$vc->data['last_name'] = $show_client['last_name'];
#$vc->data['additional_name'] = ""; //Middle name
#$vc->data['name_prefix'] = "";  //Mr. Mrs. Dr.
#$vc->data['name_suffix'] = ""; //DDS, MD, III, other designations.
#$vc->data['nickname'] = "TJ";

/*
Contact's company, department, title, profession
*/
$vc->data['company'] = $show_client['company_name'];
#$vc->data['department'] = "";
#$vc->data['title'] = "Web Developer";
#$vc->data['role'] = "";

/*
Contact's work address
*/
#$vc->data['work_po_box'] = "";
#$vc->data['work_extended_address'] = "";
$vc->data['work_address'] = $show_client['billing_address'];
$vc->data['work_city'] = $show_client['billing_city'];
$vc->data['work_state'] = $show_client['billing_province'];
$vc->data['work_postal_code'] = $show_client['billing_postal'];
$vc->data['work_country'] = $show_client['billing_country'];

/*
Contact's home address
*/
#$vc->data['home_po_box'] = "";
#$vc->data['home_extended_address'] = "";
#$vc->data['home_address'] = "7027 N. Hickory";
#$vc->data['home_city'] = "Kansas City";
#$vc->data['home_state'] = "MO";
#$vc->data['home_postal_code'] = "64118";
#$vc->data['home_country'] = "United States of America";

/*
Contact's telephone numbers.
*/
$vc->data['office_tel'] = $show_client['work_number'];
$vc->data['home_tel'] = $show_client['home_number'];
$vc->data['cell_tel'] = $show_client['mobile_number'];
$vc->data['fax_tel'] = $show_client['fax_number'];
#$vc->data['pager_tel'] = "";

/*
Contact's email addresses
*/
$vc->data['email1'] = $show_client['email_address'];
#$vc->data['email2'] = "";

/*
Contact's website
*/
#$vc->data['url'] = "http://www.troywolf.com";

/*
Some other contact data.
*/
#$vc->data['photo'] = "";  //Enter a URL.
#$vc->data['birthday'] = "1971-08-13";
#$vc->data['timezone'] = "-06:00";

/*
If you leave this blank, the class will default to using last_name or company.
*/
#$vc->data['sort_string'] = "";

/*
Notes about this contact.
*/
#$vc->data['note'] = "Troy is an amazing guy!";

/*
Generate card and send as a .vcf file to user's browser for download.
*/
$vc->download();


?>

