<?php
 /* Fetching Current monthorder records */
  $orderCntSql3 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND `merchantCode`= '$user_code' AND  MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery3 = mysqli_query($conn, $orderCntSql3);
  $orderCntrow3 = mysqli_fetch_row($orderCntquery3);
  $total_rows_total_cur = $orderCntrow3[0];

   
  $orderCntSql2 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND `Cash`='Y' and (`partial` = 'Y' OR `partial` IS NULL) and `merchantCode`= '$user_code' AND  MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery2 = mysqli_query($conn, $orderCntSql2);
  $orderCntrow2 = mysqli_fetch_row($orderCntquery2);
  $total_rows_success = $orderCntrow2[0];

  $orderCntSql4 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND `Ret`='Y' and `merchantCode`= '$user_code' AND  MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery4 = mysqli_query($conn, $orderCntSql4);
  $orderCntrow4 = mysqli_fetch_row($orderCntquery4);
  $total_rows_return = $orderCntrow4[0];

  $orderCntSql5 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND   `Shtl` = 'Y'  AND `Cash` IS NULL AND `close` IS NULL  AND `Rea` IS NOT NULL AND  `onHoldSchedule` IS NOT NULL and  `merchantCode` = '$user_code' AND MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery5 = mysqli_query($conn, $orderCntSql5);
  $orderCntrow5 = mysqli_fetch_row($orderCntquery5);
  $total_rows_hold = $orderCntrow5[0];

  $orderCntSql6 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `close`='Y' and  `merchantCode` = '$user_code' AND MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery6 = mysqli_query($conn, $orderCntSql6);
  $orderCntrow6 = mysqli_fetch_row($orderCntquery6);
  $total_rows_closed = $orderCntrow6[0];

  $orderCntSql7 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND   `Shtl` = 'Y' AND  `Cash` IS NULL AND `Ret` IS NULL AND `partial` IS NULL AND `Rea` IS NULL AND `onHoldSchedule` IS NULL AND `close` IS NULL  and  `merchantCode` = '$user_code' AND MONTH(`orderDate`) = MONTH(CURRENT_DATE()) AND YEAR(`orderDate`) = YEAR(CURRENT_DATE()) AND `orderDate` < CURRENT_DATE()";
  $orderCntquery7 = mysqli_query($conn, $orderCntSql7);
  $orderCntrow7 = mysqli_fetch_row($orderCntquery7);
  $total_rows_live = $orderCntrow7[0];

/*Fetching Previous month order records */

$orderCntSql_prev1 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev1 = mysqli_query($conn, $orderCntSql_prev1);
$orderCntrow_prev1 = mysqli_fetch_row($orderCntquery_prev1);
$total_rows_prev = $orderCntrow_prev1[0];


$orderCntSql_prev2 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `Cash`='Y' and (`partial` = 'Y' OR `partial` IS NULL) and `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev2 = mysqli_query($conn, $orderCntSql_prev2);
$orderCntrow_prev2 = mysqli_fetch_row($orderCntquery_prev2);
$total_rows_prev_success = $orderCntrow_prev2[0];

$orderCntSql_prev3 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `Ret`='Y' and `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev3 = mysqli_query($conn, $orderCntSql_prev3);
$orderCntrow_prev3 = mysqli_fetch_row($orderCntquery_prev3);
$total_rows_prev_return = $orderCntrow_prev3[0];

$orderCntSql_prev4 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND   `Shtl` = 'Y' AND `Cash` IS NULL AND `close` IS NULL AND `Rea` IS NOT NULL AND  `onHoldSchedule` IS NOT NULL and `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev4 = mysqli_query($conn, $orderCntSql_prev4);
$orderCntrow_prev4 = mysqli_fetch_row($orderCntquery_prev4);
$total_rows_prev_hold = $orderCntrow_prev4[0];

$orderCntSql_prev5 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `close`='Y' and `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev5 = mysqli_query($conn, $orderCntSql_prev5);
$orderCntrow_prev5 = mysqli_fetch_row($orderCntquery_prev5);
$total_rows_prev_closed = $orderCntrow_prev5[0];

$orderCntSql_prev6 ="SELECT COUNT(1) FROM `tbl_order_details` where `Pick`='Y' AND  `Shtl` = 'Y' AND `Cash` IS NULL AND `Ret` IS NULL AND `partial` IS NULL AND `Rea` IS NULL AND `onHoldSchedule` IS NULL AND `close` IS NULL  AND  `merchantCode`= '$user_code' AND  YEAR(orderDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orderDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
$orderCntquery_prev6 = mysqli_query($conn, $orderCntSql_prev6);
$orderCntrow_prev6 = mysqli_fetch_row($orderCntquery_prev6);
$total_rows_prev_live = $orderCntrow_prev6[0];

