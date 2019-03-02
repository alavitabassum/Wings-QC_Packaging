<?php
                include('config.php');
 
             $select="select districtid, districtName from tbl_district_info";
             $result = mysqli_query($conn,$select);
?>
<html>
<head>
<script src="js/jquery-2.2.0.min.js"></script>

    </head>

   <body>
     <p id="heading">Dynamic Select Option Menu Using Ajax and PHP</p>
	 <center>
	   <div id="select_box">

         <select name="districtid" onchange="fetch_select(this.value);">
           <option>
              Select District
           </option>
           
           <?php
             

             foreach ($result as $row)
             {
              echo "<option value=".$row['districtid'].">".$row['districtName']."</option>";
             }
           ?>

         </select>

         <select id="thana">
         </select>
	  
       </div>     
	 </center>
<script type="text/javascript">

function fetch_select(val)
{
   $.ajax({
     type: 'post',
     url: 'fetch_thana.php',
     data: {
       get_thanaid:val
     },
     success: function (response) {
       document.getElementById("thana").innerHTML=response; 
     }
   });
}

</script>	  
   
   </body>
</html>