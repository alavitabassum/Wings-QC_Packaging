<?php
    include('header.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and revSummary = 'Y'"));
    if ($userPrivCheckRow['revSummary'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Revenue Summary Report</p>
            <div style="width: 500px; float: left">
                <form action="" method="post">
                <br>
                <table>
                    <tr>
                        <td><label style="display: inline; font: 15px 'paperfly roman'">Select Merchant&nbsp;&nbsp;</label></td>
                        <td>
                            <select id ="merchantCode" name="merchantCode" data-placeholder="Select Merchant............." style="margin-left: 10px; width: 250px; height: 28px">
                                <?php if ($user_type == 'Merchant'){
                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);
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
                        </td>
                        <td></td>
                    </tr>
                    <tr style="height: 50px">
                        <td><label style="display: inline; font: 15px 'paperfly roman'">Select Period&nbsp;&nbsp;&nbsp;</label></td>
                        <td>
                            <select id ="monthList" name="monthList" data-placeholder="Select Month................." style="margin-left: 10px; width: 250px; height: 28px" required>
                                <option></option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>                        
                        </td>
                        <td>
                            &nbsp;<select id ="yearList" name="yearList" data-placeholder="Select Year................." style="margin-left: 10px; width: 100px; height: 28px" required>
                                    <option></option>
                                <?php
                                    $yearSQL = "SELECT distinct DATE_FORMAT(inv_date, '%Y') as invYear FROM tbl_invoice";
                                    $yearResult = mysqli_query($conn, $yearSQL);
                                    foreach ($yearResult as $yearRow) {
                                ?>
                                <option value="<?php echo $yearRow['invYear'];?>"><?php echo $yearRow['invYear'];?></option>
                                <?php
                                    }
                                ?>
                            </select>                        
                        </td>
                    </tr>                
                </table>
                <br>
                <input type="submit" class="btn btn-info" name="showReports" value="Show Report">
                </form>
<?php
  if (isset($_POST['showReports'])) {
      $merchantCode = trim($_POST['merchantCode']);
      $monthList = trim($_POST['monthList']);
      $yearList = trim($_POST['yearList']);
      
      $invMonthStart = $yearList.'-'.$monthList.'-2';    
      if ($monthList == 12){
          $monthList = 1;
          $yearList = $yearList+1;
          $invMonthEnd = $yearList.'-'.$monthList.'-1';    
      } else {
          $monthList = $monthList+1;
          $invMonthEnd = $yearList.'-'.$monthList.'-1';
      }

      $merchantNameSQL = "select merchantName from tbl_merchant_info where merchantCode ='$merchantCode'";
      $merchantNameResult = mysqli_query($conn, $merchantNameSQL);
      $merchantNameRow = mysqli_fetch_array($merchantNameResult);
      $merchantName = $merchantNameRow['merchantName'];

        $invSummary = "select destination, sum(TotalOrder) as totalorder, sum(cash+partial) as cash, sum(Ret) as ret, sum(deliveryCharge) as deliverycharge, sum(cashCollection) as cashcollection, sum(CashCoD) as cashcod from tbl_invoice_details where invNum in (select invNum from tbl_invoice where (inv_date between '$invMonthStart' and '$invMonthEnd') and merchantCode='$merchantCode') group by destination order by destination desc";
        $invSummaryResult = mysqli_query($conn, $invSummary);

        $totinvSummary = "select destination, sum(TotalOrder) as totalorder, sum(cash+partial) as cash, sum(Ret) as ret, sum(deliveryCharge) as deliverycharge, sum(cashCollection) as cashcollection, sum(CashCoD) as cashcod from tbl_invoice_details where invNum in (select invNum from tbl_invoice where inv_date between '$invMonthStart' and '$invMonthEnd') group by destination order by destination desc";
        $totinvSummaryResult = mysqli_query($conn, $totinvSummary);
?>
  <div id="revenueReport" style="width: 1300px; font: 20px 'paperfly roman'">
      <p style="width: 1200px; text-align: center">Revenue Summary Report<br><font size="2">for the month <?php  echo date('F',strtotime($invMonthStart)).', '.$yearList;?></font></p>
      <?php if ($merchantName !='') {?>
      <p style="width: 1200px; text-align: left; font-weight: 800; font-size: x-large"><?php echo $merchantName;?></p>
      <table class="table table-hover" style="width: 800px; float: left">
          <tr style="width: 800px; font: 11px 'paperfly roman'">
              <th></th>
              <th colspan="4" style="text-align: center; background-color: #cfcfcf">Orders & Delivery Charge</th>
              <th colspan="7" style="text-align: center; background-color: #cfcfcf">Collection & CoD Commission</th>
          </tr>
          <tr style="width: 800px; font: 11px 'paperfly roman'">
              <th>Destination</th>
              <th>Orders</th>
              <th>Successful</th>
              <th>Returned</th>
              <th>Delivery Charge</th>
              <th>Collection</th>
              <th>Cash Collection</th>
              <th>Card/Other Collection</th>
              <th>CoD from Cash</th>
              <th>CoD from Card/Others</th>
              <th>Total CoD</th>
              <th>Success rate</th>
          </tr>
          <?php 
            foreach($invSummaryResult as $invSummaryRow){
                $totOrders = $totOrders + $invSummaryRow['totalorder'];
                $totReturns = $totReturns + $invSummaryRow['ret'];
                $totCollection = $totCollection + $invSummaryRow['cashcollection'];
                $totDeliveryCharge = $totDeliveryCharge + $invSummaryRow['deliverycharge'];
                $totCod = $totCod + $invSummaryRow['cashcod'];

                if ($invSummaryRow['destination'] == 'local'){
          ?>
                    <tr style="width: 800px; font: 10px 'paperfly roman'">
                      <td><?php echo $invSummaryRow['destination'];?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['totalorder']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cash']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['ret']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['deliverycharge']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcollection']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcollection']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcod']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcod']));?></td>
                      <td><?php echo round(($invSummaryRow['cash']/$invSummaryRow['totalorder'])*100).'%';?></td>
                    </tr>
          <?php
              }
              if ($invSummaryRow['destination'] == 'interDistrict'){
          ?>
                    <tr style="width: 800px; font: 10px 'paperfly roman'">
                      <td><?php echo $invSummaryRow['destination'];?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['totalorder']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cash']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['ret']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['deliverycharge']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcollection']));?></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcollection']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcod']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($invSummaryRow['cashcod']));?></td>
                      <td><?php echo round(($invSummaryRow['cash']/$invSummaryRow['totalorder'])*100).'%';?></td>
                    </tr>
          <?php
              }      
            }
          ?>
      </table>
      <table class="table table-hover" style="width: 400px; float: left">
          <tr style="width: 400px; font: 11px 'paperfly roman'">
              <th colspan="7" style="text-align: center; background-color: #c0c0c0">Month Summary</th>
          </tr>
          <tr style="width: 400px; font: 11px 'paperfly roman'">
              <th>Total order</th>
              <th>Total return</th>
              <th>Success rate</th>
              <th>Total collection</th>
              <th>Total delivery charge</th>
              <th>Total CoD</th>
              <th>Total revenue</th>
          </tr>
          <tr style="width: 400px; height: 60px; font: 10px 'paperfly roman'">
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totOrders));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totReturns));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo round((($totOrders -$totReturns)/$totOrders)*100).'%';?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totCollection));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totDeliveryCharge));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totCod));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totDeliveryCharge + $totCod));?></td>
          </tr>
      </table>
  <?php }?>    
      <p style="width: 1200px; text-align: left; color: #16469E; font-weight: 800; font-size: x-large; display: inline-block">Paperfly</p>
      <table class="table table-hover" style="width: 800px; float: left">
          <tr style="width: 800px; font: 11px 'paperfly roman'">
              <th></th>
              <th colspan="4" style="text-align: center; background-color: #cfcfcf">Orders & Delivery Charge</th>
              <th colspan="7" style="text-align: center; background-color: #cfcfcf">Collection & CoD Commission</th>
          </tr>
          <tr style="width: 800px; font: 11px 'paperfly roman'">
              <th>Destination</th>
              <th>Orders</th>
              <th>Successful</th>
              <th>Returned</th>
              <th>Delivery Charge</th>
              <th>Collection</th>
              <th>Cash Collection</th>
              <th>Card/Other Collection</th>
              <th>CoD from Cash</th>
              <th>CoD from Card/Others</th>
              <th>Total CoD</th>
              <th>Success rate</th>
          </tr>
          <?php 
            foreach($totinvSummaryResult as $totinvSummaryRow){
                $totSumOrders = $totSumOrders + $totinvSummaryRow['totalorder'];
                $totSumReturns = $totSumReturns + $totinvSummaryRow['ret'];
                $totSumCollection = $totSumCollection + $totinvSummaryRow['cashcollection'];
                $totSumDeliveryCharge = $totSumDeliveryCharge + $totinvSummaryRow['deliverycharge'];
                $totSumCod = $totSumCod + $totinvSummaryRow['cashcod'];

                if ($totinvSummaryRow['destination'] == 'local'){
          ?>
                    <tr style="width: 800px; font: 10px 'paperfly roman'">
                      <td><?php echo $totinvSummaryRow['destination'];?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['totalorder']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cash']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['ret']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['deliverycharge']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcollection']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcollection']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcod']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcod']));?></td>
                      <td><?php echo round(($totinvSummaryRow['cash']/$totinvSummaryRow['totalorder'])*100).'%';?></td>
                    </tr>
          <?php
              }
              if ($totinvSummaryRow['destination'] == 'interDistrict'){
          ?>
                    <tr style="width: 800px; font: 10px 'paperfly roman'">
                      <td><?php echo $totinvSummaryRow['destination'];?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['totalorder']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cash']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['ret']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['deliverycharge']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcollection']));?></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcollection']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcod']));?></td>
                      <td></td>
                      <td><?php echo num_to_format(round($totinvSummaryRow['cashcod']));?></td>
                      <td><?php echo round(($totinvSummaryRow['cash']/$totinvSummaryRow['totalorder'])*100).'%';?></td>
                    </tr>
          <?php
              }      
            }
          ?>
      </table>
      <table class="table table-hover" style="width: 400px; float: left">
          <tr style="width: 400px; font: 11px 'paperfly roman'">
              <th colspan="7" style="text-align: center; background-color: #c0c0c0">Month Summary</th>
          </tr>
          <tr style="width: 400px; font: 11px 'paperfly roman'">
              <th>Total order</th>
              <th>Total return</th>
              <th>Success rate</th>
              <th>Total collection</th>
              <th>Total delivery charge</th>
              <th>Total CoD</th>
              <th>Total revenue</th>
          </tr>
          <tr style="width: 400px; height: 60px; font: 10px 'paperfly roman'">
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumOrders));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumReturns));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo round((($totSumOrders -$totSumReturns)/$totSumOrders)*100).'%';?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumCollection));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumDeliveryCharge));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumCod));?></td>
              <td style="text-align: center; vertical-align: middle"><?php echo num_to_format(round($totSumDeliveryCharge + $totSumCod));?></td>
          </tr>
      </table>

  </div>

<?php
  }                 
?>
                <script type="text/javascript" charset="utf-8">
                    $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                       $('#merchantCode').select2();
                    });

                    $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                       $('#invoiceList').select2();
                    });

                    $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                       $('#monthList').select2();
                    });

                    $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                       $('#yearList').select2();
                    });

                </script>
            </div>
        </div>
    </body>
</html>
