<?php
    include('header.php');
    $pointManagerResult = mysqli_query($conn, "select * from tbl_employee_info where desigid = 6 and isActive ='Y'");
    $merchantResult = mysqli_query($conn, "select * from tbl_merchant_info");
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and delivery_performance = 'Y'"));
    if ($userPrivCheckRow['delivery_performance'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Daily point and officers performance</p>
            <div class="col-sm-12">
                <form name="frmDate" action="" method="post" style="margin: 15px">
                    <div class="row">
                        <div class="col-sm-12">
                            <p id="alrt" style="color: blue; text-align: center"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Start Date</label>
                            <input type="text" id="startDate" class="form-control input-sm" value="<?php echo date('d-m-Y');?>" onfocus="displayCalendar(document.frmDate.startDate,'dd-mm-yyyy',this, false)">
                        </div>
                        <div class="col-sm-2">
                            <label>End Date</label>
                            <input type="text" id="endDate" class="form-control input-sm" value="<?php echo date('d-m-Y');?>" onfocus="displayCalendar(document.frmDate.endDate,'dd-mm-yyyy',this, false)">
                        </div>
                        <div class="col-sm-2">
                            <br>
                            <button type="button" id="showExecPerformance" style="margin-top: 4px" class="btn btn-info">Show Performance</button>
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <label>Merchant</label>
                            <select id="merchantCode" data-placeholder="Select Merchant...." style="width: 100%">
                                <?php foreach($merchantResult as $merchantRow){?>
                                <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <br>
                            <button type="button" id="showMerchantPerformance" style="margin-top: 4px" class="btn btn-info">Show Merchat-Wise</button>
                        </div>
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
        <script>
            $('select').select2();
            $('#showExecPerformance').click(function () {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                $('#alrt').html('Please wait....');

                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: 'na',
                        startDate: startDate,
                        endDate: endDate,
                        flagreq: 'orderDeliverPerf'
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
            $('#showMerchantPerformance').click(function () {
                var merchantCode = $('#merchantCode').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                $('#alrt').html('Please wait....');

                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: 'na',
                        merchantCode: merchantCode,
                        startDate: startDate,
                        endDate: endDate,
                        flagreq: 'orderDeliverPerfMerchant'
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
