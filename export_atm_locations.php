<?PHP
  include('config.php');
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "ATM_Location_Info_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $sql = "SELECT atmLocationID as 'ATM Location ID', tbl_bank_info.bankName as 'Bank Name', locationName as 'ATM Name', address as 'Address', tbl_district_info.districtName as 'District', DATE_FORMAT(tbl_atm_locations.creationDate,'%d-%b-%Y %h:%i %p') as 'Creation Date', tbl_atm_locations.createdBy as 'Created By', DATE_FORMAT(tbl_atm_locations.updateDate,'%d-%b-%Y %h:%i %p') as 'Update Date', tbl_atm_locations.updateBy as 'Updated By' FROM `tbl_atm_locations` left join tbl_bank_info on tbl_bank_info.bankID = tbl_atm_locations.bankID left join tbl_district_info on tbl_district_info.districtId = tbl_atm_locations.districtID";
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
