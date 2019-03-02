<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and close_order = 'Y'"));
    if ($userPrivCheckRow['close_order'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form name="batchClose" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Batch Close</p>
                <br>
                <?php
                    $cashCount = 0;
                    $retCount = 0;
                    $partialCount = 0;
                    if (!isset($_POST['submit'])) {
                        $cashOrderSQL = "select count(1) as cashCount from tbl_order_details where Cash ='Y' and bank = 'Y' and close is null";
                        $cashOrderResult = mysqli_query($conn, $cashOrderSQL);
                        $cashOrderRow = mysqli_fetch_array($cashOrderResult);
                        $cashCount = $cashOrderRow['cashCount'];

                        $retOrderSQL = "select count(1) as retCount from tbl_order_details where Ret ='Y' and retcp1 = 'Y' and close is null";
                        $retOrderResult = mysqli_query($conn, $retOrderSQL);
                        $retOrderRow = mysqli_fetch_array($retOrderResult);
                        $retCount = $retOrderRow['retCount'];

                        $partialOrderSQL = "select count(1) as partialCount from tbl_order_details where partial ='Y' and bank = 'Y' and retcp1 = 'Y' and close is null";
                        $partialOrderResult = mysqli_query($conn, $partialOrderSQL);
                        $partialOrderRow = mysqli_fetch_array($partialOrderResult);
                        $partialCount = $partialOrderRow['partialCount'];
                    }
                ?>
                <input type="hidden" name="cashCount" value="<?php echo $cashCount;?>">
                <input type="hidden" name="retCount" value="<?php echo $retCount;?>">
                <input type="hidden" name="partialCount" value="<?php echo $partialCount;?>">
                <div class="row" style="margin: 0px">
                    <div class="col-sm-2" style="margin-left: 10px; height: 60px; border-style: solid; border-width: 1px; border-color: #16469E; text-align: center">
                        <p style="color: #4CAF50; font: 50px 'paperfly roman'"><?php echo $cashCount;?></p>
                    </div>
                    <div class="col-sm-2" style="margin-left: 10px; height: 60px; border-style: solid; border-width: 1px; border-color: #16469E; text-align: center">
                        <p style="color: #F44336; font: 50px 'paperfly roman'"><?php echo $retCount;?></p>
                    </div>
                    <div class="col-sm-2" style="margin-left: 10px; height: 60px; border-style: solid; border-width: 1px; border-color: #16469E; text-align: center">
                        <p style="color: #FF9800; font: 50px 'paperfly roman'"><?php echo $partialCount;?></p>
                    </div>
                </div>
                <br>
                <div class="row" style="margin: 0px">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <label for="closeDate">Close Date</label>
                        <input type="text" id="closeDate" name="closeDate" class="form-control" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.batchClose.closeDate,'dd-mm-yyyy',this)" required>
                    </div>
                </div>
                <div class="row" style="margin: 0px">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                        <input type="submit" name="submit" value="Close" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $closeDate = date('Y-m-d', strtotime($_POST['closeDate']));
                    $cashCount = $_POST['cashCount'];
                    $retCount = $_POST['retCount'];
                    $partialCount = $_POST['partialCount'];
                    $cashUpdateSQL = "update tbl_order_details set close = 'Y', closeTime = '$closeDate', closeBy = '$user_check' where Cash = 'Y' and bank = 'Y' and close is null";
                    echo '<div class="row" style="margin: 0px">';
                    if (!mysqli_query($conn,$cashUpdateSQL))
                        {
                            
                            $error ="Cash Close Error : " . mysqli_error($conn);
                            echo "<div class='col-sm-3 alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='col-sm-3 alert alert-success'>";
                                echo $cashCount." Cash Orders closed successfully";
                            echo "</div>";
                        }
                    echo '</div>';
                    $retUpdateSQL = "update tbl_order_details set close = 'Y', closeTime = '$closeDate', closeBy = '$user_check' where Ret = 'Y' and retcp1 = 'Y' and close is null";
                    echo '<div class="row" style="margin: 0px">';
                    if (!mysqli_query($conn,$retUpdateSQL))
                        {

                            $error ="Return Orders Close Error : " . mysqli_error($conn);
                            echo "<div class='col-sm-3 alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='col-sm-3 alert alert-success'>";
                                echo $retCount." Return Orders closed successfully";
                            echo "</div>";
                        }
                    echo '</div>';
                    $partialUpdateSQL = "update tbl_order_details set close = 'Y', closeTime = '$closeDate', closeBy = '$user_check' where partial = 'Y' and bank = 'Y' and retcp1 = 'Y' and close is null";
                    echo '<div class="row" style="margin: 0px">';
                    if (!mysqli_query($conn,$partialUpdateSQL))
                        {
                            $error ="Partial Order Close Error : " . mysqli_error($conn);
                            echo "<div class='col-sm-3 alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='col-sm-3 alert alert-success'>";
                                echo $partialCount." Partial Orders closed successfully";
                            echo "</div>";
                        }
                    echo '</div>';
                    }
                mysqli_close($conn);                    
            ?>
        </div>
    </body>
</html>
