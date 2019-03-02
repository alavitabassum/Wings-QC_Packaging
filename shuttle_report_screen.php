<?php
    include('header.php');
    $pointSQL = "select pointCode, pointName from tbl_point_info order by pointCode";
    $pointResult = mysqli_query($conn, $pointSQL);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and shuttleReport = 'Y'"));
    if ($userPrivCheckRow['shuttleReport'] != 'Y'){
        exit();
    }
?>
    <body>
        <div style="clear: both; margin-left: 2%">
            <p style="background-color: #16469E; border-radius: 5px; width: 99%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Shuttle Report</p>
            <div class="container-fluid">
                <div class="row">
                    <?php
                        $lasShuttleTimeSQL = "SELECT DATE_FORMAT(max(`ShtlTime`), '%d-%M-%Y %H:%i') as maxShuttleTime FROM `tbl_order_details`";
                        $lasShuttleTimeResult = mysqli_query($conn, $lasShuttleTimeSQL);
                        $lasShuttleTimeRow = mysqli_fetch_array($lasShuttleTimeResult);
                    ?>
                    <div class="col-sm-6">
                        <br>
                        <label for="lastShuttleTime" style="font-size: 110%">Last Shuttle Time : <span style="color: blue; font-weight: 500"><?php echo $lasShuttleTimeRow['maxShuttleTime'];?></span></label>
                        <br>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-sm-2">
                        <form name="frmDate" action="" method="post">
                            <label for="orderDate">Enter Shuttle Date</label>
                            <input type="text" id="orderDate" class="form-control" value="<?php echo date('d-m-Y H:i');?>" onfocus="displayCalendar(document.frmDate.orderDate,'dd-mm-yyyy hh:ii',this, true)">
                        </form>
                    </div>
                    <div class="col-sm-2">
                        <form name="frmDate2" action="" method="post">
                            <label for="orderDate2">Enter Shuttle End Time</label>
                            <input type="text" id="shuttleEndTime" class="form-control" value="<?php echo date('d-m-Y H:i');?>" onfocus="displayCalendar(document.frmDate2.shuttleEndTime,'dd-mm-yyyy hh:ii',this, true)">
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <label for="pointCode">Select Point</label>
                        <select id="pointCode" data-placeholder="Select point ............" style="width: 100%">
                            <option></option>
                            <?php foreach($pointResult as $pointRow){?>
                            <option value="<?php echo $pointRow['pointCode'];?>"><?php echo $pointRow['pointCode'].' - '.$pointRow['pointName'];?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <button id="showReport" class="btn btn-info" onclick="shuttleRpt()">Show Shuttle Report</button>
                    </div>
                    <div class="col-sm-2">
                        <button id="showMerchantCount" class="btn btn-info" onclick="merchantCount()">Merchant-wise Count</button>
                    </div>
                    <div class="col-sm-2">
                        <button id="showPointCount" class="btn btn-info" onclick="pointCount()">Point-wise Count</button>
                    </div>
                    <div class="col-sm-2">
                        <button id="PointWiseDetail" class="btn btn-info" onclick="pointDetail()">Point-wise Detail</button>
                    </div>
                    <div class="col-sm-2">
                        <button id="PointWiseExport" class="btn btn-info" onclick="exportDetail()">Export Detail</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <table id="merchantCount" class="table table-striped" style="margin-top: 50px">
                        </table>
                    </div>
                    <div class="col-sm-4">
                        <table id="pointCount" class="table table-striped" style="margin-top: 50px">
                        </table>
                    </div>
                </div>
            </div>
            <div id="dialog" title="Alert" style="text-align: center">
            </div>
        </div>
        <script>
            $(window).load(function ()
            {
                $('#pointCode').select2();
            });
            function commonAlert(v)
            {
                $('#dialog').html('');
                $("#dialog").append("<p id=\"dialogAlert\"></p>");
                $('#dialogAlert').html(v);
                $("#dialog").append("<button type=\"button\" id=\"btnOK\" class=\"btn btn-default\" onclick=\"btnGenOK()\">&nbsp;OK&nbsp;&nbsp;</button>");
                $("#dialog").dialog({ modal: true });
            }
            function btnGenOK()
            {
                $('#dialog').dialog("close");
            }
            function shuttleRpt()
            {
                var shuttleDate = document.getElementById("orderDate").value;
                var shuttleEndTime = document.getElementById("shuttleEndTime").value;
                var pointCode = document.getElementById("pointCode").value;
                if (shuttleDate == '' || pointCode == '' || shuttleEndTime =='')
                {
                    commonAlert("Please enter shuttle date and Point Code");
                } else
                {
                    window.open("Shuttle-Point-Report?xxCode=" + shuttleDate + "&pointCode=" + pointCode + "&shuttleEndTime=" + shuttleEndTime, "_blank");
                }
            }
            function exportDetail(){
                var shuttleDate = document.getElementById("orderDate").value;
                var shuttleEndTime = document.getElementById("shuttleEndTime").value;
                if (shuttleDate == '' || shuttleEndTime =='')
                {
                    commonAlert("Please enter shuttle date");
                } else
                {
                    window.open("Export-Shuttle?xxCode=" + shuttleDate + "&shuttleEndTime=" + shuttleEndTime, "_blank");
                }                
            }
            function pointDetail()
            {
                var shuttleDate = document.getElementById("orderDate").value;
                var shuttleEndTime = document.getElementById("shuttleEndTime").value;
                if (shuttleDate == '' || shuttleEndTime =='')
                {
                    commonAlert("Please enter shuttle date");
                } else
                {
                    window.open("Detail-Shuttle?xxCode=" + shuttleDate + "&shuttleEndTime=" + shuttleEndTime, "_blank");
                }
            }
            function merchantCount()
            {
                var shuttleDate = document.getElementById("orderDate").value;
                var shuttleEndTime = document.getElementById("shuttleEndTime").value;
                if (shuttleDate == '' || shuttleEndTime == '')
                {
                    commonAlert("Please enter shuttle date");
                } else
                {
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: shuttleDate,
                            shuttleEndTime: shuttleEndTime,
                            flagreq: 'merchantWiseShuttle'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#merchantCount').html('');
                                $('#merchantCount').append(response);
                            } else
                            {
                                $('#merchantCount').html('');
                                $('#merchantCount').append(response);
                            }
                        }
                    })
                }
            }
            function pointCount()
            {
                var shuttleDate = document.getElementById("orderDate").value;
                var shuttleEndTime = document.getElementById("shuttleEndTime").value;
                if (shuttleDate == '' || shuttleEndTime == '')
                {
                    commonAlert("Please enter shuttle date");
                } else
                {
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: shuttleDate,
                            shuttleEndTime: shuttleEndTime,
                            flagreq: 'pointWiseShuttle'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#pointCount').html('');
                                $('#pointCount').append(response);
                            } else
                            {
                                $('#pointCount').html('');
                                $('#pointCount').append(response);
                            }
                        }
                    })
                }
            }
        </script>
    </body>
</html>
