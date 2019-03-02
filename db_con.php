<?php
	error_reporting(0);
	if (!$conn=mysqli_connect("188.166.246.157","root","dbfunzonecscl","contessa")){
		$connError='Y';
        echo "Database connection unsuccessfull";
		} else {
		    echo "Test Conntection Successfull for Khalid";
		}
	error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));	
?>
