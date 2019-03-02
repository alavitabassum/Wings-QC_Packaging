
        <div style="margin-left: 15px; width: 98%; clear: both">
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
                            </div>
                        </div>
                        
                       
                    
                    
                </div>

                <script type="text/javascript" charset="utf-8">
                    $(window).load(function ()
                    {
                        $('#merchantCode').select2();
                    });

                    $(window).load(function ()
                    {
                        $('#invoiceList').select2();
                    });

                    function fetch_select(val)
                    {
                        $.ajax({
                            type: 'post',
                            url: 'fetch_invoice_for5days.php',
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


                  
                </script>
            </div>
        </div>
    </body>
</html>