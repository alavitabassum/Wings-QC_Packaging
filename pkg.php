


<?php
include 'config.php';  
include 'session.php';
include 'header.php';

function debug($arg)
{
    echo '<pre>';
    print_r($arg);
    echo '<pre>';
    exit;
}

$userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and merchant = 'Y'"));
   if ($userPrivCheckRow['merchant'] != 'Y'){
       exit();
   }

//get all merchants
function get_all_merchants()
{

    global $conn;
    $sql = "SELECT `merchant_id`, `merchantCode`, `merchantName` FROM `tbl_merchant_info` WHERE `isActive`= 'Y' ";
    $merchantResult = mysqli_query($conn, $sql);

    if (mysqli_num_rows($merchantResult)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($merchantResult)) {
            $inv .= '<option data-mName="' . $row['merchantName'] .'"  value="' . $row['merchantCode'] .'">' . $row['merchantName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}

//get all orders related to merchant id
$merchantCode = $_POST['mName'];
$sql = "SELECT * FROM `tbl_order_details` where `merchantCode` = '$merchantCode' ";
$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));


if(mysqli_num_rows($result) > 0){
 $resultOrders = mysqli_fetch_array($result);
} else {
    echo 'nothing';
}



?>

<script>
 /*     $( document ).ready(function() {
           if( $("#mName").val() == '') {  
              }
              else{
                $("#merchantOrderList").show();
      }
    });  
 */

    
   /*  function fetchOrders(val)
           {
               $.ajax({
                   type: 'post',
                   url: 'fetch_mOrders.php',
                   data: {
                    get_merchantId: val
                   },
                   success: function (response)
                   {
                       document.getElementById("orderid").innerHTML = response;
                   }
               });
           } */

           function fetch_mName(){
            var index = document.getElementById("mName").selectedIndex;

            var mn = document.getElementById("mName").options[index].getAttribute("data-mName");

            document.getElementsByName("mn")[0].value = mn;
           }
  </script>


    

    <!-- page content -->


    <div class="pg_title">
    <h4>Product Packaging</h4>
    </div>
    <div class="form-content">
        <div class="form-wrapper">
            <form  action="product_packaging_fulfillment.php" method="POST">
                <div class="search_merchant">
                    <table>
                    <tbody>
                        <tr>
                        <td><label class="pkgForm_label">Select Merchant</label></td>
                        <td>
                            <select  autofocus style="width: 100%;"  name="mName" id="mName"  onchange="fetch_mName()"  required>
                            <option  data-mName="" value="<?php echo isset($_POST['mName']) ? $_POST['mName'] : '' ?>">Select Merchant</option>
                            <?=get_all_merchants()?>
                            </select>
                        </td>
                        <td>
                        <input name="mn" id="mn" type="hidden" value="<?php echo isset($_POST['mn']) ? $_POST['mn'] : ''?>" readonly>
                        </td>
                        </tr>
                        <tr>
                        <td> <input class="btn btn-demo btn-ok" type="submit" name="submitSelection" id="submitSelection" value="Enter" ></td>
                        </tr>
                    </tbody>
                    </table>
                </div>  
            </form>        
        </div>                   
    </div>
        


    
                        <!--selected merchant's orders' table-->

     <div class="col-sm-12">
     	 <div class="row" style="margin-top: 15px; margin-left: 1%">
                 <div class="col-sm-12" id="ordersTable">

                 </div>
         </div>
     </div>
<!--     <div  class="merchantOrders" id="merchantOrderList"> 
        <h4> Orders from <?php echo isset($_POST['mn']) ? $_POST['mn'] : ''?></h4>
        <table>
            <tbody>
            <tr>
                <th>OrderID</th>
                <th>Merchant Ref</th>
                <th>Product Brief</th>
                <th>Scan</th>
                </tr>
            <?php
                    foreach($result  as $orderlist){
                        echo'<tr>';         
                        echo '<td name="orderidp">'.$orderlist['orderid'].'</td>';
                        echo '<td>'.$orderlist['merOrderRef'].'</td>';
                        echo '<td>'.$orderlist['productBrief'].'</td>';
                        echo '<td><button class="btn btn-primary" id="btnPrintpkg" data-id="'.$orderlist['orderid'].'">Scan</button></td>';
                        echo'</tr>';  
            }
                ?>
            </tbody>
        </table>
    </div> -->

       <!-- Measurement modal -->
       <div id="scanpkgModal" class="modal prod_records_modal" style="width: auto">
            <!-- Modal content -->
            <div id="modal-content"  class="modal-content modal-dialog modal-pkgCount" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'" id="headerID">Create product records</h3>
                </div>
                <div class="modal-body modal-body-p_pkg" style="text-align: center">
          
                <form id="scanpkgForm"  method="POST">
                <div  class="prod_info_tbl_two">  <table>

              
                              <tbody>
                              <tr>
                              <td><label class="pkgForm_label">Order ID</label></td>
                            
                              <td><input id="order_id"></td>
                              </tr>
                              <tr>
                              <td colspan="2"> <input class="btn btn-primary" type="submit" name="submit_pkgScan" value="Submit" id="submit_pkgScan">
                             </td>
                        
                              </tr>
                              </tbody>
               
                              </table>
                            </div>   
                          </form>
                  <!--   <button id="btnSave" class="btn btn-primary">Save</button>
                    <button id="btnCancel" class="btn btn-default">Cancel</button> -->
                </div>
                </div>
            </div>
        </div>
  <!-- /Measurement modal -->
     <!-- /page content -->
      
<script>
   $(document).on('click', '#btnPrintpkg', function(){ 
 
    var messageModal = document.getElementById("scanpkgModal");
    messageModal.style.display = "block";
    var orderid = $(this).attr("data-id");
    alert(orderid);
    $("#order_id").val(orderid);
    var span = document.getElementsByClassName("close")[0];
      span.onclick = function ()
    {
        messageModal.style.display = "none";
    }

});


  </script>