<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and barcodeOne = 'Y'"));
    if ($userPrivCheckRow['barcodeOne'] != 'Y'){
        exit();
    }
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Barcode Print (Single)</p>
            <div class="col-sm-12">
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12">
                        <p id="alrt" style="color: blue; text-align: center"></p>
                    </div>
                </div>
                <div class="row" style="margin-left: 15px; margin-top: 15px">
                    <div class="col-sm-6">
                        <div class="row" style="margin-top: 15px">
                            <div class="col-sm-12">
                                <label>Please enter Paperfly Order ID or Merchant Order ID and then press enter</label>
                                <input type="text" id="orderID" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px">
                            <div class="col-sm-12" id="orderDetail">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" id="barcodeDiv" hidden>
                        <!--<div class="row" style="margin-top: 15px" >
                            <div class="col-sm-12">-->
                                <br>
                                <iframe id="barcodeView" src="" style="width: 100%; height:450px"></iframe>
                            <!--</div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- <button id="myBtn">Open Modal</button> -->
        <!-- The Modal for Update Records -->
        <div id="updateModal" class="modal" style="width: auto">
          <!-- Modal content -->
          <div class="modal-content modal-dialog" style="width: 520px">
            <div class="modal-header" style="height: 40px; background-color: #16469E">
              <span class="close" onclick=" return fncClose()">&times;</span>
              <h3 style="font: 25px 'paperfly roman'">Order Edit</h3>
            </div>
            <div class="modal-body" style="font: 13px 'paperfly roman'">
                <br>
                <div class="form-group">
                    <label class="col-sm-4 control-label">ORDER ID</label>
                    <div class="col-sm-8">
                        <label id="ordID"></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Merchant Ref.</label>
                    <div class="col-sm-8">
                        <label id="merRef"></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Price</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="price" name="price" value="" style="height: 25px" onkeyup="return isNumberKey(this)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Package Option</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="packageOption" name="productSizeWeight">
                            <option value="standard">Standard</option>
                            <option value="large">Large</option>
                            <option value="special">Special</option>
                            <option value="specialplus">Special Plus</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label class="col-sm-4 control-label">Delivery Option</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="deliveryOption" name="deliveryOption">
                            <option value="regular">Regular</option>
                            <option value="express">Express</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Collection</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="collection" name="collection" value="" style="height: 25px" onkeyup="return isNumberKey(this)">
                    </div>
                </div>
                <div class="form-group">
                <label class="col-sm-4 control-label">District/Sub-District</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="customerDistrict"  name="customerDistrict" onchange="fetch_customerThana(this.value);">
                            <option></option>
                            <?php
                                foreach ($districtresult as $districtrow){
                                    echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>        
                <div class="form-group">
                <label class="col-sm-4 control-label">Customer Thana</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="customerThana" name="customerThana">

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Customer Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="customerPhone" name="customerPhone" value="" style="height: 28px" onkeyup="return isNumberKey(this)">
                    </div>
                </div>
                <div class="form-group" style="text-align: center">                
                    <button id="btnUpCancel" type="button" class="btn btn-default">Cancel</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Save Changes</button>
                    <p id="successMsg" ></p>
                </div>
            </div>
          </div>
        </div>


        <!-- The Modal for message -->
        <div id="messageModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Alert</h3>
                </div>
                <div class="modal-body">
                    <p id="msg2" style="text-align: center; font: 13px 'paperfly roman'"></p>
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
                    <p style="color: 	#00AEEF; font: 20px 'paperfly roman'">Are you sure!!!!!</p>
                    <br>    
                    <button id="btnOK" class="btn btn-warning">Yes</button>
                    <button id="btnCancel" class="btn btn-default">No</button>
                    <p id="successMsgD"></p>
                </div>
            </div>
        </div>

        <!-- The Modal for Confirmation -->
        <div id="infoModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span id="infClose" class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <p id="infoMsg"></p>
                    <br>    
                    <button id="btnInfOK" class="btn btn-info">OK</button>
                </div>
            </div>
        </div>
        <script>
            $('#orderID').keypress(function (e)
            {
                if (e.which == 13)
                {
                    viewBarcode();
                }
            });
            function viewBarcode()
            {
                $('#alrt').css('color', 'blue');
                $('#alrt').html('Please wait......');
                var orderID = $('#orderID').val();
                if (!orderID)
                {
                    $('#alrt').css('color', 'red');
                    $('#alrt').html('Order ID missing');
                    setTimeout(function () { $('#alrt').html(''); }, 3000);
                } else
                {
                    $.ajax(
                            {
                                type: 'post',
                                url: 'toupdateorders',
                                data:
                                {
                                    get_orderid: orderID,
                                    flagreq: 'signleBarcodeID'
                                },
                                success: function (response)
                                {
                                    var str = response;
                                    var n = str.search("Error");
                                    if (n < 0)
                                    {
                                        $('#barcodeDiv').attr('hidden', false);
                                        $('#barcodeView').attr('src', 'Print-Single-Barcode?orderid=' + response);
                                        $('#alrt').html('');
                                        $.ajax(
                                        {
                                            type: 'post',
                                            url: 'toupdateorders',
                                            data:
                                            {
                                                get_orderid: response,
                                                flagreq: 'orderForEdit'
                                            },
                                            success: function (response2)
                                            {
                                                var str = response;
                                                var n = str.search("Error");
                                                if (n < 0)
                                                {
                                                    $('#orderDetail').html('');
                                                    $('#orderDetail').html(response2);
                                                } else
                                                {
                                                    $('#orderDetail').html('');
                                                    $('#orderDetail').html(response2);
                                                }
                                            }
                                        })
                                    } else
                                    {
                                        $('#alrt').css('color', 'red');
                                        $('#alrt').html(response);
                                        setTimeout(function () { $('#alrt').html(''); }, 3000);
                                    }
                                }
                            })
                }
            }

            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function ordEdit(inp)
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data:
                    {
                        get_orderid: inp,
                        flagreq: 'getOrderID'
                    },
                    success: function (ord)
                    {
                        var upmodal = document.getElementById('updateModal');
                        upmodal.style.display = "block";
                        document.getElementById('ordID').innerHTML = '&nbsp;&nbsp;&nbsp;' + ord;
                        document.getElementById('merRef').innerHTML = '&nbsp;&nbsp;&nbsp;' + document.getElementById('merRef' + ord).innerHTML;
                        document.getElementById('price').value = document.getElementById('price' + ord).innerHTML;
                        document.getElementById('packageOption').value = document.getElementById('packOps' + ord).innerHTML;
                        document.getElementById('deliveryOption').value = document.getElementById('deliveryOps' + ord).innerHTML;
                        document.getElementById('collection').value = document.getElementById('collection' + ord).innerHTML;
                        document.getElementById('customerDistrict').value = document.getElementById('customerDistrict' + ord).innerHTML;
                        document.getElementById('customerPhone').value = document.getElementById('customerPhone' + ord).innerHTML;
                        val = document.getElementById('customerDistrict' + ord).innerHTML;
                        $.ajax({
                            type: 'post',
                            url: 'fetch_thana.php',
                            data: {
                                get_thanaid: val
                            },
                            success: function (response)
                            {
                                document.getElementById("customerThana").innerHTML = response;
                                document.getElementById('customerThana').value = document.getElementById('customerThana' + ord).innerHTML;
                            }
                        });
                    }
                })
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

            function ordStatus(ord)
            {
                var flag = 'reset';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: ord,
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        if (response == 'success')
                        {
                            var infoModal = document.getElementById('infoModal');
                            infoModal.style.display = "block";
                            $('#infoMsg').html('Order reset successfull');
                        }
                    }
                });
            }

            $('#infClose').click(function ()
            {
                var infoModal = document.getElementById('infoModal');
                infoModal.style.display = "none";
            })

            $('#btnSave').click(function ()
            {
                var orderID = $('#ordID').text();
                var price = $('#price').val();
                var packageOptions = $('#packageOption').val();
                var deliveryOptions = $('#deliveryOption').val();
                var collections = $('#collection').val();
                var district = $('#customerDistrict').val();
                var thana = $('#customerThana').val();
                var phone = $('#customerPhone').val();
                var flag = 'update';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: orderID.trim(),
                        prices: price,
                        packageOption: packageOptions,
                        deliveryOption: deliveryOptions,
                        collection: collections,
                        districts: district,
                        thanas: thana,
                        phones: phone,
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        if (response == 'success')
                        {
                            $('#successMsg').html('Order updated successfully');
                            setTimeout(location.reload(true), 1000);
                        } else
                        {
                            document.getElementById("successMsg").innerHTML = response;
                        }
                    }
                });

            })

            $('#select_all').click(function (event)
            {
                if (this.checked)
                {
                    // Iterate each checkbox
                    $(':checkbox').each(function ()
                    {
                        this.checked = true;
                    });
                }
                else
                {
                    $(':checkbox').each(function ()
                    {
                        this.checked = false;
                    });
                }
            });

            $('#btnInfOK').click(function ()
            {
                var infoModal = document.getElementById('infoModal');
                infoModal.style.display = "none";
            })

            $('#deleteSelected').click(function (evt)
            {
                var confirmModal = document.getElementById('confirmModal');
                var span = document.getElementsByClassName("close")[2];
                confirmModal.style.display = "block";
                span.onclick = function ()
                {
                    confirmModal.style.display = "none";
                }
                $('#btnCancel').click(function (evt)
                {
                    confirmModal.style.display = "none";
                })
                $('#btnOK').click(function (evt)
                {
                    var delVal = [];
                    $("input[type=checkbox]").each(function ()
                    {
                        if ($(this).is(":checked"))
                        {
                            delVal.push("'" + $(this).val() + "'");
                        }
                    });
                    var flag = 'delete';
                    $.ajax({
                        type: 'post',
                        url: 'toupdateorders',
                        data: {
                            get_orderid: delVal.toString(),
                            flagreq: flag
                        },
                        success: function (response)
                        {
                            if (response == 'success')
                            {
                                $('#successMsgD').html('Order Deleted successfully');
                                setTimeout(location.reload(true), 1000);
                            } else
                            {
                                document.getElementById("successMsgD").innerHTML = response;
                            }
                        }
                    });
                })
            })
            function fetch_customerThana(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'fetch_thana.php',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("customerThana").innerHTML = response;
                    }
                });
            }
        </script>
    </body>
</html>
