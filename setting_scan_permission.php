<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userResult = mysqli_query($conn, "SELECT user_id, userName, tbl_employee_info.empName FROM tbl_user_info left join tbl_employee_info on tbl_user_info.merchEmpCode = tbl_employee_info.empCode WHERE userType != 'Administrator' and userType != 'Merchant' and tbl_user_info.isActive = 'Y'");
    $previlegeResult = mysqli_query($conn, "select privilegeID, userName, privilegeOption from tbl_scan_priv where userName != 'admin' order by userName");

    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_usersetting` WHERE user_id = $user_id_chk and permission = 'Y'"));
    if ($userPrivCheckRow['permission'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search User for Permission</p>
        </div>
        <div class="row" style="margin-top: 10px; margin-left: 15px">
            <div class="col-sm-3">
                <label>User Name</label>
                <select id="userName" style="width: 100%">
                    <?php foreach($userResult as $userRow){?>
                    <option value="<?php echo $userRow['userName'];?>"><?php echo $userRow['userName'].' - '.$userRow['empName'];?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Privilege List</label>
                <select id="privilege" style="width: 100%">
                    <option value="shuttle">Shuttle</option>
                    <option value="retcp1">CP Return</option>
                    <option value="dp2">DP2</option>
                    <option value="dp2pick">DP2 Pick</option>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px; margin-left: 15px">
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" id="addPrivilege">Add Privilege</button>
            </div>
            <div class="col-sm-4">
                <p id="alrt"></p>
            </div>
        </div>
        <div class="row" style="margin-top: 20px; margin-left: 15px">
            <div class="col-sm-6" id="privilegeList">
                <table class="table table-hover" id="privilegeTable">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Privilege</th>
                            <th style="text-align: right">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($previlegeResult as $previlegeRow){?>
                        <tr>
                            <td><?php echo $previlegeRow['userName'];?></td>
                            <td><?php echo $previlegeRow['privilegeOption'];?></td>
                            <td style="text-align: right"><button type="button" class="btn btn-warning" onclick="removePrivilege(<?php echo $previlegeRow['privilegeID'];?>)">Remove</button> </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
        <script type="text/javascript" charset="utf-8">
            $(window).load(function ()
            {
                $('#userName').select2();
                $('#privilege').select2();
            });
            $('#privilegeTable').bdt({
                showSearchForm: 1,
                showEntriesPerPageField: 1

            });
            $('#addPrivilege').click(function ()
            {
                var userName = $('#userName').val();
                var privilege = $('#privilege').val();

                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: userName,
                        privilege: privilege,
                        flagreq: 'addPrivilege'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#privilegeList').html('');
                            $('#privilegeList').append(response);
                            $('#privilegeTable').bdt({
                                showSearchForm: 1,
                                showEntriesPerPageField: 1

                            });
                        } else
                        {
                            $('#privilegeList').html('');
                            $('#privilegeList').append(response);
                        }
                    }
                })
            })
            function removePrivilege(inp)
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: inp,
                        flagreq: 'removePrivilege'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#privilegeList').html('');
                            $('#privilegeList').append(response);
                            $('#privilegeTable').bdt({
                                showSearchForm: 1,
                                showEntriesPerPageField: 1

                            });
                        } else
                        {
                            $('#privilegeList').html('');
                            $('#privilegeList').append(response);
                        }
                    }
                })
            }
        </script>
    </body>
</html>
