<?php
    include('header.php');
    $scanPrivSQL = "select * from tbl_scan_priv where userName = '$user_check'";
    $scanPrivResult = mysqli_query($conn, $scanPrivSQL);
    foreach ($scanPrivResult as $scanPrivRow){
        if($scanPrivRow['privilegeOption'] == 'shuttle'){
            $shuttle = 'Y';
        }
        if($scanPrivRow['privilegeOption'] == 'retcp1'){
            $retcp1 = 'Y';
        }
        if($scanPrivRow['privilegeOption'] == 'dp2'){
            $dp2 = 'Y';
        }
        if($scanPrivRow['privilegeOption'] == 'dp2pick'){
            $dp2pick = 'Y';
        }
    }
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and scanOrders = 'Y'"));
    if ($userPrivCheckRow['scanOrders'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Scan Orders</p>
            <div class="container-fluid" style="margin-left: 15px; font: 15px 'paperfly roman'">
                <div class="row">
                    <?php  if($shuttle == 'Y'){?>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" id="shuttle" name="scanOption" value="shuttle" onclick="optionChecked()"> Shuttle
                        </label>
                    </div>
                    <?php } if($retcp1 == 'Y'){?>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" id="cpreturn" name="scanOption" value="cpreturn" onclick="optionChecked()"> CP Return
                        </label>
                    </div>
                    <?php } if($dp2 == 'Y'){?>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" id="dp2" name="scanOption" value="dp2" onclick="optionChecked()"> DP2
                        </label>
                    </div>
                    <?php } if($dp2pick == 'Y'){?>
                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" id="dp2pick" name="scanOption" value="dp2pick" onclick="optionChecked()"> Pick from DP2
                        </label>
                    </div>
                    <?php }?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                        <input type='text' id='scanInputBox' class="form-control" onchange="scanResult()">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>Scan Count: <span id="scanCount">0</span></p>
                    </div>
                    <div class="col-sm-6"  id="scanResult">
                    </div>
                </div>
                <div class="row" style="position: fixed">
                    <div class="col-sm-6" id="mobileSendingResult">
                    </div>
                </div>
            </div>
    </div>


<script type='text/javascript'>
    $(function(){
        //removed duplicates form the following array
        $(jQuery.unique(
            //create an array with the names of the groups
            $('INPUT:radio')
                .map(function(i,e){
                    return $(e).attr('name') }
                ).get()
        ))
        //interate the array (array with unique names of groups)
        .each(function(i,e){
            //make the first radio-button of each group checked
            $('INPUT:radio[name="'+e+'"]:visible:first')
                .attr('checked','checked');
        });
    });
    function SetFocus()
    {
        // safety check, make sure its a post 1999 browser
        if (!document.getElementById)
        {
            return;
        }

        var scanInputBoxElement = document.getElementById("scanInputBox");

        if (scanInputBoxElement != null)
        {
            scanInputBoxElement.focus();
        }
    }
    function scanResult()
    {
        if ($('#scanInputBox').val().length >= 9)
        {
            var scanedValue = $('#scanInputBox').val();
            if ($("input[name='scanOption']:checked").val() == 'shuttle')
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: scanedValue,
                        flagreq: 'shuttleScan'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + response + "</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanCount').html(parseInt($('#scanCount').html()) + 1);
                            $('#scanInputBox').val('');
                            var orderid = response.substring(0, 17);
                            $.ajax(
                            {
                                type: 'post',
                                url: 'sendMessage',
                                data:
                                {
                                    rootData: scanedValue,
                                    flagreq: 'sendPickupAccept'
                                },
                                success: function (response)
                                {
                                    $('#mobileSendingResult').html('');
                                    data = JSON.parse(response);
                                    for (rst in data)
                                    {
                                        var status = data[rst][0].status;
                                        var mobile = data[rst][0].destination;
                                        if (status == 0)
                                        {
                                            //var prevSendResults = $('#mobileSendingResult').html();
                                            $('#mobileSendingResult').html('');
                                            $('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: green\">" + orderid + ": SMS to " + mobile + " successful</p>");
                                            //$('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: green\">" + prevSendResults + "</p>");
                                            //$.ajax(
                                            //{
                                            //    type: 'post',
                                            //    url: 'sendMessage',
                                            //    data:
                                            //    {
                                            //        rootData: orderid,
                                            //        status: status,
                                            //        phone: mobile,
                                            //        flagreq: 'smsStatus'
                                            //    },
                                            //    success: function (response2)
                                            //    {
                                            //        //var prevSendResults = $('#mobileSendingResult').html();
                                            //        //$('#mobileSendingResult').html('');
                                            //        $('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: orange\">Log recorded successfully</p>");
                                            //        //$('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: green\">" + prevSendResults + "</p>");
                                            //    }
                                            //})
                                        } else
                                        {
                                            //var prevSendResults = $('#mobileSendingResult').html();
                                            $('#mobileSendingResult').html('');
                                            $('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: red\">" + orderid + ": SMS to " + mobile + " failed</p>");
                                            //$('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: green\">" + prevSendResults + "</p>");
                                            //$.ajax(
                                            //{
                                            //    type: 'post',
                                            //    url: 'sendMessage',
                                            //    data:
                                            //    {
                                            //        rootData: orderid,
                                            //        status: status,
                                            //        phone: mobile,
                                            //        flagreq: 'smsStatus'
                                            //    },
                                            //    success: function (response2)
                                            //    {
                                            //        //var prevSendResults = $('#mobileSendingResult').html();
                                            //        //$('#mobileSendingResult').html('');
                                            //        $('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: red\">Failed to log</p>");
                                            //        //$('#mobileSendingResult').append("<p id=\"sendOutput\" style=\"color: green\">" + prevSendResults + "</p>");
                                            //    }
                                            //})
                                        }
                                    }
                                }
                            })
                        } else
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: red\">" + response + " ----shuttle---not ok</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanInputBox').val('');
                        }
                    }
                })
            }
            if ($("input[name='scanOption']:checked").val() == 'cpreturn')
            {

                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: scanedValue,
                        flagreq: 'retCPScan'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + response + "</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanCount').html(parseInt($('#scanCount').html()) + 1);
                            $('#scanInputBox').val('');
                        } else
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: red\">" + response + " ----CP Return---not ok</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanInputBox').val('');
                        }
                    }
                })
            }
            if ($("input[name='scanOption']:checked").val() == 'dp2')
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: scanedValue,
                        flagreq: 'dp2Scan'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + response + "</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanCount').html(parseInt($('#scanCount').html()) + 1);
                            $('#scanInputBox').val('');
                        } else
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: red\">" + response + " ----DP2---not ok</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanInputBox').val('');
                        }
                    }
                })
            }
            if ($("input[name='scanOption']:checked").val() == 'dp2pick')
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: scanedValue,
                        flagreq: 'dp2pickScan'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + response + "</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanCount').html(parseInt($('#scanCount').html()) + 1);
                            $('#scanInputBox').val('');
                        } else
                        {
                            var prevScanedResults = $('#scanResult').html();
                            $('#scanResult').html('');
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: red\">" + response + " ----Pick Product---not ok</p>");
                            $('#scanResult').append("<p id=\"scanOutput\" style=\"color: green\">" + prevScanedResults + "</p>");
                            $('#scanInputBox').val('');
                        }
                    }
                })
            }
        }
    }
    SetFocus();
    function optionChecked()
    {
        SetFocus();
        $('#scanCount').html('0');
        $('#scanResult').html('');
    }
</script>
        
    </body>
</html>
