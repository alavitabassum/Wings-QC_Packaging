<?php
    include('header.php');
    $pointManagerResult = mysqli_query($conn, "select * from tbl_employee_info where desigid = 6 and isActive ='Y'");
    $merchantResult = mysqli_query($conn, "select * from tbl_merchant_info");
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and point_executive = 'Y'"));
    if ($userPrivCheckRow['point_executive'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Daily Delivery Tracker</p>
            <div class="col-sm-12">
                <form name="frmDate" action="" method="post" style="margin: 15px">
                    <div class="row">
                        <div class="col-sm-12">
                            <p id="alrt" style="color: blue; text-align: center"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Point Manager</label>
                            <select id="pointManager" data-placeholder="Select point manager...." style="width: 100%">
                                <option value="0">All</option>
                                <?php foreach($pointManagerResult as $pointManagerRow){?>
                                <option value="<?php echo $pointManagerRow['empCode'];?>"><?php echo $pointManagerRow['empCode'].'-'.$pointManagerRow['empName'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    
                        <div class="col-sm-2">
                            <label>DP2 Pick Date</label>
                            <input type="text" id="startDate" class="form-control input-sm" value="<?php echo date('d-m-Y');?>" onfocus="displayCalendar(document.frmDate.startDate,'dd-mm-yyyy',this, false)">
                        </div>
                        <!--<div class="col-sm-2">
                            <label>To</label>
                            <input type="text" id="endDate" class="form-control input-sm" value="<?php echo date('d-m-Y');?>" onfocus="displayCalendar(document.frmDate.endDate,'dd-mm-yyyy',this, false)">
                        </div>-->
                    
                        <div class="col-sm-1">
                            <br>
                            <button type="button" id="showPerform" style="margin-top: 4px" class="btn btn-info">Show</button>
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <label>Merchant</label>
                            <select id="merchantCode" data-placeholder="Select Merchant...." style="width: 100%">
                                <?php foreach($merchantResult as $merchantRow){?>
                                <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <br>
                            <button type="button" id="showMerchantWise" style="margin-top: 4px" class="btn btn-info">Merchant Wise</button>
                        </div>
                        <!--<div class="col-sm-1" style="text-align: right">
                            <label>SLA Year</label>
                            <select id="slaYear">
                                <option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
                                <option value="<?php echo date('Y', strtotime('-1year'));?>"><?php echo date('Y', strtotime('-1year'));?></option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <label>SLA Month</label>
                            <select id="slaMonth" style="width: 100%">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <br>
                            <button type="button" id="showMerchantWiseSLA" style="margin-top: 4px" class="btn btn-info">Merch. Wise</button>
                        </div>
                        <div class="col-sm-1">
                            <br>
                            <button type="button" id="showAllOrdersSLA" style="margin-top: 4px" class="btn btn-info">All Orders</button>
                        </div>-->
                    </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-hover" id="pointTable">
                                </table>
                            </div>
                        </div>
                </form>
            </div> 
        </div>
        <div id="tableRowShowHide">
        </div>
        <script>
            $('select').select2();
            $('#showAllOrdersSLA').click(function () {
                var slaYear = $('#slaYear').val();
                var slaMonth = $('#slaMonth').val();
                $('#alrt').html('Please wait....');
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'na',
                        slaYear: slaYear,
                        slaMonth: slaMonth,
                        flagreq: 'overAllSLA'
                    },
                    success: function (response) {
                        $('#pointTable').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        } else {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        }
                    }
                })
            })
            $('#showMerchantWiseSLA').click(function () {
                var slaYear = $('#slaYear').val();
                var slaMonth = $('#slaMonth').val();
                var merchantCode = $('#merchantCode').val();
                $('#alrt').html('Please wait....');
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'na',
                        slaYear: slaYear,
                        slaMonth: slaMonth,
                        merchantCode: merchantCode,
                        flagreq: 'merchantWiseSLA'
                    },
                    success: function (response) {
                        $('#pointTable').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        } else {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        }
                    }
                })
            })
            $('#showPerform').click(function () {
                var pointManager = $('#pointManager').val();
                var startDate = $('#startDate').val();
                //var endDate = $('#endDate').val();
                $('#alrt').html('Please wait....');
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: 'na',
                        pointManager: pointManager,
                        startDate: startDate,
                        endDate: startDate,
                        flagreq: 'pointManagerPerf'
                    },
                    success: function (response) {
                        $('#pointTable').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        } else {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        }
                    }
                })
            })
            $('#showMerchantWise').click(function () {
                var pointManager = $('#pointManager').val();
                var merchant = $('#merchantCode').val();
                var startDate = $('#startDate').val();
                //var endDate = $('#endDate').val();
                $('#alrt').html('Please wait....');
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: 'na',
                        pointManager: pointManager,
                        merchant: merchant,
                        startDate: startDate,
                        endDate: startDate,
                        flagreq: 'pointManagerPerfMerchant'
                    },
                    success: function (response) {
                        $('#pointTable').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        } else {
                            $('#pointTable').html(response);
                            $('#alrt').html('');
                        }
                    }
                })
            })
            function regionDetail(inp) {
                $('.' + inp).toggle("slow", "swing");
            }
        </script>        
    </body>
</html>
