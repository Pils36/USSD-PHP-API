<?php  
	// mysql_connect("localhost", "root", "" )or die(mysql_error()); 
	// mysql_select_db("sessiondb") or die(mysql_error()); 

	// 1. Create a database connection
$connection = mysqli_connect("localhost", "root", "", "dice6_db");
// Test if connection succeeded
if (mysqli_connect_errno()) {
    die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
    );
}
?>




