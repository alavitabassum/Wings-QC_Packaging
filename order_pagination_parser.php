<?php
// Make the script run only if there is a page number posted to this script
if(isset($_POST['pn'])){
	$rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
	$last = preg_replace('#[^0-9]#', '', $_POST['last']);
	$pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
	// This makes sure the page number isn't below 1, or more than our $last page
	if ($pn < 1) { 
    	$pn = 1; 
	} else if ($pn > $last) { 
    	$pn = $last; 
	}
	// Connect to our database here
	include("config.php");
	// This sets the range of rows to query for the chosen $pn
	$limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;
	// This is your query again, it is for grabbing just one page worth of rows by applying $limit
	$sql = "SELECT * FROM tbl_order_details where close != 'Y' or close is null $limit";
	$query = mysqli_query($conn, $sql);
	$dataString = '';
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
		$orderid = $row["orderid"];
		$merOrderRef = $row["merOrderRef"];
		$pickPointEmp = $row["pickPointEmp"];
        $Pick = $row["Pick"];
		//$itemdate = strftime("%b %d, %Y", strtotime($row["datemade"]));
		$dataString .= $orderid.'|'.$merOrderRef.'|'.$pickPointEmp.'|'.$Pick.'||';
	}
	// Close your database connection
    mysqli_close($conn);
	// Echo the results back to Ajax
	echo $dataString;
	exit();
}
?>