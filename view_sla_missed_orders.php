<?php
    include('header.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and slaMissed = 'Y'"));
    if ($userPrivCheckRow['slaMissed'] != 'Y'){
        exit();
    }
    $merchantResult = mysqli_query($conn, "select * from tbl_merchant_info");
?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">5 Days SLA Missed Orders</p>
            <div class="col-sm-12">
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12">
                        <p id="alrt" style="color: blue; text-align: center"></p>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-2">
                        <label>SLA Period</label>
                        <select id="slaPeriod" style="width: 100%">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button id="btnShow" class="btn btn-info"> Show</button>
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
                        <button type="button" id="showMerchantWise" style="margin-top: 4px" class="btn btn-info">Merchant Wise</button>
                    </div>
                    <div class="col-sm-2">
                        <br><br>
                        <input type="checkbox" class="form-check-input" id="shuttleYN">Skip Shuttle Validation
                        <!--<input type="checkbox" id="shuttleYN" class="">Without Shuttle</button>-->
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12" id="slaMissedCount" >

                    </div>
                </div>
            </div>
        </div>
        <script>
            $('select').select2();
            $('#btnShow').click(function ()
            {
                if ($("#shuttleYN").is(':checked'))
                {
                    var slaPeriod = $('#slaPeriod').val();
                    $('#alrt').html('Please wait....');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: slaPeriod,
                            flagreq: 'showSlaMissedOrdersNS'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            } else
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            }
                        }
                    })
                } else
                {
                    var slaPeriod = $('#slaPeriod').val();
                    $('#alrt').html('Please wait....');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: slaPeriod,
                            flagreq: 'showSlaMissedOrders'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            } else
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            }
                        }
                    })
                }
            })
            $('#showMerchantWise').click(function ()
            {
                if ($("#shuttleYN").is(':checked'))
                {
                    var slaPeriod = $('#slaPeriod').val();
                    var merchantCode = $('#merchantCode').val();
                    $('#alrt').html('Please wait....');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: slaPeriod,
                            merchantCode: merchantCode,
                            flagreq: 'showMerchantSlaMissedOrdersSN'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            } else
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            }
                        }
                    }) 
                } else
                {
                    var slaPeriod = $('#slaPeriod').val();
                    var merchantCode = $('#merchantCode').val();
                    $('#alrt').html('Please wait....');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: slaPeriod,
                            merchantCode: merchantCode,
                            flagreq: 'showMerchantSlaMissedOrders'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            } else
                            {
                                $('#slaMissedCount').html('');
                                $('#slaMissedCount').html(response);
                                $('#alrt').html('');
                            }
                        }
                    })                    
                }
            })
        </script>
    </body>
</html>