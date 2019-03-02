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
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Barcode Warehouse</p>
            <div class="col-sm-12">
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12">
                        <p id="alrt" style="color: blue; text-align: center"></p>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-2">
                        <label>Barcode Quantity</label>
                        <input type="text" id="barcodeQty" class="form-control input-sm">
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button type="button" id="btnPrintBarcode" class="btn btn-default">Print</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#btnPrintBarcode').click(function () {
                var barcodeQty = $('#barcodeQty').val();
                if(barcodeQty > 0 || barcodeQty < 999){
                    //$.ajax({
                    //    type: 'post',
                    //    url: 'toupdateorders',
                    //    data:{
                    //        get_orderid: barcodeQty,
                    //        flagreq: 'ajaxEncryption'
                    //    },
                    //    success: function(response){
                    //        window.open("Barcode-Warehouse?barcodeQty=" + response, "_blank");
                    //    }
                    //})
                    window.open("Barcode-Warehouse?barcodeQty=" + barcodeQty, "_top");
                } else {
                    $('#alrt').css('color', 'red');
                    $('#alrt').html('Maximum 999 barcode request allowed');
                    setTimeout(function () { $('#alrt').html(''); }, 3000);                    
                }
                
            })
        </script>
    </body>
</html>
