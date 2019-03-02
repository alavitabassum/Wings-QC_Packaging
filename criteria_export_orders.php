<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and exportToExcel = 'Y'"));
    if ($userPrivCheckRow['exportToExcel'] != 'Y'){
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        <div style="clear: both; margin-left: 2%">
            <form name="exportCri" action="orderExport" method="post">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Enter Export Criteria</p>
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="startDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.exportCri.startDate,'dd-mm-yyyy',this)" required> 
                <span style="color: #16469E; font: 11px 'paperfly roman'">To</span>
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="endDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.exportCri.endDate,'dd-mm-yyyy',this)"required> 
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                    if ($user_type == 'Merchant'){
                        echo '<input id="showMerchant" type="checkbox" name="showMerchant" style="border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" checked hidden>';
                    } else {
                        echo '<input id="showMerchant" type="checkbox" name="showMerchant" style="border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff">';                        
                    }
                ?>
                <select id="merchantList" name="merchantList" data-placeholder="Select Merchant............." style="width: 250px; height: 28px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'">
                    <?php
                        if ($user_type == 'Merchant'){
                            $merchantSQL = "Select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                            $merchantResult = mysqli_query($conn, $merchantSQL);
                            foreach ($merchantResult as $merchantRow){
                                ?> 
                                <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                <?php
                            }                                                        
                        } else {
                        echo  '<option></option>';
                            $merchantSQL = "Select merchantCode, merchantName from tbl_merchant_info";
                            $merchantResult = mysqli_query($conn, $merchantSQL);
                            foreach ($merchantResult as $merchantRow){
                                ?> 
                                <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                <?php
                            }                            
                        }

                    ?>
                </select>&nbsp;&nbsp;&nbsp;
                <input type="submit" name="submit" value="Export" class="btn btn-primary">
            </form>
        </div>
        <script>
            $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
               $('#merchantList').select2();
            });
        </script>
    </body>
</html>
