<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and revenuePerformance = 'Y'"));
    if ($userPrivCheckRow['revenuePerformance'] != 'Y'){
        exit();
    }


    $merchantResult = mysqli_query($conn, "select * from tbl_merchant_info");
?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Merchant Performance</p>
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
                        <div class="col-sm-2">
                            <br>
                            <button type="button" id="showRevPerformance" style="margin-top: 4px" class="btn btn-info">Invoiced Orders Revenue</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover" id="revenueTable">
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $('select').select2();
            $('#showExecPerformance').click(function ()
            {
                $('#alrt').html('Please wait............');
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data: 
                    {
                        get_orderid: 'na',
                        startDate: startDate,
                        endDate: endDate,
                        flagreq: 'unInvoiceReveune'                                        
                    },
                    success: function(response)
                    {
                        $('#revenueTable').html('');
                        $('#alrt').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) 
                        {
                            $('#revenueTable').html(response);
                        } else 
                        {
                            $('#revenueTable').html(response);
                        }                        
                    }
                })
            })
            $('#showRevPerformance').click(function ()
            {
                $('#alrt').html('Please wait............');
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data: 
                    {
                        get_orderid: 'na',
                        startDate: startDate,
                        endDate: endDate,
                        flagreq: 'InvoiceReveune'                                        
                    },
                    success: function(response)
                    {
                        $('#revenueTable').html('');
                        $('#alrt').html('');
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) 
                        {
                            $('#revenueTable').html(response);
                        } else 
                        {
                            $('#revenueTable').html(response);
                        }                        
                    }
                })
            })
        </script>
    </body>
</html>
