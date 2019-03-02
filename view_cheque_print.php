<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $bankSQL = "select bankID, bankName from tbl_bank_info";
    $bankResult = mysqli_query($conn, $bankSQL);

    //$pendingInvoiceSQL = "select tbl_invoice.*, tbl_merchant_info.merchantName from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode where chequeStatus = 'N' and tbl_invoice.merchantCode in (select merchantCode from tbl_merchant_info where paymentMode = 'cheque')";
    $pendingInvoiceSQL = "select tbl_invoice.invID, tbl_invoice.invNum, tbl_merchant_info.merchantName, sum(tbl_invoice_details.cashCollection) as collection, sum(tbl_invoice_details.deliveryCharge + tbl_invoice_details.CashCoD) as charges from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode left join tbl_invoice_details on tbl_invoice.invNum = tbl_invoice_details.invNum where chequeStatus = 'N' and tbl_invoice.merchantCode in (select merchantCode from tbl_merchant_info where paymentMode = 'cheque') group by tbl_invoice.invID, tbl_invoice.invNum, tbl_merchant_info.merchantName";
    $pendingInvoiceResult = mysqli_query($conn, $pendingInvoiceSQL);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and chequePrint = 'Y'"));
    if ($userPrivCheckRow['chequePrint'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Cheque Printing</p>
            <div class="container-fluid" style="font: 15px 'paperfly roman'">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="printChequeGeneral" style="color: #16469E; padding-bottom: 20px; font: 20px 'paperfly roman'"><u>Print Cheque for General Purpose</u> </label>
                        <label for="payTo">Pay to </label>
                        <input type="text" class="form-control" style="height: 27px" id="payTo" name="payTo" required>
                        <label for="paidAmt">Amount </label>
                        <input type="text" class="form-control" style="height: 27px" id="paidAmt" name="paidAmt" onkeyup="return isNumberKey(this)" required>
                        <label for="chequeNo">Cheque No </label>
                        <input type="text" class="form-control" style="height: 27px" id="chequeNo" name="chequeNo" required>
                        <label for="selectBank">Select Bank </label>
                        <select style="width: 100%" id="bankID" name="bankID">
                            <?php foreach($bankResult as $bankRow){?>
                                <option value="<?php echo $bankRow['bankID'];?>"><?php echo $bankRow['bankName'];?></option>
                            <?php }?>
                        </select>
                        <label for="payReason" style="padding-top: 10px">Reason </label>
                        <input type="text" class="form-control" style="height: 27px" id="payReason" name="payReason" required>
                    </div>
                </div>
                <div class="row" style="padding-left: 15px">
                    <button id="generalCheque" class="btn btn-primary" name="generalCheque" onclick="return generalChequePrint()">Print Cheque</button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="printChequeInvoice" style="color: #16469E; padding-bottom: 20px; font: 20px 'paperfly roman'"><u>Print Cheque against Invoices</u> </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="pendingChequeList" style="font: 18px 'paperfly roman'"><b><u>Pending Cheque List</u></b> </label>
                        <table class="table table-hover" id="pending-invoice-table">
                            <thead style="font: 14px 'paperfly roman'">
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Merchant Name</th>
                                    <th>General/Collection</th>
                                    <th>Bank</th>
                                    <th>Cheque No</th>
                                    <th>Collection Amount</th>
                                    <th>Invoice Amount</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php   foreach($pendingInvoiceResult as $pendingInvoiceRow){?>
                                <tr id="<?php echo $pendingInvoiceRow['invNum'];?>">
                                    <td><?php echo $pendingInvoiceRow['invNum'];?></td>
                                    <td><?php echo $pendingInvoiceRow['merchantName'];?></td>
                                    <td>
                                        <select id="sel<?php echo $pendingInvoiceRow['invNum'];?>">
                                            <option value="General">General</option>
                                            <option value="Collection">Collection</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="bank<?php echo $pendingInvoiceRow['invNum'];?>">
                                            <?php foreach($bankResult as $bankRow){?>
                                                <option value="<?php echo $bankRow['bankID'];?>"><?php echo $bankRow['bankName'];?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                    <td><input type="text" style="height: 27px" id="cheque<?php echo $pendingInvoiceRow['invNum'];?>"></td>
                                    <td><?php echo $pendingInvoiceRow['collection'];?></td>
                                    <td><?php echo ($pendingInvoiceRow['collection'] - round($pendingInvoiceRow['charges'], 0));?></td>
                                    <td><button class="btn btn-info" id="btn<?php echo $pendingInvoiceRow['invNum'];?>" onclick="<?php echo "return printPendingCheque('".$pendingInvoiceRow['invNum']."')"; ?>">Print</button></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="rePrintCheque" style="padding-bottom: 20px; font: 18px 'paperfly roman'"><b><u>Re-Print Cheque</u></b> </label>
                        <label for="selectMerchant">Select Merchant </label>
                        <select id="merchantList" name="merchantList" data-placeholder="Select Merchant............." style="width: 100%; height: 28px; font: 11px 'paperfly roman'" onchange ="fetch_select(this.value);">
                            <option></option>
                            <?php 
                                $merchantSQL = "Select merchantCode, merchantName from tbl_merchant_info";
                                $merchantResult = mysqli_query($conn, $merchantSQL);
                                foreach ($merchantResult as $merchantRow){
                                    ?> 
                                    <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <label for="rePrintInvoice" style="padding-top: 10px">Select Invoice</label>
                        <select id ="rePrintInvoice" name="invoicelist" data-placeholder="Select Invoice................." style="margin-left: 10px; width: 100%; height: 28px" required>
                            <option></option>
                        </select> 
                        <label for="rePrintBank" style="padding-top: 10px">Select Bank</label>
                        <select style="width: 100%; height: 28px" id="rePrintBank">
                            <?php foreach($bankResult as $bankRow){?>
                                <option value="<?php echo $bankRow['bankID'];?>"><?php echo $bankRow['bankName'];?></option>
                            <?php }?>
                        </select>
                        <label for="rePrintCheque" style="padding-top: 10px">Cheque No</label>
                        <input type="text" id="rePrintChequeNo" style="height: 27px">
                        <label for="rePrintFor">General/Collection Amount</label>
                        <select id="rePrintFor" style="width: 100px">
                            <option value="General">General</option>
                            <option value="Collection">Collection</option>
                        </select>
                        <label for="rePrintReason">Reason</label>
                        <input type="text" id="rePrintReason" style="height: 27px">
                        <div class="row" style="padding-top: 15px; padding-left: 30px">
                            <button id="rePrintCheque" class="btn btn-default" name="rePrintCheque" onclick="return rePrintCheque()">Re-Print</button>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="chequePrintingLog" style="color: #16469E; padding-bottom: 20px; font: 20px 'paperfly roman'"><u>Cheque Printing Log</u> </label>
                        <form name="exportLog" action="ChequeLog" method="post">
                            <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="startDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.exportLog.startDate,'dd-mm-yyyy',this)" required> 
                            <span style="color: #16469E; font: 11px 'paperfly roman'">To</span>
                            <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="endDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.exportLog.endDate,'dd-mm-yyyy',this)"required> 
                            <button id="chequeLog" type="submit" name="chequeLog" class="btn btn-primary">Export Log to Excel</button>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">

                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function ()
            {
                $('#pending-invoice-table').bdt({
                    showSearchForm: 1,
                    showEntriesPerPageField: 1
                });

            });
            function fetch_select(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'invoicefetch',
                    data: {
                        get_inv: val,
                        flagreq: 'cheque'
                    },
                    success: function (response)
                    {
                        document.getElementById("rePrintInvoice").innerHTML = response;
                        $('#rePrintInvoice').select2();
                    }
                });
            }
            $(window).load(function ()
            {
                $('#merchantList').select2();
                $('#bankID').select2();
                $('#rePrintInvoice').select2();
                $('#rePrintBank').select2();
            });
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }
            function generalChequePrint()
            {
                if ($('#payTo').val().length < 3 || $('#paidAmt').val() < 9 || $('#chequeNo').val().length < 5 || $('#payReason').val().length < 5)
                {
                    alert('All field must be filled up');
                } else
                {
                    var payTo = $('#payTo').val();
                    var paidAmt = $('#paidAmt').val();
                    var chequeNo = $('#chequeNo').val();
                    var bankID = $('#bankID').val();
                    var payReason = $('#payReason').val();
                    var flagreq = 'general';
                    $.ajax({
                        type: 'post',
                        url: 'generalChequePrint',
                        data: {
                            payto: payTo,
                            paidamt: paidAmt,
                            chequeno: chequeNo,
                            bank: bankID,
                            payreason: payReason,
                            flag: flagreq
                        },
                        success: function (response)
                        {
                            var successStr = response.substring(0, 7);
                            var printid = response.substring(7);
                            if (successStr == 'success')
                            {
                                //alert('Success string:'+successStr +'||'+ printid);
                                window.open("cheque_print_general.php?xxCode=" + printid, "_blank");
                            } else
                            {
                                alert(response);
                                //alert('Wrong string: '+successStr +'||'+ printid);
                            }
                        }
                    });
                }
            }
            function printPendingCheque(inv)
            {
                var chequeFor = $('#sel' + inv).val();
                if ($('#cheque' + inv).val().length < 5)
                {
                    alert('Cheque No require');
                } else
                {
                    var payTo = 'NA'
                    var paidAmt = 0;
                    var chequeNo = $('#cheque' + inv).val();
                    var bankID = $('#bank' + inv).val();
                    var payReason = 'NA';
                    var flagreq = 'pendingInvoice';
                    $.ajax({
                        type: 'post',
                        url: 'generalChequePrint',
                        data: {
                            payto: payTo,
                            paidamt: paidAmt,
                            chequeno: chequeNo,
                            bank: bankID,
                            payreason: payReason,
                            invNum: inv,
                            chequefor: chequeFor,
                            flag: flagreq
                        },
                        success: function (response)
                        {
                            var successStr = response.substring(0, 7);
                            var printid = response.substring(7);
                            if (successStr == 'success')
                            {
                                $('#' + inv).hide();
                                if (chequeFor == 'General')
                                {
                                    window.open("print_cheque.php?xxCode=" + printid, "_blank");
                                } else
                                {
                                    window.open("print_cheque_collection_only.php?xxCode=" + printid, "_blank");
                                }
                            } else
                            {
                                alert(response);
                            }
                        }
                    });
                }
            }
            function rePrintCheque()
            {
                var rePrintFor = $('#rePrintFor').val();

                if ($('#merchantList').val() == '' || $('#rePrintInvoice').val() == '' || $('#rePrintBank').val() == '' || $('#rePrintChequeNo').val().length < 5 || $('#rePrintReason').val().length < 5)
                {
                    alert('Input Require');
                } else
                {
                    var payTo = 'NA'
                    var paidAmt = 0;
                    var chequeNo = $('#rePrintChequeNo').val();
                    var bankID = $('#rePrintBank').val();
                    var payReason = $('#rePrintReason').val();
                    var inv = $('#rePrintInvoice').val();
                    var flagreq = 'rePrintCheque';
                    $.ajax({
                        type: 'post',
                        url: 'generalChequePrint',
                        data: {
                            payto: payTo,
                            paidamt: paidAmt,
                            chequeno: chequeNo,
                            bank: bankID,
                            payreason: payReason,
                            invNum: inv,
                            chequefor: rePrintFor,
                            flag: flagreq
                        },
                        success: function (response)
                        {
                            var successStr = response.substring(0, 7);
                            var printid = response.substring(7);
                            if (successStr == 'success')
                            {
                                if (rePrintFor == 'General')
                                {
                                    window.open("print_cheque.php?xxCode=" + printid, "_blank");
                                } else
                                {
                                    window.open("print_cheque_collection_only.php?xxCode=" + printid, "_blank");
                                }
                            } else
                            {
                                alert(response);
                            }
                        }
                    });
                }
            }
        </script>
    </body>
</html>
