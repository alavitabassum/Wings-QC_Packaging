<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and barcodePrint = 'Y'"));
    if ($userPrivCheckRow['barcodePrint'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post" name="barcode" style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Barcode Generation</p>
                <div class="container-fluid" style="font: 15px 'paperfly roman'">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="printDate">Date</label>
                            <input id="startDate" style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="startDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.barcode.startDate,'dd-mm-yyyy',this)" required> 
                            <span><button class="btn btn-info" id="showMerchant" type="button" onclick="populateMerchant()">Show merchant list</button></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="merchantList">Select Merchant</label> 
                            <select id ="merchantList" name="merchantList" data-placeholder="Select Merchant................." style="margin-left: 10px; width: 100%; height: 28px" required>
                                <option></option>
                            </select>
                       </div>
                    </div>
                 </div>
                <br>
                &nbsp;<button id="viewBarcode" type="button" class="btn btn-primary" onclick="generateBarcode()">Generate Barcode</button>
            </form>
         </div>
        <script>
            $(window).load(function ()
            {
                $('#merchantList').select2();
            });
            function generateBarcode()
            {
                var merchant = $('#merchantList').val();
                var startDate = $('#startDate').val();
                if (merchant == '')
                {
                    alert('Merchant Name Require');
                } else
                {
                    window.open("Print-Barcode?merchant=" + merchant + "&startDate=" + startDate, "_blank");
                }
            }
            function populateMerchant()
            {
                var startDate = $('#startDate').val();
                var flag = 'merchantList';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: startDate,
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        $('#merchantList').html(response);
                    }
                });
            }
        </script>       
    </body>
</html>
