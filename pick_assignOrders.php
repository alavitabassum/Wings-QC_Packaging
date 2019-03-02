<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $pickAssignSQL = "SELECT tbl_order_details.merchantCode, tbl_merchant_info.merchantName, count(1) as cnt FROM `tbl_order_details` left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE pickPointEmp is null and close is null group by tbl_order_details.merchantCode";
    $pickAssignResult = mysqli_query($conn, $pickAssignSQL);
    $pickEmpSQL = "Select empCode, empName from tbl_employee_info where desigid in (5,6,8,9,10) and isActive = 'Y'";
    $pickEmpResult = mysqli_query($conn, $pickEmpSQL);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and assign_order = 'Y'"));
    if ($userPrivCheckRow['assign_order'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Pick Assignment in Batch</p>
            <p id="infoMsg" style="color: #ff6a00; font: 17px 'paperfly roman'"></p>
            <table class="table table-hover" id="pickAssign-table">
                <thead style="font: 18px 'paperfly roman'">
                    <tr>
                        <th>Merchant Name</th>
                        <th>Order Count</th>
                        <th>Executive List</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pickAssignResult as $pickAssignRow){ $merchantPoint = $pickAssignRow['merchantCode'];?>
                    <tr>
                        <td><?php echo $pickAssignRow['merchantName']?></td>
                        <td><?php echo $pickAssignRow['cnt']?></td>
                        <td>
                            <?php
                                $pickEmpSQL = "select empCode, empName from tbl_employee_info where empCode in ( SELECT empCode FROM `tbl_employee_point` WHERE pointCode= (select pointCode from tbl_merchant_info where merchantCode = '$merchantPoint')) and tbl_employee_info.isActive = 'Y'";
                                $pickEmpResult = mysqli_query($conn, $pickEmpSQL);
                            ?>
                            <select id="mer<?php echo $pickAssignRow['merchantCode'];?>" name="selectEmp" style="width: 300px">
                                <?php foreach($pickEmpResult as $pickEmpRow){?>
                                <option value="<?php echo $pickEmpRow['empCode'];?>"><?php echo $pickEmpRow['empName'];?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td><button class="btn btn-primary" onclick="<?php echo "return ordAssign('".$pickAssignRow['merchantCode']."')"; ?>">Assign</button></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
      
    <script type="text/javascript">
    $(document).ready(function ()
    {
        $('#pickAssign-table').bdt({
            showSearchForm: 1,
            showEntriesPerPageField: 1
        });

    });
    $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
        $('select[name=selectEmp]').select2();
    });

    function ordAssign(ord)
    {
        var flag = 'pickassign';
        var merCode = $('#mer'+ord).val();
        //alert(flag+'||'+merCode);
        $.ajax({
            type: 'post',
            url: 'toupdateorders',
            data: {
                get_orderid: ord,
                employee: merCode,
                flagreq: flag
            },
            success: function (response)
            {
                if (response == 'success')
                {
                    $('#infoMsg').html('Executive assigned successfull');
                    setTimeout(location.reload(true), 1000);
                } else 
                {
                    $('#infoMsg').html('Unable to assign executive');
                }
            }
        });
    }
    </script>
    </body>
</html>
