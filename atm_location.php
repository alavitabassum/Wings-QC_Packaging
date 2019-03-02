<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $bankSQL = "select bankID, bankName from tbl_bank_info";
    $bankResult = mysqli_query($conn, $bankSQL);
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);
    $atmLocationSQL = "SELECT atmLocationID, tbl_bank_info.bankName, locationName, address, tbl_district_info.districtName FROM `tbl_atm_locations` left join tbl_bank_info on tbl_bank_info.bankID = tbl_atm_locations.bankID left join tbl_district_info on tbl_district_info.districtId = tbl_atm_locations.districtID";
    $atmLocationResult = mysqli_query($conn, $atmLocationSQL);

    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and atmLocation = 'Y'"));
    if ($userPrivCheckRow['atmLocation'] != 'Y'){
        exit();
    }
?>
    <div style="margin-left: 15px; width: 98%; clear: both">
        <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">ATM Locations</p>
            <div style="width: 500px; padding: 10px">
                <table style="width: 100%">
                    <tr>
                        <td><label>Bank Name</label></td>
                        <td style="height: 50px"><select style="width: 100%" id="bankID" name="bankID">
                            <?php foreach($bankResult as $bankRow){?>
                                <option value="<?php echo $bankRow['bankID'];?>"><?php echo $bankRow['bankName'];?></option>
                            <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>ATM Name</label></td>
                        <td><input style="height: 25px" type="text" name="locationName"></td>
                    <tr>
                        <td><label>Address</label></td>
                        <td><input style="height: 25px" type="text" name="address"></td>
                    </tr>
                    <tr>
                        <td><label>District Name</label></td>
                        <td><select style="width: 100%" id="districtID" name="districtID">
                            <?php foreach($districtresult as $districtRow){?>
                                <option value="<?php echo $districtRow['districtId'];?>"><?php echo $districtRow['districtName'];?></option>
                            <?php }?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <input class="btn btn-primary" style="margin-left: 5px" type="submit" name="atmLocationAdd" value="ADD">
            <p id="alertMSG"></p>
        </form>
        <?php
            if(isset($_POST['atmLocationAdd'])){
                $bankID = trim($_POST['bankID']);
                $locationName = trim($_POST['locationName']);
                $locationName = mysqli_real_escape_string($conn, $locationName);
                $address = trim($_POST['address']);
                $address = mysqli_real_escape_string($conn, $address);
                $districtID = trim($_POST['districtID']);
                $insertLocationSQL = "Insert into tbl_atm_locations (bankID, locationName, address, districtID, creationDate, createdBy) values ('$bankID', '$locationName', '$address', '$districtID', NOW() + INTERVAL 6 HOUR, '$user_check')";
                if (!mysqli_query($conn, $insertLocationSQL)){
                    $error ="Insert Error : " . mysqli_error($conn);
                    echo "<script> document.getElementById('alertMSG').style.color = 'red'; </script>";
                    echo $error;
                } else {
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }
        ?>
        <div class="container">
            <div class="row" style="font: 11px 'paperfly roman'">
                <button id='export' class='btn btn-info' style="margin-top: 25px; margin-bottom: 5px;  float: right" onclick="return exportATMLocations()">Export to Excel</button>
                <table class="table table-hover" id="ATM-location-table">
                    <thead>
                    <tr>
                        <th>ATM Location ID</th>
                        <th>Bank Name</th>
                        <th>Location Name</th>
                        <th>Address</th>
                        <th>District Name</th>
		                <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody style="font: 11px 'paperfly roman'">
                        <?php 
                            foreach($atmLocationResult as $atmLocationRow){
                        ?>
                            <tr id="tr<?php echo $atmLocationRow['atmLocationID'];?>">
                                <td><?php echo $atmLocationRow['atmLocationID'];?></td>
                                <td><?php echo $atmLocationRow['bankName'];?></td>
                                <td id="locationName<?php echo $atmLocationRow['atmLocationID'];?>"><?php echo $atmLocationRow['locationName'];?></td>
                                <td id="address<?php echo $atmLocationRow['atmLocationID'];?>"><?php echo $atmLocationRow['address'];?></td>
                                <td><?php echo $atmLocationRow['districtName'];?></td>
                                <td><button class="btn btn-primary" onclick="<?php echo "return atmEdit('".$atmLocationRow['atmLocationID']."')"; ?>">Edit</button></td>
                            </tr>
                        <?php 
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
        mysqli_close($conn);
    ?>

    <div id="updateModal" class="modal" style="width: auto">
      <!-- Modal content -->
      <div class="modal-content" style="width: 500px">
        <div class="modal-header" style="height: 40px; background-color: #16469E">
          <span class="close" onclick=" return fncClose()">&times;</span>
          <h3 style="font: 25px 'paperfly roman'">ATM Location Edit</h3>
        </div>
        <div class="modal-body" style="font: 13px 'paperfly roman'">
            <br>
            <div class="form-group">
                <label class="col-sm-4 control-label">ATM Location ID</label>
                <div class="col-sm-8">
                    <label id="atmLocationID"></label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Bank Name</label>
                <div class="col-sm-8">
                    <select style="width: 100%" id="bankIDUpdate" name="bankID">
                    <?php foreach($bankResult as $bankRow){?>
                        <option value="<?php echo $bankRow['bankID'];?>"><?php echo $bankRow['bankName'];?></option>
                    <?php }?>
                    </select>                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">ATM Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="locationNameUpdate" name="locationName" value="" style="height: 25px">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="addressUpdate" name="address" value="" style="height: 25px">
                </div>
            </div>
            <div class="form-group">
            <label class="col-sm-4 control-label">District Name</label>
                <div class="col-sm-8">
                    <select style="width: 100%" id="districtIDUpdate" name="districtID">
                    <?php foreach($districtresult as $districtRow){?>
                        <option value="<?php echo $districtRow['districtId'];?>"><?php echo $districtRow['districtName'];?></option>
                    <?php }?>
                    </select>
                </div>
            </div>
            <div style="text-align: center" class="form-group">                
                <button id="btnUpCancel" type="button" class="btn btn-default">Cancel</button>
                <button id="btnSave" type="button" class="btn btn-primary">Save Changes</button>
                <p id="successMsg"></p>
            </div>
        </div>
      </div>
    </div>

    <script>
        $(document).ready(function ()
        {
            $('#ATM-location-table').bdt({
                showSearchForm: 1,
                showEntriesPerPageField: 1
            });

        });
        $(window).load(function ()
        {
            $('#bankID').select2();
            $('#districtID').select2();
        });

        function atmEdit(atmID)
        {
            var upmodal = document.getElementById('updateModal');
            upmodal.style.display = "block";
            document.getElementById('atmLocationID').innerHTML = '&nbsp;&nbsp;&nbsp;' + atmID;
            document.getElementById('locationNameUpdate').value = document.getElementById('locationName' + atmID).innerHTML;
            document.getElementById('addressUpdate').value = document.getElementById('address' + atmID).innerHTML;
        }

        function fncClose()
        {
            var upmodal = document.getElementById('updateModal');
            upmodal.style.display = "none";
        }

        $('#btnUpCancel').click(function ()
        {
            var upmodal = document.getElementById('updateModal');
            upmodal.style.display = "none";
        })

        $('#btnSave').click(function ()
        {
            var atmID = $('#atmLocationID').text();
            var bankID = $('#bankIDUpdate').val();
            var locationName = $('#locationNameUpdate').val();
            var address = $('#addressUpdate').val();
            var districtID = $('#districtIDUpdate').val();
            var flag = 'ATMupdate';
            //alert(atmID + '||' + bankID + '||' + locationName + '||' + address + '||' + districtID);

            $.ajax({
                type: 'post',
                url: 'toupdateorders',
                data: {
                    get_orderid: atmID.trim(),
                    bank: bankID,
                    atmLocation: locationName,
                    atmAddress: address,
                    atmDistrict: districtID,
                    flagreq: flag
                },
                success: function (response)
                {
                    if (response == 'success')
                    {
                        $('#successMsg').html('ATM information updated successfully');
                        setTimeout(location.reload(true), 1000);
                    } else
                    {
                        document.getElementById("successMsg").innerHTML = response;
                    }
                }
            });
        })

        function exportATMLocations()
        {
            window.open("ATMLocation-export", "_self");
        }
    </script>
</body>
</html>