<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and returnReport = 'Y'"));
    if ($userPrivCheckRow['returnReport'] != 'Y'){
        exit();
    }
    $merchantListResult = mysqli_query($conn, "select merchantCode, merchantName from tbl_merchant_info where isActive = 'Y'");
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Return Report</p>
            <div class="col-sm-12">
                <form name="frmDate" action="" method="post" style="margin: 15px">
                    <div class="row">
                        <div class="col-sm-12">
                            <p id="alrt" style="color: blue; text-align: center"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1">
                            <label>SLA</label>
                            <select id="returnSLA" style="width: 100%">
                                <option value="0">All</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Merchant</label>
                            <select id="merchantCode" style="width: 100%">
                                <option value="0">All</option>
                                <?php foreach($merchantListResult as $merchantListRow){?>
                                <option value="<?php echo $merchantListRow['merchantCode'];?>"><?php echo $merchantListRow['merchantName'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <br>
                            <button type="button" id="btnShow" class="btn btn-default">Show</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover" id="returnDetail">
                            </table>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
        <script>
            $('select').select2();
            $('#btnShow').click(function () {
                $('#alrt').html('Please wait............');
                var returnSLA = $('#returnSLA').val();
                var merchantCode = $('#merchantCode').val();
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: returnSLA,
                        merchantCode: merchantCode,
                        flagreq: 'returnOrdersTrack'
                    },
                    success: function (response) {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0) {
                            $('#returnDetail').html('');
                            $('#returnDetail').html(response);
                            $('#alrt').html('');
                        } else {
                            $('#alrt').html(response);
                        }
                    }
                })
            })
            function regionDetail(inp) {
                $('.' + inp).toggle("slow", "swing");
            }
        </script>        
    </body>
</html>
