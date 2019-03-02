<?php
 include('session.php');
  include('config.php');
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and employee = 'Y'"));
    if ($userPrivCheckRow['employee'] != 'Y'){
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
  $filename = "Employee_data_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $sql = "SELECT empCode as 'Employee Code', empName as 'Employee Name', tbl_designation_info.name as 'Designation', 
  contactNumber as 'Contact Number', email, address, tbl_thana_info.thanaName as 'Thana Name', 
  tbl_district_info.districtName, DATE_FORMAT(doj,'%d-%b-%Y') as 'Date of Joining', hrBand as 'HR Band', 
  basicSalary as 'Basic Salary', houseRent as 'House Rent', tAllowance as 'Transport Allowance', qIncentive as 'Quarterly Incentive', 
  fBonus as 'Festival Bonus', bankAccount as 'Bank Account', tinNumber as 'TIN Number', 
  DATE_FORMAT(dob,'%d-%b-%Y') as 'Date of Birth', maritalStatus as 'Marital Status', 
  bloodGroup as 'Blood Group', gender as 'Gender', fatherName as 'Father Name', nid as 'NID', religion as 'Religion', emergencyName as 'Emergency Name', 
  emergencyAddress as 'Emergency Address', emergencyNumber as 'Emergency Number', relationship as 'Relationship', 
  DATE_FORMAT(tbl_employee_info.creation_date,'%d-%b-%Y %h:%i %p') as 'Creation Date', 
  tbl_employee_info.created_by as 'Created By', DATE_FORMAT(tbl_employee_info.update_date,'%d-%b-%Y %h:%i %p') as 'Update Date', 
  tbl_employee_info.updated_by as 'Updated By' FROM tbl_employee_info left join tbl_thana_info on tbl_employee_info.thanaId =  tbl_thana_info.thanaId, tbl_designation_info,  tbl_district_info 
  where tbl_employee_info.desigid = tbl_designation_info.desigid and tbl_employee_info.districtId = tbl_district_info.districtId";
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
