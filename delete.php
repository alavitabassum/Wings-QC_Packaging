<?php
  include('config.php');

 if(isset($_GET['delete']))
   {
    $delete_id =$_GET['delete'];
    mysqli_query($conn,"DELETE FROM temporary_merchant_reg where applyid = '$delete_id'");
    echo "<meta http-equiv='refresh' content='0;url=unregistered_merchant_list.php'>";
  }
  




?>  