-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb5+lenny1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 12, 2009 at 12:47 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `billwerxrc512b`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `campaign_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY  (`campaign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `campaigns`
--


-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `company_name` varchar(255) NOT NULL default '',
  `home_number` varchar(255) NOT NULL default '',
  `work_number` varchar(255) NOT NULL default '',
  `mobile_number` varchar(255) NOT NULL default '',
  `fax_number` varchar(255) NOT NULL default '',
  `email_address` varchar(255) NOT NULL default '',
  `payment_terms` varchar(255) NOT NULL default '0',
  `discount` int(11) default '0',
  `billing_email_address` varchar(255) NOT NULL default '',
  `account_password` varchar(255) NOT NULL default '',
  `campaign_id` int(11) NOT NULL,
  `billing_address` varchar(255) NOT NULL default '',
  `billing_city` varchar(255) NOT NULL default '',
  `billing_province` varchar(255) NOT NULL default '',
  `billing_postal` varchar(255) NOT NULL default '',
  `billing_country` varchar(255) NOT NULL default '',
  `shipping_address` varchar(255) NOT NULL default '',
  `shipping_city` varchar(255) NOT NULL default '',
  `shipping_province` varchar(255) NOT NULL default '',
  `shipping_postal` varchar(255) NOT NULL default '',
  `shipping_country` varchar(255) NOT NULL default '',
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `clients`
--


-- --------------------------------------------------------

--
-- Table structure for table `client_access_logs`
--

