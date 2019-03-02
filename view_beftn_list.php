<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $bankSQL = "select bankID, bankName from tbl_bank_info";
    $bankResult = mysqli_query($conn, $bankSQL);

    //$pendingInvoiceSQL = "select tbl_invoice.*, tbl_merchant_info.merchantName from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode where beftn = 'N' and tbl_invoice.merchantCode in (select merchantCode from tbl_merchant_info where paymentMode = 'beftn')";
    $pendingInvoiceSQL = "select tbl_invoice.invNum, tbl_merchant_info.merchantName, sum(cashCollection - deliveryCharge - CashCoD) as amount from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode left join tbl_invoice_details on tbl_invoice.invNum = tbl_invoice_details.invNum where beftn = 'N' and tbl_invoice.merchantCode in (select merchantCode from tbl_merchant_info where paymentMode = 'beftn') group by tbl_invoice.invNum ";
    $pendingInvoiceResult = mysqli_query($conn, $pendingInvoiceSQL);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and beftn = 'Y'"));
    if ($userPrivCheckRow['beftn'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">BEFTN</p>
            <div class="container-fluid" style="font: 15px 'paperfly roman'">
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <select id="beftnList" data-placeholder="Select BEFTN ID ......" style="width: 100%; margin-left: 15px">
                            <option></option>
                        </select>
                        <br><br>
                    </div>
                </div>
                <div class="row">
                    <button type="button" id="beftnLetter" class="btn btn-default" style="margin-left: 15px" onclick="beftnToPDF()">BEFTN Letter</button>
                    <button type="button" id="beftnFile" class="btn btn-success" onclick="beftnToExcel()">BEFTN File</button>
                    <p id="successMsg" style="margin-left: 15px" ></p>
                    <input type="hidden" id="beftnID">
                    <hr>
                    <button style="margin-left: 15px" type="button" id="beftnFile" class="btn btn-primary" onclick="generateBeftn()">Generate BEFTN Instruction</button>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="pendingChequeList" style="font: 18px 'paperfly roman'"><b><u>Pending Invoice List for BEFTN (Eastern Bank Ltd.)</u></b> </label>
                        <table class="table table-hover" id="pending-invoice-table">
                            <thead style="font: 14px 'paperfly roman'">
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Merchant Name</th>
                                    <th>Amount</th>
                                    <th>General/Collection</th>
                                    <th>Select</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php   foreach($pendingInvoiceResult as $pendingInvoiceRow){?>
                                <tr id="tr<?php echo $pendingInvoiceRow['invNum'];?>">
                                    <td><?php echo $pendingInvoiceRow['invNum'];?></td>
                                    <td><?php echo $pendingInvoiceRow['merchantName'];?></td>
                                    <td><?php echo $pendingInvoiceRow['amount'];?></td>
                                    <td>
                                        <select id="sel<?php echo $pendingInvoiceRow['invNum'];?>">
                                            <option value="general">General</option>
                                            <option value="collection">Collection</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="checkbox" id="chk<?php echo $pendingInvoiceRow['invNum'];?>" value="<?php echo $pendingInvoiceRow['invNum'];?>" checked>
                                    </td>
                                    <td><button class="btn btn-warning" id="btn<?php echo $pendingInvoiceRow['invNum'];?>" onclick="<?php echo "return removeBeftn('".$pendingInvoiceRow['invNum']."')"; ?>">Exclude</button></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
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
            $(window).load(function ()
            {
                $('#beftnList').select2();
                beftnList();
            });
            function beftnList()
            {
                var flag = 'beftnList';
                $.ajax(
                {
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'NA',
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#beftnList').html(response);
                        } else
                        {
                            $('#successMsgD').html(response);
                        }
                    }
                })
            }
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }
            function beftnToPDF()
            {
                var beftnID = $('#beftnList').val();
                if(beftnID >0)
                {
                    window.open("Application-BEFTN?xxCode=" + beftnID, "_blank");    
                }
            }
            function beftnToExcel()
            {
                var beftnID = $('#beftnList').val();
                if(beftnID >0)
                {
                    window.open("export-BEFTN-List?xxCode=" + beftnID, "_blank");                    
                }
            }
            function generateBeftn()
            {
                var invVal = [];
                var collectionVal = [];
                $("input[type=checkbox]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        invVal.push("'" + $(this).val() + "'");
                        var selID = $(this).val();
                        if ($('#sel' + selID).val() == 'collection')
                        {
                            collectionVal.push("'" + $(this).val() + "'");
                        }
                    }
                });
                var flag = 'beftnInfo';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: invVal.toString(),
                        collectionVal: collectionVal.toString(),
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#beftnID').val(response);
                            window.open("export-BEFTN-List?xxCode=" + response, "_blank");
                            window.open("Application-BEFTN?xxCode=" + response, "_blank");
                            setTimeout(location.reload(true), 1000);
                        } else
                        {
                            $('#successMsgD').html(response);
                        }
                    }
                });
            }
            function removeBeftn(r)
            {
                var flag = 'beftnRemove';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: r,
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#chk' + r).prop('checked', false);
                            $('#tr' + r).hide();

                        } else
                        {
                            $('#successMsgD').html(response);
                        }
                    }
                });
            }
        </script>
    </body>
</html>
