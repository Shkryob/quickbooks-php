<?php

/**
 * Example integration with an application
 * 
 * The idea behind the action queue is basically just that you want to add an 
 * action/ID pair to the queue whenever something happens in your application 
 * that you need to tell QuickBooks about. 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */
 
// Error reporting for easier debugging
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
 
// Require the queueuing class
require_once '../QuickBooks.php';

if (isset($_POST['customer']))
{
	// Oooh, here's a new customer, let's do some stuff with them
	
	// Connect to your own MySQL server....
	$link = mysqli_connect('localhost', 'your_mysqli_username', 'your_mysqli_password');
	if (!$link) 
	{
		die('Could not connect to MySQL: ' . mysqli_error());
	}
	
	// ... and use the correct database
	$selected = mysqli_select_db('your_database_name', $link);
	if (!$selected) 
	{
		die ('Could not select database: ' . mysqli_error());
	}	
	
	// Insert into our local MySQL database
	mysqli_query("INSERT INTO my_customer_table ( name, phone, email ) VALUES ( '" . $_POST['customer']['name'] . "', '" . $_POST['customer']['phone'] . "', '" . $_POST['customer']['email'] . "' ) ");
	$id_value = mysqli_insert_id();
	
	// QuickBooks queueing class
	$Queue = new QuickBooks_WebConnector_Queue('mysql://root:password@localhost/my_database');
	
	// Queue it up!
	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $id_value);
}

