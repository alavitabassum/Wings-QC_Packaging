<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and new_order = 'Y'"));
    if ($userPrivCheckRow['new_order'] != 'Y'){
        exit();
    }
    $merchantresult = mysqli_query($conn,"select tbl_api_client.merchantCode, tbl_merchant_info.merchantName from tbl_api_client left join tbl_merchant_info on tbl_api_client.merchantCode = tbl_merchant_info.merchantCode where tbl_api_client.isActive = 'Y'");
    if ($user_type == 'Merchant'){exit();}

?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Pull Orders</p>
            <div class="col-sm-12">
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12" id="barcodeDiv" hidden>
                        <br>
                        <iframe id="barcodeView" src="" style="width: 100%; height:250px"></iframe>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12">
                        <p id="alrt" style="color: blue; text-align: center"></p>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-4">
                        <label>Select Merchant</label>
                        <select id="merchantCode" style="width: 100%" required>
                            <?php if ($user_type == 'Merchant'){
                                $merchantsql = "select merchantCode, merchantName from tbl_api_client where merchantCode = '$user_code'";
                                $merchantresult = mysqli_query($conn,$merchantsql);
                                $merchantrow = mysqli_fetch_array($merchantresult);
                                echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                            } else {?>
                                <option></option>
                                <?php
                                    foreach ($merchantresult as $merchantrow){
                                        echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" class="btn btn-info" id="btnPull"> Pull Orders</button>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12" id="ordersTable">

                    </div>
                </div>
            </div>
        </div>
        <!-- The Modal for Confirmation -->
        <div id="confirmModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <div class="col-sm-6" style="text-align: left; margin-left: -10px">
                        <input type="radio" name="acceptOption" value="picked" checked>Picked
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input type="radio" name="acceptOption" value="paused">Pause
                    </div>
                    <br>
                    <input type="text" id="comments" placeholder="Comments (if any)" class="form-control input-sm"> 
                    <br>    
                    <button id="btnOK" class="btn btn-warning">Yes</button>
                    <button id="btnCancel" class="btn btn-default">No</button>
                    <input type="hidden" id="apiOrderID">
                </div>
            </div>
        </div>
        <!-- The Modal for Confirmation -->
        <div id="removeModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <div class="col-sm-6" style="text-align: left; margin-left: -10px">
                        <input type="radio" name="removeOption" value="remove" checked>Unable to pick
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input type="radio" name="removeOption" value="other">Other reason
                    </div>
                    <br>
                    <input type="text" id="reason" placeholder="Comments (if any)" class="form-control input-sm"> 
                    <br>    
                    <button id="btnRemove" class="btn btn-warning">Yes</button>
                    <button id="btnRemoveCancel" class="btn btn-default">No</button>
                    <input type="hidden" id="apiRemoveID">
                </div>
            </div>
        </div>
        <!-- The Modal for Confirmation -->
        <div id="notifyModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <div class="col-sm-6" style="text-align: left; margin-left: -10px">
                        <input type="radio" name="notifyOption" value="notify" checked>Unable to pick
                    </div>
                    <div class="col-sm-6" style="text-align: left">
                        <input type="radio" name="notifyOption" value="otherNotify">Other reason
                    </div>
                    <br>
                    <input type="text" id="reasonNotify" placeholder="Comments (if any)" class="form-control input-sm"> 
                    <br>    
                    <button id="btnNotify" class="btn btn-warning">Yes</button>
                    <button id="btnNotifyCancel" class="btn btn-default">No</button>
                    <input type="hidden" id="apiNotifyID">
                </div>
            </div>
        </div>
        <script>
            $('select').select2();
            function exportOrders()
            {
                var merchantCode = $('#merchantCode').val();
                if (!merchantCode)
                {
                    $('#alrt').css('color', 'red');
                    $('#alrt').html('Select merchant......');
                } else
                {
                    window.open("other-merchant-order-export?xxCode=" + merchantCode, "_blank");
                }
            }
            $('#btnPull').click(function ()
            {
                $('#alrt').css('color', 'blue');
                $('#alrt').html('Please wait......');
                var merchantCode = $('#merchantCode').val();
                if (!merchantCode)
                {
                    $('#alrt').css('color', 'red');
                    $('#alrt').html('Select merchant......');
                } else
                {
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: merchantCode,
                            flagreq: 'pullMerchantOrders'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#ordersTable').html('');
                                $('#ordersTable').html(response);
                                $('#alrt').html('');
                                $('#orderPullTable').DataTable();
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                                $('#ordersTable').html('');
                            }
                        }
                    })
                }
            })
            function acceptAllOrders()
            {
                $('#alrt').css('color', 'blue');
                $('#alrt').html('Please wait......');
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: 'N',
                        flagreq: 'acceptAllApiOrder'
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#alrt').html('');
                            $('#alrt').css('color', 'green');
                            $('#alrt').html(response);
                            $('#ordersTable').html('');
                        } else
                        {
                            $('#alrt').css('color', 'red');
                            $('#alrt').html(response);
                        }
                    }
                })
            }
            $('#btnCancel').click(function (evt)
            {
                var confirmModal = document.getElementById('confirmModal');
                confirmModal.style.display = "none";
            })
            $('#btnOK').click(function ()
            {
                var apiOrderID = $('#apiOrderID').val();
                var comments = $('#comments').val();
                if ($("input[name='acceptOption']:checked").val() == 'picked')
                {
                    $('#alrt').css('color', 'blue');
                    $('#alrt').html('Please wait......');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: apiOrderID,
                            comments: comments,
                            flagreq: 'acceptSingleApiOrder'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                //$('#alrt').css('color', 'green');
                                //$('#alrt').html(response);
                                $('#barcodeDiv').attr('hidden', false);
                                $('#barcodeView').attr('src', 'Print-Single-Barcode?orderid=' + response);
                                $('#' + apiOrderID).hide();
                                var confirmModal = document.getElementById('confirmModal');
                                confirmModal.style.display = "none";
                                $('#alrt').html('');
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                            }
                        }
                    })
                } else
                {

                }
            })
            function acceptOrder(inp)
            {
                var confirmModal = document.getElementById('confirmModal');
                var span = document.getElementsByClassName("close")[0];
                confirmModal.style.display = "block";
                span.onclick = function ()
                {
                    confirmModal.style.display = "none";
                }
                $('#apiOrderID').val(inp);
            }
            $('#btnRemoveCancel').click(function (evt)
            {
                var removeModal = document.getElementById('removeModal');
                removeModal.style.display = "none";
            })
            $('#btnRemove').click(function ()
            {
                var apiRemoveID = $('#apiRemoveID').val();
                var reason = $('#reason').val();
                if ($("input[name='removeOption']:checked").val() == 'remove')
                {
                    $('#alrt').css('color', 'blue');
                    $('#alrt').html('Please wait......');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: apiRemoveID,
                            reason: reason,
                            flagreq: 'deleteSingleApiOrder'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#alrt').css('color', 'green');
                                $('#alrt').html(response);
                                $('#' + apiRemoveID).hide();
                                var removeModal = document.getElementById('removeModal');
                                removeModal.style.display = "none";
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                            }
                        }
                    })
                } else
                {
                    $('#alrt').css('color', 'blue');
                    $('#alrt').html('Please wait......');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: apiRemoveID,
                            reason: reason,
                            flagreq: 'deleteSingleApiOrder'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#alrt').css('color', 'green');
                                $('#alrt').html(response);
                                $('#' + apiRemoveID).hide();
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                            }
                        }
                    })
                }
            })
            function removeOrder(inp)
            {
                var removeModal = document.getElementById('removeModal');
                var span = document.getElementsByClassName("close")[1];
                removeModal.style.display = "block";
                span.onclick = function ()
                {
                    removeModal.style.display = "none";
                }
                $('#apiRemoveID').val(inp);
            }
            function notifyOrder(inp)
            {
                var notifyModal = document.getElementById('notifyModal');
                var span = document.getElementsByClassName("close")[2];
                notifyModal.style.display = "block";
                span.onclick = function ()
                {
                    notifyModal.style.display = "none";
                }
                $('#apiNotifyID').val(inp);
            }
            $('#btnNotifyCancel').click(function (evt)
            {
                var notifyModal = document.getElementById('notifyModal');
                notifyModal.style.display = "none";
            })
            $('#btnNotify').click(function ()
            {
                var apiNotifyID = $('#apiNotifyID').val();
                var reason = $('#reasonNotify').val();
                if ($("input[name='notifyOption']:checked").val() == 'notify')
                {
                    $('#alrt').css('color', 'blue');
                    $('#alrt').html('Please wait......');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: apiNotifyID,
                            reason: reason,
                            flagreq: 'notifySingleApiOrder'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#alrt').css('color', 'green');
                                $('#alrt').html(response);
                                //$('#' + apiNotifyID).hide();
                                var notifyModal = document.getElementById('notifyModal');
                                notifyModal.style.display = "none";
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                            }
                        }
                    })
                } else
                {
                    $('#alrt').css('color', 'blue');
                    $('#alrt').html('Please wait......');
                    $.ajax(
                    {
                        type: 'post',
                        url: 'toupdateorders',
                        data:
                        {
                            get_orderid: apiNotifyID,
                            reason: reason,
                            flagreq: 'notifySingleApiOrder'
                        },
                        success: function (response)
                        {
                            var str = response;
                            var n = str.search("Error");
                            if (n < 0)
                            {
                                $('#alrt').css('color', 'green');
                                $('#alrt').html(response);
                                //$('#' + apiRemoveID).hide();
                            } else
                            {
                                $('#alrt').css('color', 'red');
                                $('#alrt').html(response);
                            }
                        }
                    })
                }
            })
        </script>
    </body>
</html>
