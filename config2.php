<?php
    
	error_reporting(0);
	if (!$conn=mysqli_connect("localhost","root","123root456mysql","paperfly_db")){
    //if (!$conn=mysqli_connect("128.199.74.21","pf_rm_usr","!@#4paperfly_db$","paperfly_db")){
		$connError='Y';
		}
	error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));	
?>
