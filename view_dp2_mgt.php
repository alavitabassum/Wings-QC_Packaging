<?php
    include('session.php');
    include('header.php');
    include('config.php');
    if(isset($_POST['point'])){
        $pointCode = $_POST['pointList'];
        $dp2OrdersSQL = "select orderid, merOrderRef, dropPointCode, packagePrice, CashAmt, Cash, Ret, partial from tbl_order_details where (Cash = 'Y' or Ret = 'Y' or partial = 'Y') and DropDP2 is null and close is null and dropPointCode = '$pointCode' order by orderDate asc";        
    } else {
        $dp2OrdersSQL = "select orderid, merOrderRef, dropPointCode, packagePrice, CashAmt, Cash, Ret, partial from tbl_order_details where (Cash = 'Y' or Ret = 'Y' or partial = 'Y') and DropDP2 is null and close is null and dropPointCode in (select pointCode from tbl_employee_point where empCode = '$user_code') order by dropPointCode asc, orderDate asc";    
    }
    $dp2OrdersResult = mysqli_query($conn, $dp2OrdersSQL);
    $cashCount = 0;
    $returnCount = 0;
    $partialCount = 0;
    $cashTotal = 0;
    $partialTotal = 0;

    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and DP2_mgt = 'Y'"));
    if ($userPrivCheckRow['DP2_mgt'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">DP2 Management</p>
            <br>
            <div class="container-fluid">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">Order Summary</div>
                            <div class="panel-body">
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">Cash :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="cashCount" style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">Cash Total:</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="collectionAmount" style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">Return :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="returnCount" style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">Partial :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="partialCount" style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">Partial Total:</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="partialAmount" style="color: black; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Selected Orders Summary</div>
                            <div class="panel-body">
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px;color: #07889B; width: 100%; height: 25px; font: 15px 'paperfly roman'">Cash :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="selectedCashCount" style="color: black; color: #07889B; border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: black; border-radius: 5px; color: #07889B; width: 100%; height: 25px; font: 15px 'paperfly roman'">Cash Total:</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="selectedCollectionAmount" style="color: black; color: #07889B;border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px; width: 100%; color: #07889B;height: 25px; font: 15px 'paperfly roman'">Return :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="selectedReturnCount" style="color: black; border-radius: 5px;color: #07889B; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-left: 10px">
                                    <div class="col-sm-2">
                                        <p style="color: black; border-radius: 5px; color: #07889B;width: 100%; height: 25px; font: 15px 'paperfly roman'">Partial :</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="selectedPartialCount" style="color: black; border-radius: 5px; color: #07889B;width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p style="color: black; border-radius: 5px; color: #07889B;width: 100%; height: 25px; font: 15px 'paperfly roman'">Partial Total:</p>
                                    </div>
                                    <div class="col-sm-1">
                                        <p id="selectedPartialAmount" style="color: black; color: #07889B;border-radius: 5px; width: 100%; height: 25px; font: 15px 'paperfly roman'">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <hr>
            <div class="row" style="margin: 0px">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row" style="margin: 0px">
                                <div class="col-sm-1" style="min-width: 140px">
                                    <label for="depositedBy">Deposited By</label>
                                </div>
                                <div class="col-sm-2">
                                    <?php
                                        $employeeListSQL = "SELECT empid, empCode, empName FROM `tbl_employee_info` WHERE isActive = 'Y' and empCode in (select empCode from tbl_employee_point where pointCode in (select pointCode from tbl_employee_point where empCode = '$user_code'))";
                                        $employeeListResult = mysqli_query($conn, $employeeListSQL);
                                    ?>
                                    <select id="employeeList" style="width: 100%">
                                        <?php foreach($employeeListResult as $employeeListRow){?>
                                        <option value="<?php echo $employeeListRow['empCode'];?>" <?php if($employeeListRow['empCode'] == $user_code){echo 'selected';}?>><?php echo $employeeListRow['empName'].' - '.$employeeListRow['empCode'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-sm-1" style="min-width: 180px">
                                    <label for="depositDate" style="text-align: right">Deposited Date</label>
                                </div>
                                <div class="col-sm-1">
                                    <form name="frmDep" action="">
                                        <input type="text" id="depositDate" name="depositDate" class="form-control" value="<?php echo date('d-m-Y');?>" onfocus="displayCalendar(document.frmDep.depositDate,'dd-mm-yyyy',this)" required>
                                    </form>
                                </div>
                                <div class="col-sm-1" style="min-width: 180px">
                                    <label for="depositedBy" style="text-align: right">Deposited Slip Number</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="depositSlip" class="form-control">
                                </div>
                                <div class="col-sm-1">
                                    <?php
                                        $batchNoSQL = "select max(dropDP2Batch) as dropDP2Batch from tbl_order_details where DropDP2By = '$user_check'";
                                        if(!mysqli_query($conn, $batchNoSQL)){
                                            echo "Error : unable generate batch no".mysqli_error($conn);
                                            exit;
                                        } else {
                                            $batchNoResult = mysqli_query($conn, $batchNoSQL);
                                            $batchNoRow = mysqli_fetch_array($batchNoResult);
                                            $batchNo = $batchNoRow['dropDP2Batch'];
                                        }                                        
                                    ?>
                                    <span class="label label-default" style="height: 28px; padding: 6px">Last Batch No: <?php echo $batchNo;?></span>
                                </div>
                            </div>
                            <div class="row" style="margin: 0px">
                                <div class="col-sm-1" style="min-width: 140px">
                                    <label for="depositComment">Deposit Comment</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="depositComment" class="form-control">
                                </div>
                                <form action="" method="post">
                                    <div class="col-sm-1">
                                        <?php 
                                            $pointListSQL = "select pointCode from tbl_employee_point where empCode = '$user_code' order by pointCode";
                                            $pointListResult = mysqli_query($conn, $pointListSQL);
                                        ?>
                                        <select id="pointList" name="pointList" class="form-control">
                                            <?php foreach($pointListResult as $pointListRow){?>
                                            <?php if($pointListRow['pointCode'] == $pointCode){?>
                                            <option value="<?php echo $pointListRow['pointCode']?>" selected><?php echo $pointListRow['pointCode'];?></option>
                                            <?php } else {?>
                                            <option value="<?php echo $pointListRow['pointCode']?>"><?php echo $pointListRow['pointCode'];?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="submit" class="btn btn-info btn-block" name="point" value="Show">
                                    </div>
                                    <div class="col-sm-2" style="text-align: right">
                                        <button type="button" id="btnSubmit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
            <input type="hidden" id="cashSelectedOrders">
            <input type="hidden" id="returnSelectedOrders">
            <input type="hidden" id="partialSelectedOrders">
            <table class="table" id="dp2ListTable">
                <thead>
                    <tr style="background-color: #16469E; color: white">
                        <th>Order ID</th>
                        <th>Merchant Ref</th>
                        <th>Package Price</th>
                        <th>Collection Amount</th>
                        <th>Cash</th>
                        <th>Return</th>
                        <th>Partial</th>
                        <th>Comments</th>
                        <th style="text-align: center">Reset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dp2OrdersResult as $dp2OrdersRow){?>
                    <tr id="tr<?php echo $dp2OrdersRow['orderid'];?>">
                        <td><?php echo $dp2OrdersRow['orderid'];?></td>
                        <td><?php echo $dp2OrdersRow['merOrderRef'];?></td>
                        <td><?php echo $dp2OrdersRow['packagePrice'];?></td>
                        <td><?php if($dp2OrdersRow['Ret'] != 'Y'){?><input type="text" id="cashAmt<?php echo $dp2OrdersRow['orderid'];?>" class="form-control" value="<?php echo $dp2OrdersRow['CashAmt'];?>" onchange="updateCash()" oninput="this.onchange();"><?php }?></td>
                        <td style="background-color: #4CAF50; text-align: center"><?php if($dp2OrdersRow['Cash'] == 'Y'){?><input type="checkbox" id="cashChk<?php echo $dp2OrdersRow['orderid'];?>" name="cashChk" value="<?php echo $dp2OrdersRow['orderid'];?>"><?php $cashCount++; $cashTotal = $cashTotal + $dp2OrdersRow['CashAmt'];}?></td>
                        <td style="background-color: #F44336; text-align: center"><?php if($dp2OrdersRow['Ret'] == 'Y'){?><input type="checkbox" id="retChk<?php echo $dp2OrdersRow['orderid'];?>" name="retChk" value="<?php echo $dp2OrdersRow['orderid'];?>"><?php $returnCount++; }?></td>
                        <td style="background-color: #FF9800; text-align: center"><?php if($dp2OrdersRow['partial'] == 'Y'){?><input type="checkbox" id="partialChk<?php echo $dp2OrdersRow['orderid'];?>" name="partialChk" value="<?php echo $dp2OrdersRow['orderid'];?>"><?php $partialCount++; $partialTotal = $partialTotal + $dp2OrdersRow['CashAmt'];}?></td>
                        <td><input id="comments<?php echo $dp2OrdersRow['orderid']?>" type="text" class="form-control" onchange="updateCash()" oninput="this.onchange();"></td>
                        <td style="text-align: center"><button id="reset<?php echo $dp2OrdersRow['orderid'];?>" class="btn btn-warning" onclick="resetOrders('<?php echo $dp2OrdersRow['orderid'];?>')">Reset</button></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
                echo '<script type="text/javascript" charset="utf-8">';
                    echo '$("#cashCount").html("'.$cashCount.'");';
                    echo '$("#returnCount").html("'.$returnCount.'");';
                    echo '$("#partialCount").html("'.$partialCount.'");';
                    echo '$("#collectionAmount").html("'.$cashTotal.'");';
                    echo '$("#partialAmount").html("'.$partialTotal.'");';
                echo '</script>';
            ?>
        </div>
        <div id="dialog" title="Alert" style="text-align: center">
            <p id="dialogAlert"></p>
        </div>
        <script type="text/javascript" charset="utf-8">
            $(window).load(function ()
            {
                $('#employeeList').select2();
            });
            $(document).ready(function ()
            {
                $('#dp2ListTable').bdt({
                    showSearchForm: 1,
                    showEntriesPerPageField: 1
                });

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
            function resetOrders(ord)
            {
                $.ajax(
                         {
                             type: 'post',
                             url: 'toupdateorders',
                             data:
                             {
                                 get_orderid: ord,
                                 flagreq: 'orderStatusReset'
                             },
                             success: function (response)
                             {
                                 var str = response;
                                 var n = str.search("Error");
                                 if (n < 0)
                                 {
                                     commonAlert('Order Reset Successfull');

                                 } else
                                 {
                                     commonAlert(response);
                                 }
                             }
                         })
            }
            $('#btnSubmit').click(function ()
            {
                if (parseInt($('#selectedCashCount').html()) == 0 && parseInt($('#selectedReturnCount').html()) == 0 && parseInt($('#selectedPartialCount').html()) == 0)
                {
                    commonAlert('There is no order selected!!');
                } else
                {
                    if (parseInt($('#selectedCollectionAmount').html()) >= 0 || parseInt($('#selectedPartialAmount').html()) >= 0)
                    {
                        if ($('#depositSlip').val() == '')
                        {
                            commonAlert('Deposit Slip Number Require');
                        } else
                        {
                            var cashOrders = $('#cashSelectedOrders').val();
                            var partialOrders = $('#partialSelectedOrders').val();
                            var depositSlip = $('#depositSlip').val();
                            var depositedBy = $('#employeeList').val();
                            var depositDate = $('#depositDate').val();
                            var depositComment = $('#depositComment').val();
                            $.ajax(
                            {
                                type: 'post',
                                url: 'toupdateorders',
                                data:
                                {
                                    get_orderid: 'na',
                                    cashOrders: cashOrders,
                                    partialOrders: partialOrders,
                                    depositSlip: depositSlip,
                                    depositedBy: depositedBy,
                                    depositDate: depositDate,
                                    depositComment: depositComment,
                                    flagreq: 'dp2-cash-partial'
                                },
                                success: function (response)
                                {
                                    var str = response;
                                    var n = str.search("Error");
                                    if (n < 0)
                                    {
                                        commonAlert('DP2 Successfull');
                                        window.location.href = window.location.href;

                                    } else
                                    {
                                        commonAlert(response);
                                    }
                                }
                            })
                        }
                        var returnOrders = $('#returnSelectedOrders').val();
                        $.ajax(
                        {
                            type: 'post',
                            url: 'toupdateorders',
                            data:
                            {
                                get_orderid: 'na',
                                returnOrders: returnOrders,
                                flagreq: 'dp2-return'
                            },
                            success: function (response)
                            {
                                var str = response;
                                var n = str.search("Error");
                                if (n < 0)
                                {
                                    commonAlert('DP2 Successfull');
                                    location.reload();
                                } else
                                {
                                    commonAlert(response);
                                }

                            }
                        })
                    } else
                    {
                        var returnOrders = $('#returnSelectedOrders').val();
                        $.ajax(
                        {
                            type: 'post',
                            url: 'toupdateorders',
                            data:
                            {
                                get_orderid: 'na',
                                returnOrders: returnOrders,
                                flagreq: 'dp2-return'
                            },
                            success: function (response)
                            {
                                var str = response;
                                var n = str.search("Error");
                                if (n < 0)
                                {
                                    commonAlert('DP2 Successfull');
                                    location.reload();
                                } else
                                {
                                    commonAlert(response);
                                }

                            }
                        })
                    }
                }
            })

            //Cash selection
            var cnt = 0;
            var cashAmt = 0;
            var nanHnd = 0;
            $("input[name=cashChk]").click(function ()
            {
                if ($(this).is(":checked"))
                {
                    cnt = cnt + 1;
                    $('#selectedCashCount').html(cnt);
                    if (isNaN(parseInt($('#cashAmt' + $(this).val()).val())))
                    {
                        cashAmt = cashAmt + 0;
                    } else
                    {
                        cashAmt = cashAmt + parseInt($('#cashAmt' + $(this).val()).val());
                    }
                    $('#selectedCollectionAmount').html(cashAmt);
                    $('#tr' + $(this).val()).css('background-color', 'rgb(221, 255, 221)');
                } else
                {
                    cnt = cnt - 1;
                    $('#selectedCashCount').html(cnt);
                    cashAmt = cashAmt - parseInt($('#cashAmt' + $(this).val()).val());
                    $('#selectedCollectionAmount').html(cashAmt);
                    $('#tr' + $(this).val()).css('background-color', 'white');
                }
                var cashSelectedOrders = [];
                $("input[name=cashChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        cashSelectedOrders.push('{"orderid":"' + $(this).val() + '","CashAmt":"' + $('#cashAmt' + $(this).val()).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#cashSelectedOrders').val('[' + cashSelectedOrders.toString() + ']');
            });

            //Return selection
            var retCnt = 0;
            $("input[name=retChk]").click(function ()
            {
                if ($(this).is(":checked"))
                {
                    retCnt = retCnt + 1;
                    $('#selectedReturnCount').html(retCnt);
                    $('#tr' + $(this).val()).css('background-color', 'rgb(255, 221, 221)');
                } else
                {
                    retCnt = retCnt - 1;
                    $('#selectedReturnCount').html(retCnt);
                    $('#tr' + $(this).val()).css('background-color', 'white');
                }
                var returnSelectedOrders = [];
                $("input[name=retChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        returnSelectedOrders.push('{"orderid":"' + $(this).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#returnSelectedOrders').val('[' + returnSelectedOrders.toString() + ']');
            });

            //Partial selection

            var partialCnt = 0;
            var partialAmt = 0;
            $("input[name=partialChk]").click(function ()
            {
                if ($(this).is(":checked"))
                {
                    partialCnt = partialCnt + 1;
                    $('#selectedPartialCount').html(partialCnt);
                    if (isNaN(parseInt($('#cashAmt' + $(this).val()).val())))
                    {
                        partialAmt = partialAmt + 0;
                    } else
                    {
                        partialAmt = partialAmt + parseInt($('#cashAmt' + $(this).val()).val());
                    }
                    $('#selectedPartialAmount').html(partialAmt);
                    $('#tr' + $(this).val()).css('background-color', 'rgb(255, 213, 128)');
                } else
                {
                    partialCnt = partialCnt - 1;
                    $('#selectedPartialCount').html(partialCnt);
                    partialAmt = partialAmt - parseInt($('#cashAmt' + $(this).val()).val());
                    $('#selectedPartialAmount').html(partialAmt);
                    $('#tr' + $(this).val()).css('background-color', 'white');
                }
                var partialSelectedOrders = [];
                $("input[name=partialChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        partialSelectedOrders.push('{"orderid":"' + $(this).val() + '","CashAmt":"' + $('#cashAmt' + $(this).val()).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#partialSelectedOrders').val('[' + partialSelectedOrders.toString() + ']');
            });

            function updateCash()
            {
                cashAmt = 0;
                var cashSelectedOrders = [];
                $("input[name=cashChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        cashAmt = cashAmt + parseInt($('#cashAmt' + $(this).val()).val());
                        $('#selectedCollectionAmount').html(cashAmt);
                        cashSelectedOrders.push('{"orderid":"' + $(this).val() + '","CashAmt":"' + $('#cashAmt' + $(this).val()).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#cashSelectedOrders').val('[' + cashSelectedOrders.toString() + ']');


                var returnSelectedOrders = [];
                $("input[name=retChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        returnSelectedOrders.push('{"orderid":"' + $(this).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#returnSelectedOrders').val('[' + returnSelectedOrders.toString() + ']');

                partialAmt = 0;
                var partialSelectedOrders = [];
                $("input[name=partialChk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        partialAmt = partialAmt + parseInt($('#cashAmt' + $(this).val()).val());
                        $('#selectedPartialAmount').html(partialAmt);
                        partialSelectedOrders.push('{"orderid":"' + $(this).val() + '","CashAmt":"' + $('#cashAmt' + $(this).val()).val() + '","comments":"' + $('#comments' + $(this).val()).val() + '"}');
                    }
                });
                $('#partialSelectedOrders').val('[' + partialSelectedOrders.toString() + ']');
            }
        </script>
    </body>
</html>
