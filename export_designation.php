<?PHP
 include('session.php');
  include('config.php');
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and designation = 'Y'"));
    if ($userPrivCheckRow['designation'] != 'Y'){
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
  $filename = "Designation_data_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $sql = "SELECT desigid as 'Designation ID', Name as 'Designation', DATE_FORMAT(tbl_designation_info.creation_date,'%d-%b-%Y %h:%i %p') as 'Creation Date', 
  tbl_designation_info.created_by as 'Created By', DATE_FORMAT(tbl_designation_info.update_date,'%d-%b-%Y %h:%i %p') as 'Update Date', 
  tbl_designation_info.updated_by as 'Updated By' FROM tbl_designation_info";
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
