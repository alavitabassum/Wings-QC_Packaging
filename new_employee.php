<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $districtsql = "select districtId, districtName from tbl_district_info";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and employee = 'Y'"));
    if ($userPrivCheckRow['employee'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post" name="newEmp" style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">New Employee</p>
                <table style="width: 100%">
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Employee Details:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Employee Name</label>
                        </td>
                        <td>
                            <input type="text" name="empName" style="height: 25px" required>
                        </td>
                        <td>
                            <label> Designation</label>
                        </td>
                        <td>
                            <select id="designation" name="desigid" style="width: 95%; height: 25px" required>
                                <option></option>
                                <?php
                                    $desigsql = "select desigid, name from tbl_designation_info";
                                    $desigresult = mysqli_query($conn,$desigsql);
                                    foreach ($desigresult as $desigrow){
                                        echo "<option value='".$desigrow['desigid']."'>".$desigrow['name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label> Contact Number</label>
                        </td>
                        <td>
                            <input type="text" name="contactNumber" style="height: 25px" required>
                        </td>
                        <td>
                            <label> Email Address</label>
                        </td>
                        <td>
                            <input type="email" name="email" placeholder="your@paperfly.com.bd" style="height: 25px">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label> Address</label>
                        </td>
                        <td colspan="3">
                            <input type="text" name="address" style="width: 98%; height: 25px" required>
                        </td>
                        <td>
                            <label>District</label>
                        </td>
                        <td>
                            <select name="districtId" style="width: 95%; height: 25px" onchange="fetch_select(this.value);" required>
                                <option></option>
                                <?php
                                    foreach ($districtresult as $districtrow){
                                        echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label>Thana</label>
                        </td>
                        <td>
                            <select id="thana" name="thanaId" style="width: 95%; height: 25px" required></select>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Date of Joining</label>
                        </td>
                        <td>
                            <input type="text" name="doj" style="height: 25px" onfocus="displayCalendar(document.newEmp.doj,'dd-mm-yyyy',this)" required>
                        </td>
                        <td>
                            <label>HR Band</label>
                        </td>
                        <td>
                            <input type="text" name="hrBand" style="height: 25px">
                        </td>
                        <td>
                            <label>Basic Salary</label>
                        </td>
                        <td>
                            <input type="text" name="basicSalary" style="height: 25px" onkeyup="return isNumberKey(this)" required>
                        </td>
                        <td>
                            <label>House Rent</label>
                        </td>
                        <td>
                            <input type="text" name="houseRent" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Transport Allowance</label>
                        </td>
                        <td>
                            <input type="text" name="tAllowance" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>
                        <td>
                            <label>Quarterly Incentive(%)</label>
                        </td>
                        <td>
                            <input type="text" name="qIncentive" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>                        <td>
                            <label>Festival Bonus(%)</label>
                        </td>
                        <td>
                            <input type="text" name="fBonus" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>
                        <td>
                            <label>Bank A/C</label>
                        </td>
                        <td>
                            <input type="text" name="bankAccount" style="height: 25px">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>TIN Number</label>
                        </td>
                        <td>
                            <input type="text" name="tinNumber" style="height: 25px">
                        </td>
                        <td>
                            <label>Date of Birth</label>
                        </td>
                        <td>
                            <input type="text" name="dob" style="height: 25px" onfocus="displayCalendar(document.newEmp.dob,'dd-mm-yyyy',this)">
                        </td>
                        <td>
                            <label>Marital Status</label>
                        </td>
                        <td>
                            <select id="maritalStatus" name="maritalStatus" style="width: 95%; height: 25px">
                                <option value="S">Single</option>
                                <option value="M">Married</option>
                                <option value="D">Divorced</option>
                            </select>
                        </td>
                        <td>
                            <label>Blood Group</label>
                        </td>
                        <td>
                            <input type="text" name="bloodGroup" style="height: 25px">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Gender</label>
                        </td>
                        <td>
                            <select id="gender" name="gender" style="width: 95%; height: 25px">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </td>
                        <td>
                            <label>Father's/Spouse Name</label>
                        </td>
                        <td>
                            <input type="text" name="fatherName" style="height: 25px">
                        </td>
                        <td>
                            <label>NID</label>
                        </td>
                        <td>
                            <input type="text" name="nid" style="height: 25px">
                        </td>
                        <td>
                            <label>Religion</label>
                        </td>
                        <td>
                            <select id="religion" name="religion" style="width: 95%; height: 25px">
                                <option value="I">Islam</option>
                                <option value="C">Christianity</option>
                                <option value="H">Hinduism</option>
                                <option value="B">Buddhism</option>
                                <option value="O">Others</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Point Assignment:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Point List</label>
                        </td>
                        <td>
                            <select id="pointSel" name="pointSel" style="width: 98%; height: 100px" multiple>
                            <?php
                                $pointsql = "Select pointCode, pointName from tbl_point_info";
                                $pointresult = mysqli_query($conn, $pointsql);
                                foreach ($pointresult as $pointrow) {
                                    echo "<option value='".$pointrow['pointCode']."'>".$pointrow['pointName']."</option>";
                                }
                            ?>
                            </select>
                        </td>
                        <td>
                            <input type="button" name="pointAdd" value="+" style="width: 40px" class="btn btn-default" onclick="addPoint()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                            <input type="button" name="pointRem" value="-" style="width: 40px" class="btn btn-default" onclick="removeItem(pointAssigned)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                        </td>
                        <td>
                            <select id="pointAssigned" name="pointAssigned[]" style="width: 98%; height: 100px" multiple></select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Emergency Contact Details:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Name</label>
                        </td>
                        <td>
                            <input type="text" name="emergencyName" style="height: 25px">
                        </td>
                        <td>
                            <label> Address</label>
                        </td>
                        <td>
                            <input type="text" name="emergencyAddress" style="height: 25px">
                        </td>
                        <td>
                            <label> Contact Number</label>
                        </td>
                        <td>
                            <input type="text" name="emergencyNumber" style="height: 25px">
                        </td>
                        <td>
                            <label> Relationship</label>
                        </td>
                        <td>
                            <input type="text" name="relationship" style="height: 25px">
                        </td>
                    </tr>
                </table>
                <div style="float: right">
                    <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('pointAssigned', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                </div>
                <br>
                <br>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $maxempid ="Select max(empid) as emplid from tbl_employee_info";
                    $maxresult = mysqli_query($conn, $maxempid);
                    foreach ($maxresult as $maxrow){
                        $employeeId = $maxrow['emplid']+1;
                    }
                    switch (strlen($employeeId)){
                        case 1: $employeeId = "E000".$employeeId;
                        break;
                        case 2: $employeeId = "E00".$employeeId;
                        break;
                        case 3: $employeeId = "E0".$employeeId;
                        break;
                        case 4: $employeeId = "E".$employeeId;
                        break;
                        default:
                            echo "Null";
                    }
                    $empName = trim($_POST['empName']);
                    $empName = mysqli_real_escape_string($conn, $empName);
                    $desigid = trim($_POST['desigid']);
                    $contactNumber = trim($_POST['contactNumber']);
                    $contactNumber = mysqli_real_escape_string($conn, $contactNumber);
                    $email = trim($_POST['email']);
                    $email = mysqli_real_escape_string($conn, $email);
                    $address = trim($_POST['address']);
                    $address = mysqli_real_escape_string($conn, $address);
                    $thanaId = trim($_POST['thanaId']);
                    $districtId = trim($_POST['districtId']);
                    $doj = date("Y-m-d", strtotime(trim($_POST['doj'])));
                    $hrBand = trim($_POST['hrBand']);
                    $hrBand = mysqli_real_escape_string($conn, $hrBand);
                    $basicSalary = trim($_POST['basicSalary']);
                    $houseRent = trim($_POST['houseRent']);
                    $tAllowance = trim($_POST['tAllowance']);
                    $qIncentive = trim($_POST['qIncentive']);
                    $fBonus = trim($_POST['fBonus']);
                    $bankAccount = trim($_POST['bankAccount']);
                    $bankAccount = mysqli_real_escape_string($conn, $bankAccount);
                    $tinNumber = trim($_POST['tinNumber']);
                    $tinNumber = mysqli_real_escape_string($conn, $tinNumber);
                    $dob = date("Y-m-d", strtotime(trim($_POST['dob'])));
                    $maritalStatus = trim($_POST['maritalStatus']);
                    $bloodGroup = trim($_POST['bloodGroup']);
                    $bloodGroup = mysqli_real_escape_string($conn, $bloodGroup);
                    $gender = trim($_POST['gender']);
                    $fatherName = trim($_POST['fatherName']);
                    $nid = trim($_POST['nid']);
                    $religion = trim($_POST['religion']);
                    $emergencyName = trim($_POST['emergencyName']);
                    $emergencyName = mysqli_real_escape_string($conn, $emergencyName);
                    $emergencyAddress = trim($_POST['emergencyAddress']);
                    $emergencyAddress = mysqli_real_escape_string($conn, $emergencyAddress);
                    $emergencyNumber = trim($_POST['emergencyNumber']);
                    $emergencyNumber = mysqli_real_escape_string($conn, $emergencyNumber);
                    $relationship = trim($_POST['relationship']);
                    $relationship = mysqli_real_escape_string($conn, $relationship);
                    $insertsql = "INSERT INTO  tbl_employee_info (empCode ,empName , desigid ,contactNumber, email, address, thanaId, districtId,  
                    doj ,hrBand ,basicSalary ,houseRent, tAllowance, qIncentive, fBonus, bankAccount, tinNumber, dob, maritalStatus, 
                    bloodGroup, gender, fatherName, nid, religion, emergencyName, emergencyAddress, emergencyNumber, relationship, 
                    creation_date , created_by ) 
                    VALUES ('$employeeId' ,'$empName' , '$desigid' ,'$contactNumber' ,'$email' ,'$address'  ,'$thanaId', '$districtId',
                    '$doj' , '$hrBand', '$basicSalary', '$houseRent', '$tAllowance', '$qIncentive', '$fBonus', '$bankAccount', '$tinNumber',  
                    '$dob', '$maritalStatus', '$bloodGroup', '$gender', '$fatherName', '$nid', '$religion', '$emergencyName', 
                    '$emergencyAddress', '$emergencyNumber', '$relationship', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                $checked_point = $_POST['pointAssigned'];
                                $count = count($checked_point);
                                for ($c=0; $c < $count; $c++) {
                                    //echo $checked_thana[$c];
                                    //echo "<br>";
                                    $ins_qry="INSERT INTO tbl_employee_point(empCode, pointCode, creation_date, created_by) 
                                    VALUES('$employeeId', '".$checked_point[$c]."',  NOW() + INTERVAL 6 HOUR, '$user_check')";
                                    mysqli_query($conn, $ins_qry);
                                }
                                  echo "<div class='alert alert-success'>";
        //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                        echo "Employee created successfully with ".$count." point assignment";
                                  echo "</div>";
                        }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function fetch_select(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'thanafetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("thana").innerHTML = response;
                        document.getElementById("thana2").innerHTML = response;
                    }
                });
            }

            function addPoint()
            {
                var listbox;
                var x = document.getElementById("pointSel");
                for (var i = 0; i < x.options.length; i++)
                {
                    if (x.options[i].selected == true)
                    {
                        x.options[i].value + "-" + x.options[i].textContent
                        listbox += "<option value=" + x.options[i].value + ">" + x.options[i].textContent + "</option>";
                    }
                }
                document.getElementById('pointAssigned').innerHTML = document.getElementById('pointAssigned').innerHTML + listbox;
            }

            function removeItem(selectbox)
            {
                var i;
                for (i = selectbox.options.length - 1; i >= 0; i--)
                {
                    if (selectbox.options[i].selected)
                        selectbox.remove(i);
                }
            }
            function selectDeselect(listid, status)
            {
                var listb = document.getElementById(listid);
                var len = listb.options.length;
                for (var i = 0; i < len; i++)
                {
                    listb.options[i].selected = status;
                }
            }  
        </script>
    </body>
</html>