?>
     
        <div style="clear: both; margin-left: 1%">
       <!-- <p id="ordercount" style="color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp;Order Count : 
       <span style="font: 13px 'paperfly roman'"><u><?php echo $total_rows;?></u>
      </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span id="altermsg" style="font: 13px 'paperfly roman'"></span> 
         
       <?php if ($unassaignedOrder > 0  and $user_type !="Merchant"){?>
        <span id="ordercount" style="color: red; font: 13px 'paperfly roman'">&nbsp;&nbsp;New orders for delivery : <span style="color: red; font: 13px 'paperfly roman'"><u><?php echo $unassaignedOrder;?></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span></span>
        <?php }?>
            </p> -->
            <input id="usercode" type="hidden" value="<?php echo $user_code ;?>">
            <form action="" method="post">
            <table>
                <tr>
                    <!-- <td style="padding-left: 1px; width: 190px">
                        <input  type="text" name="searchText" placeholder="Search Order ID">
                    </td>
                    <td>
                        <button  type="submit" class="btn btn-default" name="searchOrder">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </td> -->
                    <?php if ($user_type != 'Merchant'){?>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label></td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Pick-up Merchant&nbsp;&nbsp;<img id="img6" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 6)"/></label></td>
                    <?php }?>
                </tr>
            </table>
            <div class="wrap-btns">
            <ul id="horizontal-list">
            <li>
            <a href="placeorders" class="btn_order">Place Single Order</a>
            </li>
            <li>
            <a href="batchuploads" class="btn_order2">Place Batch Order</a>
            </li>
            </ul>
            </div>

             <div class="wrap">
               <div class="search">
                <input type="text" class="searchTerm" name="searchText" placeholder="Search Order ID">
                <button type="submit" class="searchButton"  name="searchOrder">
                <i class="glyphicon glyphicon-search"></i>
                </button>
              </div>    

            </div>

            </form>
            </div> 
                                         
                 <!-- Content Row -->

                 <div class="header_row">
                 <div  class="col-xl-12 col-sm-12 col-xs-12 col-md-12 col-lg-12 divBanner" >
                    <h3>Order Status</h3>
                    <p>Updated till: <span id="date"></span></p>
                    <hr>
                      <script type='text/javascript'>
                        var d = new Date();
                        d.setDate(d.getDate() - 1);
                        d = d.toString().substring(0,15);
                        document.getElementById("date").innerHTML = d;//Sun May 01 2016
                        </script>
                 </div>
                 </div>

     <div class="row">
       <div class="card-row">
       
<!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-sm-12 col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders Received</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_total_cur;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev;?></span> </div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-12 col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Successfully Delivered</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_success;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev_success ; ?></span> </div>
                  </div>
               
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-12 col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-warning shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">On hold</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_hold ;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev_hold;?></span> </div>
                  </div>
              
                </div>
              </div>
            </div>
          </div>

          <!-- Earnings (Monthly) Card Example -->
          <div class="col-xl-3 col-sm-12  col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-danger shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Returned</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_return ;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev_return;?></span> </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>

         

       </div>
  </div>

<!-- Content Row -->


 <!-- Content Row -->
 <div class="row">
       <div class="card-row">
<!-- Earnings (Monthly) Card Example -->
    <!--   <div class="col-xl-3 col-sm-12 col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders Closed</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_closed;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev_closed;?></span> </div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div> -->
 
          <div class="col-xl-3 col-sm-12 col-xs-12 col-md-3 col-lg-3 mb-4 col-set">
            <div class="card border-left-otw shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-otw text-uppercase mb-1">Orders On The Way</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Current month: <span><?php echo $total_rows_live;?></span></div>
                    <div class="card-footer">Last month: <span><?php echo $total_rows_prev_live ; ?></span> </div>
                  </div>
               
                </div>
              </div>
            </div>
          </div>


         
       </div>
  </div>

<!-- Content Row -->

               <!-- Content Row 2-->
               <!-- <div class="row">
                 <div class="card-row">


       
  
            <div class="card border-left-warning shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                     <div class="container2">
                      <div class="car-wrapper">
                        <div class="car-wrapper_inner">

                          <div class="car_outter">  
                            <div class="car">
                              <div class="body">
                                <div></div>
                              </div>
                              <div class="decos">
                                <div class="line-bot"></div>
                                <div class="door">
                                  <div class="handle"></div>
                                  <div class="bottom"></div>
                                </div>
                                <div class="window"></div> 
                                <div class="light"></div>
                                <div class="light-front"></div>
                                <div class="antenna"></div>
                                <div class="ice-cream">
                                  <div class="cone"></div>
                                </div>  
                              </div>
                              <div>
                                <div class="wheel"></div>
                                <div class="wheel"></div>
                              </div>    
                              <div class="wind">
                                <div class="p p1"></div>
                                <div class="p p2"></div>
                                <div class="p p3"></div>
                                <div class="p p4"></div>
                                <div class="p p5"></div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                   

                    </div>
              
                </div>
              </div>
            </div>
          

                 </div>
                </div> -->

<!-- Content Row -->
      
