<?php
 include('session.php');
  include('config.php');
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and RateChart = 'Y'"));
    if ($userPrivCheckRow['RateChart'] != 'Y'){
        exit();
    }
    if($user_check == ''){
        header("location: main");
    }
    if($user_type == 'Merchant'){
        header("location: main");    
    }

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "Rate_Chart_data_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $sql = "SELECT rateChartName as 'Rate Chart Name', packageOption as 'Package Option', deliveryOption as 'Delivery Option', destination as 'Destination', charge as 'Charge', packageDim 'package Dimension', orderCut as  'Max Order Cut Off', DelTimeLength as 'Max Delivery Duration',  
  DATE_FORMAT(tbl_rate_type.created_date,'%d-%b-%Y %h:%i %p') as 'Creation Date', 
  tbl_rate_type.created_by as 'Created By', DATE_FORMAT(tbl_rate_type.update_date,'%d-%b-%Y %h:%i %p') as 'Update Date', tbl_rate_type.updated_by as 'Updated By' FROM tbl_rate_type";
  $result = mysqli_query($conn, $sql);
  foreach ($result as $row){
      
  
  //while(false !== ($row = pg_fetch_assoc($result))) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
  //}
  }
  mysqli_close($conn);
  exit;
?>
