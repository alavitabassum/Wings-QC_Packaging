<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_capacityrel WHERE user_id = $user_id_chk and deliveryCapacity = 'Y'"));
    if ($userPrivCheckRow['deliveryCapacity'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search Employee</p>
                Search by : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchType" style="font: 15px 'paperfly roman'">
                    <option value="empName">Employee Name</option>
                    <option value="empCode">Employee Code</option>
                    <option value="contactNumber">Contact Number</option>
                </select><br>
                Search Text :&nbsp; <input type="text" name="searchText" style="height: 30px" placeholder="Search criteria"><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
                <input id="editid" type="hidden" name="empCode">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit'])) and (!isset($_POST['cancel'])) and (!isset($_POST['search']))){
                    $listEmp = "select * from tbl_employee_info";
                    $empResult = mysqli_query($conn, $listEmp);
                    ?>
                        <table class='table table-hover'>
                            <tr style='background-color:#dad8d8; li'>
                                <th>Employee Code</th>
                                <th>Employee Name</th> 
                                <th>Contact Number</th>
                                <th>Edit</th>
                            </tr>
                            <?php
                                foreach ($empResult as $empRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $empRow['empCode'];?>"><?php echo $empRow['empCode'];?></label></td>
                                            <td><?php echo $empRow['empName'];?></td>
                                            <td><?php echo $empRow['contactNumber'];?></td>
                                            <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$empRow['empCode']."')"?>"></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>
                    <?php
                        }
                    ?>
                </div>
            </form>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <?php
                //After search edit screen
                if (isset($_POST['edit'])) {
                    $empCode = trim($_POST['empCode']);
                    $editSQL = "Select * from tbl_employee_info where empCode = '$empCode'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $emprow = mysqli_fetch_array($editResult);
                ?>
            <div>
                <form id="newEmp" name="newEmp" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Employee Point Relocation</p>
                    <input type="hidden" name="empCode" value="<?php echo $emprow['empCode'];?>">
                        <table style="width: 100%">
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold; color: #0026ff"><u>Employee Details for <?php echo $emprow['empCode'];?>:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Employee Name</label>
                                </td>
                                <td>
                                    <input type="text" name="empName" style="height: 25px" value="<?php echo $emprow['empName'];?>" readonly>
                                </td>
                                <td>
                                    <label> Designation</label>
                                </td>
                                <td>
                                    <select id="designation" name="desigid" style="width: 98%; height: 25px" readonly>
                                        <?php
                                            $desigsql = "select desigid, name from tbl_designation_info";
                                            $desigresult = mysqli_query($conn,$desigsql);
                                            foreach ($desigresult as $desigrow){
                                                if ($desigrow['desigid']== $emprow['desigid']) {
                                                    echo "<option value='".$desigrow['desigid']."' selected>".$desigrow['name']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label> Contact Number</label>
                                </td>
                                <td>
                                    <input type="text" name="contactNumber" style="height: 25px" value="<?php echo $emprow['contactNumber']; ?>" readonly>
                                </td>
                                <td>
                                    <label> Email Address</label>
                                </td>
                                <td>
                                    <input type="email" name="email" placeholder="your@paperfly.com.bd" style="height: 25px" value="<?php echo $emprow['email']; ?>" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Point Assignment:</u></label>
                                </td>
                            </tr>
                            <tr>
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
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="pointAdd" value="+" style="width: 40px" class="btn btn-default" onclick="addPoint()"><br><br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="pointRem" value="-" style="width: 40px" class="btn btn-default" onclick="removeItem(pointAssigned)"><br>
                                </td>
                                <td>
                                    <select id="pointAssigned" name="pointAssigned[]" style="width: 98%; height: 100px" multiple>
                                    <?php
                                        $pointCoverageSQL = "Select tbl_employee_point.pointCode, tbl_point_info.pointName from tbl_employee_point, 
                                        tbl_point_info where tbl_employee_point.pointCode=tbl_point_info.pointCode 
                                        and tbl_employee_point.empCode='$empCode'";
                                        $pointCovResult = mysqli_query($conn, $pointCoverageSQL);
                                        foreach ($pointCovResult as $pointCovRow){
                                            echo "<option value='".$pointCovRow['pointCode']."'>".$pointCovRow['pointName']."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>
                                <td colspan="2"><p>&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Relocated Points</u></b><?php
                                        $relEmp = $emprow['empCode'];
                                        $relSQL = "select tbl_emppoint_tmp.*, tbl_point_info.pointName from tbl_emppoint_tmp, tbl_point_info where tbl_emppoint_tmp.pointCode=tbl_point_info.pointCode and empCode = '$relEmp'";
                                        $relResult =mysqli_query($conn, $relSQL);
                                        foreach ($relResult as $relRow){
                                            if ($relRow['empCode'] !=''){
                                                echo " - ".$relRow['pointName'];
                                            }
                                        } 
                                        ?></p><p>&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Effective From</u></b> <?php if ($relRow['pointName'] !='') {echo date("d-M-Y", strtotime($relRow['from_date']))." to ".date("d-M-Y", strtotime($relRow['through_date']));}?></p>
                                    <input type="hidden" name="assPoint" value="<?php echo $relRow['pointName'];?>">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="Cancel Relocation" class="btn-info"></td>
                            </tr>
                            <tr>
                                <td><label>Effective from</label></td>
                                <td><input style="height: 25px" type="text" name="startDate" value="<?php if ($startDate !='') {echo date("d-m-Y", strtotime($startDate));} else {echo date("d-m-Y");}?>" onfocus="displayCalendar(document.newEmp.startDate,'dd-mm-yyyy',this)" required></td>
                                <td><label>to</label></td>
                                <td><input style="height: 25px" type="text" name="endDate" value="<?php if ($endDate !='') {echo date("d-m-Y", strtotime($endDate));} else {echo date("d-m-Y");}?>" onfocus="displayCalendar(document.newEmp.endDate,'dd-mm-yyyy',this)" required></td>
                            </tr>
                        </table>
                        <div style="float: left">
                            <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('pointAssigned', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </div>
                        <br>
                        <br>
                    </form>
                </div>
            <?php
                }
                //Search result
                if (isset($_POST['search'])) {
                    $searchType = trim($_POST['searchType']);
                    $searchText = trim($_POST['searchText']);
                    $searchSQL = "select empCode, empName, contactNumber from tbl_employee_info where $searchType like '%$searchText%'";
                    $searchResult = mysqli_query($conn, $searchSQL);
                    $searchCount = mysqli_num_rows($searchResult);
                    ?><p style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p><?php
                ?>
                    <form action="" method="post" style="color: #16469E; font: 15px 'paperfly roman'">
                    <input id="searchuserid" type="hidden" name="empCode"> 
                    <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Employee Code</th>
                            <th>Employee Name</th> 
                            <th>Contact Number</th>
                            <th>Edit</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr>
                                        <td><label id="<?php echo $searchRow['empCode'];?>"><?php echo $searchRow['empCode'];?></label></td>
                                        <td><?php echo $searchRow['empName'];?></td>
                                        <td><?php echo $searchRow['contactNumber'];?></td>
                                        <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$searchRow['empCode']."')"?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    </form>
                <?php
                    }
                    if (isset($_POST['cancel'])) {
                        $empCode = trim($_POST['empCode']);
                        $assPoint = trim($_POST['assPoint']);
                        if ($assPoint !='' ){
                            $deletepoint = "delete from tbl_employee_point where empCode ='$empCode'";
                            mysqli_query($conn, $deletepoint);
                            $inspoint ="insert into tbl_employee_point select * from tbl_emppoint_orig where empCode ='$empCode'";
                            mysqli_query($conn,$inspoint);
                            $deletetmppoint = "delete from tbl_emppoint_tmp where empCode ='$empCode'";
                            mysqli_query($conn,$deletetmppoint);
                            $deleteorigpoint = "delete from tbl_emppoint_orig where empCode ='$empCode'";
                            mysqli_query($conn,$deleteorigpoint);
                            echo "<div class='alert alert-success'>";
                                echo "Point Relocation cancel successfull";
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-info'>";
                                echo "<strong>No point found to cancel</strong>"; 
                            echo "</div>";
                        }
                    }
                    if (isset($_POST['submit'])) {
                        $assPoint = trim($_POST['assPoint']);
                        $empCode = trim($_POST['empCode']);
                        $startDate = date("Y-m-d", strtotime(trim($_POST['startDate'])));
                        $startDate = mysqli_real_escape_string($conn, $startDate);
                        $endDate = date("Y-m-d", strtotime(trim($_POST['endDate'])));
                        $endDate = mysqli_real_escape_string($conn, $endDate);
                        if ($endDate < $startDate) {
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Unable to relocate :  invalid effective date </strong>"; 
                            echo "</div>";
                            exit;
                        }
                        if ($assPoint !='' ){
                            echo "<div class='alert alert-info'>";
                                echo "<strong>First you have to cancel existing relocations</strong>"; 
                            echo "</div>";
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Unable to relocate</strong>"; 
                            echo "</div>";
                            exit;
                        } 
                        $insertSQL = "insert into tbl_emppoint_orig select * from tbl_employee_point where empCode ='$empCode'";
                        $insertResult = mysqli_query($conn, $insertSQL);
                        //Insert employee points
                        $checked_point = $_POST['pointAssigned'];
                        $count = count($checked_point);
                        for ($c=0; $c < $count; $c++) {
                            //echo $checked_thana[$c];
                            //echo "<br>";
                            $ins_qry="INSERT INTO tbl_emppoint_tmp(empCode, pointCode, from_date, through_date, created_by) 
                            VALUES('$empCode', '".$checked_point[$c]."', '$startDate', '$endDate', '$user_check')";
                            //mysqli_query($conn, $ins_qry);
                            if (!mysqli_query($conn,$ins_qry))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            }
                        }
                        echo "<div class='alert alert-success'>";
                            echo "Point Relocation successfully";
                        echo "</div>";                                                                   
                    }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">

            function ttVal(submitVal)
            {
                var inptext = document.getElementById(submitVal).innerHTML;
                document.getElementById("editid").value = inptext;
                document.getElementById("searchuserid").value = inptext;
            }
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
