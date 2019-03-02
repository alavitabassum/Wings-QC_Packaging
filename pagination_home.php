<?php
// Connect to our database here
include("config.php");
// This first query is just to get the total count of rows
$orderCntsql = "SELECT count(1) FROM tbl_order_details where close != 'Y' or close is null";
$orderCntquery = mysqli_query($conn, $orderCntsql);
$orderCntrow = mysqli_fetch_row($orderCntquery);
// Here we have the total row count
$total_rows = $orderCntrow[0];
// Specify how many results per page
$rpp = 10;
// This tells us the page number of our last page
$last = ceil($total_rows/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
	$last = 1;
}
// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
<script>
    var rpp = <?php echo $rpp; ?>; // results per page
    var last = <?php echo $last; ?>; // last page number
    function request_page(pn){
	    var results_box = document.getElementById("results_box");
	    var pagination_controls = document.getElementById("pagination_controls");
	    results_box.innerHTML = "loading results ...";
	    var hr = new XMLHttpRequest();
        hr.open("POST", "order_pagination_parser.php", true);
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hr.onreadystatechange = function() {
	        if(hr.readyState == 4 && hr.status == 200) {
			    var dataArray = hr.responseText.split("||");
			    var html_output = "";
		        for(i = 0; i < dataArray.length - 1; i++){
                    
				    var itemArray = dataArray[i].split("|");
                    if (itemArray[1] === undefined){itemArray[1] = ""}
                    if (itemArray[2] === undefined){itemArray[2] = ""}
                    if (itemArray[3] === undefined){itemArray[3] = ""}
                    if (itemArray[0] != ""){
				        html_output += "<tr><td id = '1' style='width: 100px'>"
                                        +itemArray[0]+
                                        " </td><td id = '2' style='width: 100px'>"+itemArray[1]+
                                        "</td><td id = '3' style='width: 100px'>"+itemArray[2]+
                                        " </td><td id = '4' style='width: 100px'>"+itemArray[3]+
                                        "</td><td>" + "<?php echo ' Testing PHP'. $rpp; ?></td></tr>";
                    }
			    }
			    results_box.innerHTML = "<table id='orderTracker' style='text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E'><tbody>"
                                                +html_output+
                                            "</tbody></table>";
	        }
        }
        hr.send("rpp="+rpp+"&last="+last+"&pn="+pn);
	    // Change the pagination controls
	    var paginationCtrls = "";
        // Only if there is more than 1 page worth of results give the user pagination controls
        if(last != 1){
		    if (pn > 1) {
			    paginationCtrls += '<button onclick="request_page('+(pn-1)+')">&lt;</button>';
    	    }
		    paginationCtrls += ' &nbsp; &nbsp; <b>Page '+pn+' of '+last+'</b> &nbsp; &nbsp; ';
    	    if (pn != last) {
        	    paginationCtrls += '<button onclick="request_page('+(pn+1)+')">&gt;</button>';
    	    }
        }
	    pagination_controls.innerHTML = paginationCtrls +"<br><br>";
    }
</script>
</head>
<body>

    <div id="pagination_controls"></div>
    <div id="results_box"></div>
    <script> request_page(1); </script>

</body>
</html>