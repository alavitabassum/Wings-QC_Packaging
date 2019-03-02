<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and printInvoices = 'Y'"));
    if ($userPrivCheckRow['printInvoices'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search & Print Invoice</p>
                <div class="container-fluid" style="font: 15px 'paperfly roman'">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="genLable" style="padding-bottom: 20px; font: 20px 'paperfly roman'"><u>General Invoice</u> </label>
                            <label for="merchant">Select Merchant </label>
                            <select id ="merchantCode" name="merchantCode" data-placeholder="Select Merchant............." style="margin-left: 10px; width: 100%; height: 28px" onchange ="fetch_select(this.value);" required>
                                <?php if ($user_type == 'Merchant'){
                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                    echo "<option></option>";
                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                } else {
                                    $merchantsql = "select distinct tbl_invoice.merchantCode, tbl_merchant_info.merchantName from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);                            
                                    ?>
                                    <option></option>
                                    <?php
                                        foreach ($merchantresult as $merchantrow){
                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <label for="invoice">Select Invoice</label> 
                            <select id ="invoiceList" name="invoicelist" data-placeholder="Select Invoice................." style="margin-left: 10px; width: 100%; height: 28px" required>
                                <option></option>
                            </select>                        
                            <div class="row" style="padding: 30px">
                                <input type="button" class="btn btn-info" name="showOrders" value="Show Invoice" onclick="prevInv()" >
                                <input type="button" class="btn btn-success" name="exportItemize" value="Export Itemized" onclick="exportItemize('a')">
                                <input type="button" class="btn btn-success" name="exportInProgress" value="Export In Progress" onclick="exportInProgress('a')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="collectionLable" style="padding-bottom: 20px; font: 20px 'paperfly roman'"><u>Collection Part Only</u> </label>
                            <label for="merchant2">Select Merchant </label>
                            <select id ="merchantCode2" name="merchantCode" data-placeholder="Select Merchant............." style="margin-left: 10px; width: 100%; height: 28px" onchange ="fetch_select2(this.value);" required>
                                <?php if ($user_type == 'Merchant'){
                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                    echo "<option></option>";
                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                } else {
                                    $merchantsql = "select distinct tbl_invoice.merchantCode, tbl_merchant_info.merchantName from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);                            
                                    ?>
                                    <option></option>
                                    <?php
                                        foreach ($merchantresult as $merchantrow){
                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <label for="invoice2">Select Invoice</label>
                            <select id ="invoiceList2" name="invoicelist" data-placeholder="Select Invoice................." style="margin-left: 10px; width: 100%; height: 28px" required>
                                <option></option>
                            </select>                        
                            <div class="row" style="padding: 30px">
                                <input type="button" class="btn btn-info" name="showOrders2" value="Show Invoice" onclick="prevInv2()" >
                                <input type="button" class="btn btn-success" name="exportItemize" value="Export Itemized" onclick="exportItemize('b')">
                                <input type="button" class="btn btn-success" name="exportInProgress" value="Export In Progress" onclick="exportInProgress('b')">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="chargesLable" style="padding-bottom: 20px; font: 20px 'paperfly roman'"><u>Paperfly Charges Only</u> </label>
                            <label for="merchant3">Select Merchant </label>
                            <select id ="merchantCode3" name="merchantCode" data-placeholder="Select Merchant............." style="margin-left: 10px; width: 100%; height: 28px" onchange ="fetch_select3(this.value);" required>
                                <?php if ($user_type == 'Merchant'){
                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                    echo "<option></option>";
                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                } else {
                                    $merchantsql = "select distinct tbl_invoice.merchantCode, tbl_merchant_info.merchantName from tbl_invoice left join tbl_merchant_info on tbl_invoice.merchantCode = tbl_merchant_info.merchantCode";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);                            
                                    ?>
                                    <option></option>
                                    <?php
                                        foreach ($merchantresult as $merchantrow){
                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <label for="invoice3">Select Invoice</label>
                            <select id ="invoiceList3" name="invoicelist" data-placeholder="Select Invoice................." style="margin-left: 10px; width: 100%; height: 28px" required>
                                <option></option>
                            </select>                        
                            <div class="row" style="padding: 30px">
                                <input type="button" class="btn btn-info" name="showOrders3" value="Show Invoice" onclick="prevInv3()" >
                                <input type="button" class="btn btn-success" name="exportItemize" value="Export Itemized" onclick="exportItemize('c')">
                                <input type="button" class="btn btn-success" name="exportInProgress" value="Export In Progress" onclick="exportInProgress('c')">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-4">
                            <div class="row" style="margin-top: 10px">
                                <label for="monthLable" style="padding-left: 30px; font: 20px 'paperfly roman'"><u>Invoice for Monthly Charge</u> </label>
                            </div>
                            <div class="row" style="margin-top: 10px; padding-left: 30px">
                                <div class="col-sm-12">
                                    <?php
                                        if ($user_type == 'Merchant'){
                                            $monthMerchantResult = mysqli_query($conn, "select merchantCode, merchantName from tbl_merchant_info where monthlyInvoice = 'Y' and merchantCode = '$user_code'");    
                                        } else {
                                            $monthMerchantResult = mysqli_query($conn, "select merchantCode, merchantName from tbl_merchant_info where monthlyInvoice = 'Y'");
                                        }
                                        
                                    ?>
                                    <label for="monthMerchant">Select Merchant</label>
                                    <select id="monthMerchant" data-placeholder="Select Merchant....." style="width: 100%" onchange ="fetch_select4(this.value);">
                                        <option></option>
                                        <?php foreach($monthMerchantResult as $monthMerchantRow){?>
                                        <option value="<?php echo $monthMerchantRow['merchantCode'];?>"><?php echo $monthMerchantRow['merchantName'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px; padding-left: 30px">
                                <div class="col-sm-12">
                                    <label for="monthInvoice">Select Invoice</label>
                                    <select id="monthInvoice" data-placeholder="Select Invoice....." style="width: 100%">
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px; padding-left: 30px">
                                <div class="col-sm-12">
                                    <input type="button" class="btn btn-info" id="showMonthInvoice" value="Show Invoice" onclick="prevInv4()">
                                    <input type="button" class="btn btn-success" name="exportItemize" value="Export Itemized" onclick="exportItemize('d')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script type="text/javascript" charset="utf-8">
                    $(window).load(function ()
                    {
                        $('#merchantCode').select2();
                        $('#merchantCode2').select2();
                        $('#merchantCode3').select2();
                        $('#monthMerchant').select2();
                        $('#monthInvoice').select2();
                    });

                    $(window).load(function ()
                    {
                        $('#invoiceList').select2();
                        $('#invoiceList2').select2();
                        $('#invoiceList3').select2();
                    });

                    function fetch_select(val)
                    {
                        $.ajax({
                            type: 'post',
                            url: 'invoicefetch',
                            data: {
                                get_inv: val
                            },
                            success: function (response)
                            {
                                document.getElementById("invoiceList").innerHTML = response;
                                $('#invoiceList').select2();
                            }
                        });
                    }
                    function fetch_select2(val)
                    {
                        $.ajax({
                            type: 'post',
                            url: 'invoicefetch',
                            data: {
                                get_inv: val
                            },
                            success: function (response)
                            {
                                document.getElementById("invoiceList2").innerHTML = response;
                                $('#invoiceList2').select2();
                            }
                        });
                    }
                    function fetch_select3(val)
                    {
                        $.ajax({
                            type: 'post',
                            url: 'invoicefetch',
                            data: {
                                get_inv: val
                            },
                            success: function (response)
                            {
                                document.getElementById("invoiceList3").innerHTML = response;
                                $('#invoiceList3').select2();
                            }
                        });
                    }

                    function fetch_select4(val)
                    {
                        $.ajax({
                            type: 'post',
                            url: 'toupdateorders',
                            data: {
                                get_orderid: 'na',
                                merchantCode: val,
                                flagreq: 'monthInvoice'
                            },
                            success: function (response)
                            {
                                document.getElementById("monthInvoice").innerHTML = response;
                                $('#monthInvoice').select2();
                            }
                        });
                    }

                    function prevInv()
                    {
                        var inp = document.getElementById("invoiceList").value;
                        if (inp == '')
                        {
                            alert("Please insert invoice number");
                        } else
                        {
                            var ordertype = inp.substring(0, 1);
                            if (ordertype == 'S')
                            {
                                window.open("old_invoice_smart.php?xxCode=" + inp, "_blank");
                            } else
                            {
                                window.open("old_invoice.php?xxCode=" + inp, "_blank");
                            }

                            //window.open("old_invoice.php?xxCode=" + inp + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
                        }
                    }

                    function prevInv2()
                    {
                        var inp = document.getElementById("invoiceList2").value;
                        if (inp == '')
                        {
                            alert("Please insert invoice number");
                        } else
                        {
                            var ordertype = inp.substring(0, 1);
                            if (ordertype == 'S')
                            {
                                window.open("old_invoice_smart.php?xxCode=" + inp, "_blank");
                            } else
                            {
                                window.open("old_invoice_collection_only.php?xxCode=" + inp, "_blank");
                            }

                            //window.open("old_invoice.php?xxCode=" + inp + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
                        }
                    }
                    function prevInv3()
                    {
                        var inp = document.getElementById("invoiceList3").value;
                        if (inp == '')
                        {
                            alert("Please insert invoice number");
                        } else
                        {
                            var ordertype = inp.substring(0, 1);
                            if (ordertype == 'S')
                            {
                                window.open("old_invoice_smart.php?xxCode=" + inp, "_blank");
                            } else
                            {
                                window.open("old_invoice_charges_only.php?xxCode=" + inp, "_blank");
                            }

                            //window.open("old_invoice.php?xxCode=" + inp + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
                        }
                    }
                    function prevInv4()
                    {
                        var inp = document.getElementById("monthInvoice").value;
                        if (inp == '')
                        {
                            alert("Please insert invoice number");
                        } else
                        {
                            window.open("Invoice-Monthly?xxCode=" + inp, "_blank");

                            //window.open("old_invoice.php?xxCode=" + inp + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
                        }
                    }

                    function exportItemize(tp)
                    {
                        if (tp == 'a')
                        {
                            var inp = document.getElementById("invoiceList").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_itemized_detail.php?invNum=" + inp, "_self");
                            }
                        }
                        if (tp == 'b')
                        {
                            var inp = document.getElementById("invoiceList2").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_itemized_detail.php?invNum=" + inp, "_self");
                            }
                        }
                        if (tp == 'c')
                        {
                            var inp = document.getElementById("invoiceList3").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_itemized_detail.php?invNum=" + inp, "_self");
                            }
                        }
                        if(tp == 'd'){
                            var inp = document.getElementById("monthInvoice").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_monthly_itemized.php?invNum=" + inp, "_self");
                            }                            
                        }
                    }
                    function exportInProgress(ip)
                    {
                        if (ip == 'a')
                        {
                            var inp = document.getElementById("invoiceList").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_order_in_progress.php?invNum=" + inp, "_self");
                            }
                        }
                        if (ip == 'b')
                        {
                            var inp = document.getElementById("invoiceList2").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_order_in_progress.php?invNum=" + inp, "_self");
                            }
                        }
                        if (ip == 'c')
                        {
                            var inp = document.getElementById("invoiceList3").value;
                            if (inp == '')
                            {
                                alert("Please insert invoice number");
                            } else
                            {
                                window.open("export_order_in_progress.php?invNum=" + inp, "_self");
                            }
                        }
                    }
                </script>
            </div>
        </div>
    </body>
</html>