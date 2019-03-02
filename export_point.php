<?php
 include('session.php');
  include('config.php');
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and point = 'Y'"));
    if ($userPrivCheckRow['point'] != 'Y'){
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
  $filename = "Point_information_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $sql = "SELECT pointCode as 'Point Code', pointName as 'Point Name', pointAddress as 'Point Address', 
    tbl_thana_info.thanaName as 'Thana Name', tbl_district_info.districtName as 'District Name', 
    landlordName as 'Landlord Name', landlordContact as 'Landlord Contact', advanceMonth as 'Advance (Month)', 
    advancePaid as 'Advance Paid', monthlyRent as 'Monthly Rent', DATE_FORMAT(contractDate,'%d-%b-%Y') as 'Contract Date', 
    DATE_FORMAT(validTill,'%d-%b-%Y') as 'Valid Till',DATE_FORMAT(tbl_point_info.creation_date,'%d-%b-%Y %h:%i %p') as 'Creation Date', 
    tbl_point_info.created_by as 'Created By', DATE_FORMAT(tbl_point_info.update_date,'%d-%b-%Y %h:%i %p') as 'Update Date', 
    tbl_point_info.updated_by as 'Updated By' FROM tbl_point_info, tbl_thana_info, 
    tbl_district_info where tbl_point_info.thanaId = tbl_thana_info.thanaId and tbl_point_info.districtId = tbl_district_info.districtId";
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