CREATE TABLE IF NOT EXISTS `client_access_logs` (
  `log_id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL,
  `ipv4_address` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `client_access_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `client_files`
--

CREATE TABLE IF NOT EXISTS `client_files` (
  `file_id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  `content` longblob NOT NULL,
  `description` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `client_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `client_notes`
--

CREATE TABLE IF NOT EXISTS `client_notes` (
  `note_id` int(11) NOT NULL auto_increment,
  `note` blob NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `client_id` int(11) NOT NULL default '0',
  `employee_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `client_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `company_name` varchar(255) NOT NULL default '',
  `tag_line` varchar(255) NOT NULL default '',
  `work_number` varchar(255) NOT NULL default '',
  `fax_number` varchar(255) NOT NULL default '',
  `email_address` varchar(255) NOT NULL default '',
  `markup_percent` int(11) NOT NULL default '0',
  `payment_terms` varchar(255) NOT NULL default '',
  `currency_symbol` varchar(255) NOT NULL default '',
  `business_number` varchar(255) NOT NULL default '',
  `tax1_name` varchar(255) NOT NULL default '',
  `tax1_percent` int(11) NOT NULL default '0',
  `tax2_name` varchar(255) NOT NULL default '',
  `tax2_percent` int(11) NOT NULL default '0',
  `records_per_page` int(11) NOT NULL default '0',
  `session_timeout` int(11) NOT NULL,
  `ssl_certificate_html` text NOT NULL,
  `billing_address` varchar(255) NOT NULL default '',
  `billing_city` varchar(255) NOT NULL default '',
  `billing_province` varchar(255) NOT NULL default '',
  `billing_postal` varchar(255) NOT NULL default '',
  `billing_country` varchar(255) NOT NULL default '',
  `shipping_address` varchar(255) NOT NULL default '',
  `shipping_city` varchar(255) NOT NULL default '',
  `shipping_province` varchar(255) NOT NULL default '',
  `shipping_postal` varchar(255) NOT NULL default '',
  `shipping_country` varchar(255) NOT NULL default '',
  `logo_name` varchar(255) default NULL,
  `logo_size` int(11) default '0',
  `logo_content` blob,
  `logo_type` varchar(255) default NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_name`, `tag_line`, `work_number`, `fax_number`, `email_address`, `markup_percent`, `payment_terms`, `currency_symbol`, `business_number`, `tax1_name`, `tax1_percent`, `tax2_name`, `tax2_percent`, `records_per_page`, `session_timeout`, `ssl_certificate_html`, `billing_address`, `billing_city`, `billing_province`, `billing_postal`, `billing_country`, `shipping_address`, `shipping_city`, `shipping_province`, `shipping_postal`, `shipping_country`, `logo_name`, `logo_size`, `logo_content`, `logo_type`, `updated`) VALUES
('Installation Company', '', '', '', '', 10, '30 days', '', '', '', 0, '', 0, 12, 6000, '', '', '', '', '', '', '', '', '', '', '', NULL, 0, NULL, NULL, '2009-10-12 12:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `company_files`
--

CREATE TABLE IF NOT EXISTS `company_files` (
  `file_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  `content` longblob NOT NULL,
  `description` varchar(255) NOT NULL,
  `public` int(11) NOT NULL default '0',
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `company_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `company_messages`
--

CREATE TABLE IF NOT EXISTS `company_messages` (
  `invoice_created` text NOT NULL,
  `payment_received` text NOT NULL,
  `survey_invite` text NOT NULL,
  `invoice_overdue` text NOT NULL,
  `login_notice` text NOT NULL,
  `client_notice` text NOT NULL,
  `employee_notice` text NOT NULL,
  `forgot_password` text NOT NULL,
  `survey_result` text NOT NULL,
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `credit_cards`
--

CREATE TABLE IF NOT EXISTS `credit_cards` (
  `credit_card_id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `type` blob NOT NULL,
  `number` blob NOT NULL,
  `expiration` blob NOT NULL,
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`credit_card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `credit_cards`
--


-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `home_number` varchar(255) default NULL,
  `work_number` varchar(255) default NULL,
  `mobile_number` varchar(255) default NULL,
  `pager_number` varchar(255) default NULL,
  `fax_number` varchar(255) default NULL,
  `email_address` varchar(255) NOT NULL default '',
  `account_password` varchar(255) NOT NULL default '',
  `hourly_rate` decimal(10,2) default NULL,
  `access_level` int(11) NOT NULL default '0',
  `billing_address` varchar(255) NOT NULL default '',
  `billing_city` varchar(255) NOT NULL default '',
  `billing_province` varchar(255) NOT NULL default '',
  `billing_postal` varchar(255) NOT NULL default '',
  `billing_country` varchar(255) NOT NULL default '',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `home_number`, `work_number`, `mobile_number`, `pager_number`, `fax_number`, `email_address`, `account_password`, `hourly_rate`, `access_level`, `billing_address`, `billing_city`, `billing_province`, `billing_postal`, `billing_country`, `created`) VALUES
(1, 'Installation', 'Created', NULL, NULL, NULL, NULL, NULL, 'install@domain.com', 'install', NULL, 3, '', '', '', '', '', '2009-10-12 12:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `employee_access_logs`
--

CREATE TABLE IF NOT EXISTS `employee_access_logs` (
  `log_id` int(11) NOT NULL auto_increment,
  `employee_id` int(11) NOT NULL,
  `ipv4_address` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `employee_access_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `expense_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `supplier_id` int(11) NOT NULL default '0',
  `method_id` int(11) NOT NULL default '0',
  `amount` decimal(10,2) NOT NULL default '0.00',
  `name` varchar(255) NOT NULL,
  `size` int(255) NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `content` longblob NOT NULL,
  `reference` varchar(255) NOT NULL,
  `date_received` date NOT NULL default '0000-00-00',
  `employee_id` int(11) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`expense_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `expenses`
--


-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE IF NOT EXISTS `expense_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `expense_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `invoice_id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `purpose` varchar(255) default NULL,
  `easypay_id` varchar(255) default NULL,
  `billing_email_address` varchar(255) default NULL,
  `billing_address` varchar(255) default NULL,
  `billing_city` varchar(255) default NULL,
  `billing_province` varchar(255) default NULL,
  `billing_postal` varchar(255) default NULL,
  `billing_country` varchar(255) default NULL,
  `tracking_number` varchar(255) default NULL,
  `purchase_order` varchar(255) default NULL,
  `date_created` date NOT NULL default '0000-00-00',
  `date_due` date NOT NULL default '0000-00-00',
  `shipping_address` varchar(255) default NULL,
  `shipping_city` varchar(255) default NULL,
  `shipping_province` varchar(255) default NULL,
  `shipping_postal` varchar(255) default NULL,
  `shipping_country` varchar(255) default NULL,
  `notes` text,
  `tax1_percent` int(11) NOT NULL default '0',
  `tax2_percent` int(11) NOT NULL default '0',
  `tax1_total` decimal(10,2) NOT NULL default '0.00',
  `tax2_total` decimal(10,2) NOT NULL default '0.00',
  `subtotal` decimal(10,2) NOT NULL default '0.00',
  `total` decimal(10,2) NOT NULL default '0.00',
  `received` decimal(10,2) NOT NULL default '0.00',
  `total_cost` decimal(10,2) NOT NULL default '0.00',
  `total_profit` decimal(10,2) NOT NULL default '0.00',
  `discount` decimal(10,2) NOT NULL default '0.00',
  `due` decimal(10,2) NOT NULL default '0.00',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `date_sent` date NOT NULL default '0000-00-00',
  `employee_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invoices`
--


-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `invoice_item_id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL default '0',
  `category_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `description` text,
  `serial_number` varchar(255) default NULL,
  `cost` decimal(10,2) NOT NULL default '0.00',
  `price` decimal(10,2) NOT NULL default '0.00',
  `quantity` decimal(10,2) NOT NULL,
  `tax1` int(11) NOT NULL default '0',
  `tax2` int(11) NOT NULL default '0',
  `warranty` int(11) NOT NULL default '0',
  `discount_value` decimal(10,2) NOT NULL,
  `tax1_value` decimal(10,2) NOT NULL default '0.00',
  `tax2_value` decimal(10,2) NOT NULL default '0.00',
  `extended` decimal(10,2) NOT NULL default '0.00',
  `extended_cost` decimal(10,2) NOT NULL,
  `extended_profit` decimal(10,2) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`invoice_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invoice_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(10,2) NOT NULL default '0.00',
  `price` decimal(10,2) NOT NULL default '0.00',
  `profit` decimal(10,2) NOT NULL default '0.00',
  `markup` decimal(10,2) NOT NULL default '0.00',
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `items`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE IF NOT EXISTS `item_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `item_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `invoice_id` int(11) NOT NULL default '0',
  `method_id` int(11) NOT NULL default '0',
  `amount` decimal(10,2) NOT NULL default '0.00',
  `name` varchar(255) NOT NULL,
  `size` int(255) NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `content` longblob NOT NULL,
  `reference` varchar(255) NOT NULL,
  `date_received` date NOT NULL default '0000-00-00',
  `employee_id` int(11) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `method_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `payment_methods`
--


-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `company_name` varchar(255) NOT NULL default '',
  `home_number` varchar(255) NOT NULL default '',
  `work_number` varchar(255) NOT NULL default '',
  `mobile_number` varchar(255) NOT NULL default '',
  `fax_number` varchar(255) NOT NULL default '',
  `email_address` varchar(255) NOT NULL default '',
  `billing_address` varchar(255) NOT NULL default '',
  `billing_city` varchar(255) NOT NULL default '',
  `billing_province` varchar(255) NOT NULL default '',
  `billing_postal` varchar(255) NOT NULL default '',
  `billing_country` varchar(255) NOT NULL default '',
  `shipping_address` varchar(255) NOT NULL default '',
  `shipping_city` varchar(255) NOT NULL default '',
  `shipping_province` varchar(255) NOT NULL default '',
  `shipping_postal` varchar(255) NOT NULL default '',
  `shipping_country` varchar(255) NOT NULL default '',
  `employee_id` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `suppliers`
--


-- --------------------------------------------------------

--
-- Table structure for table `supplier_notes`
--

CREATE TABLE IF NOT EXISTS `supplier_notes` (
  `note_id` int(11) NOT NULL auto_increment,
  `note` blob NOT NULL,
  `supplier_id` int(11) NOT NULL default '0',
  `employee_id` int(11) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `supplier_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE IF NOT EXISTS `surveys` (
  `survey_id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`survey_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `surveys`
--

