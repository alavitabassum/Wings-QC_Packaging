<?php
    include('header.php');
    $orderSQL = "select tbl_order_details.*, tbl_merchant_info.merchantName from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where close is null";
    $orderResult = mysqli_query($conn, $orderSQL);
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and edit_order = 'Y'"));
    if ($userPrivCheckRow['edit_order'] != 'Y'){
        exit();
    }
?>

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

<div class="container">
    <div class="row" style="font: 11px 'paperfly roman'">
        <?php if($user_check == 'admin' or $user_check =='razib'){?>
        <button id='deleteSelected' class='btn btn-danger' style="margin-top: 75px;  float: right">Delete</button>
        <?php }?>
        <table class="table table-hover" id="bootstrap-table">
            <thead>
            <tr>
                <th><!-- <input id='select_all' type='checkbox' name='allChk' value='1'> --></th>
                <th>Order ID</th>
                <th>Merchant Name</th>
                <th>Merchant Ref</th>
                <th>Price</th>
                <th>Package Option</th>
                <th>Delivery Option</th>
                <th>Collection</th>
                <th>Customer Thana</th>
                <th>Customer Phone</th>
		        <th>Edit</th>
                <th>Reset Status</th>
            </tr>
            </thead>
            <tbody style="font: 11px 'paperfly roman'">
                <?php 
                    foreach($orderResult as $orderRow){
                ?>
                <tr id="tr<?php echo $orderRow['orderid'];?>">
                    <td><?php echo "<input type = 'checkbox' name='chkName' value='".$orderRow['orderid']."'>"; ?></td>
                    <td><?php echo $orderRow['orderid'];?></td>
                    <td><?php echo $orderRow['merchantName'];?></td>
                    <td id="merRef<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['merOrderRef'];?></td>
                    <td id="price<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['packagePrice'];?></td>
                    <td id="packOps<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['productSizeWeight'];?></td>
                    <td id="deliveryOps<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['deliveryOption'];?></td>
                    <td id="collection<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['CashAmt'];?></td>
                    <td id="customerThana<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['customerThana'];?></td>
                    <td id="customerPhone<?php echo $orderRow['orderid'];?>"><?php echo $orderRow['custphone'];?></td>
                    <td><button class="btn btn-primary" onclick="<?php echo "return ordEdit('".$orderRow['orderid']."')"; ?>">Edit</button></td>
                    <td><button class="btn btn-warning" onclick="<?php echo "return ordStatus('".$orderRow['orderid']."')"; ?>" style="float: right">Reset Status</button></td>
                    <td id="customerDistrict<?php echo $orderRow['orderid'];?>" hidden><?php echo $orderRow['customerDistrict'];?></td>
                </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>


<script>

    function isNumberKey(inpt)
    {
        var regex = /[^0-9.]/g;
        inpt.value = inpt.value.replace(regex, "");
    }

    function ordEdit(ord)
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

    $('#infClose').click(function()
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

    $(document).ready(function ()
    {
        $('#bootstrap-table').bdt({
            showSearchForm: 1,
            showEntriesPerPageField: 1
        });

    });

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