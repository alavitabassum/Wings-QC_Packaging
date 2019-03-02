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

//get barcode realated info from database
$barcode = $_POST['input_barcode'];
$sql = "SELECT * FROM `tbl_barcode_factory_fulfillment` where `barcodeNumber` = '$barcode' ";
$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));


if(mysqli_num_rows($result) > 0){
 $result2 = mysqli_fetch_array($result);
} else {
    echo '';
}

function get_all_bins()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_inventory_bins`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option data-binVol="' . $row['binVolume_cubic_ft'] .'"   data-binName="' . $row['binName'] .'"  data-rackName="' . $row['rackName'] .'"  data-lineName="' . $row['lineName'] .'"  data-invName="' . $row['inventoryName'] .'"  data-pointCode="' . $row['pointCode'] . '"  value="' . $row['id'] .'">' . $row['binName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}

?>

<script>
     $( document ).ready(function() {
           if( $("#barcode_hidden_field").val() == '') {  
              }
              else{
                $("#measureform").show();

                  /* if($("#pickedqty").val() == '1' || $("#pickedqty").val() == '0' ){
                    $("#measureform").show();
                  }else{
                    var messageModal = document.getElementById('messageModal');
                    messageModal.style.display = "block";
                    $("#measureform").show();
                  }
        */
      }
    });
                 


    
     
  </script>



      <!-- page content -->


      <!-- Measurement modal -->
      <div id="messageModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="modal-content"  class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'" id="headerID">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">

                <form id="childBarcodeForm"  method="POST">
                <div  class="prod_info_tbl_two">  <table>

              
                              <tbody>
                              <tr>
                              <td><label class="barcodeForm_label">Barcode Number</label></td>
                              <td><input type="number" name="prod_barcode_parent"  id="barcode_input" required value="<?php echo isset($_POST['prod_barcode']) ? $_POST['prod_barcode'] : ''?>" readonly></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Received Quantity</label></td>
                              <td> <input type="number" name="prod_count" required placeholder="Quantity"  value="<?php  echo $result2['picked_qty'] ; echo isset($_POST['prod_qty']) ? $_POST['prod_qty'] : '' ?>" readonly></td>
                              </tr>

                              <tr>
                              <td><input type="number" name="child_barcode" required placeholder="Product Barcode" value="<?php echo isset($_POST['child_barcode']) ? $_POST['child_barcode'] : '' ?>" ></td>
                              </tr>
                              <tr>
                              <td colspan="2"> <input class="btn btn-primary" type="submit" name="submitchildBarcodes" value="Submit" id="submitchildBarcodes">
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

      <div class="pg_title">
        <h4>Product Measurement</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                          
                              <form  action="view_product_measure_fulfillment.php" method="POST">
                          <div class="prod_info_tbl_one">
                             <table>
                              <tbody>
                              <tr>
                              <td> <label class="barcodeForm_label" style="font-weight:700;">Enter Barcode Number</label></td>
                              <td> <input type="number" name="input_barcode" placeholder="Barcode number" id="barcode_num" requiredvalue="<?php echo isset($_POST['input_barcode']) ? $_POST['input_barcode'] : '' ?>" ></td>
                              </tr>
                              <tr>
                              <td> <input class="btn btn-demo btn-ok" type="submit" name="submitBarcode" id="submitBarcode" value="Enter" data-toggle="modal" data-target="#myModal" ></td>
                              <td><input type="hidden" name="b_field" id="barcode_hidden_field" value="<?php echo isset($_POST['input_barcode']) ? $_POST['input_barcode'] : '' ?>" /></td>
                              <td><input type="hidden" name="pickedqty" id="pickedqty" value="<?php  echo $result2['picked_qty'] ;  echo isset($_POST['picked_qty']) ? $_POST['picked_qty'] : '' ?>" /></td>
                              </tr>
                              </tbody>
                              </table>
                          </div>
                              
                              </form>
                             
                           
                         

                              <form id="measureform"  action="view_product_measure_fulfillment.php" method="POST" style="display:none;">
                                <!--Start Show details Data-->

                                <div  class="prod_info_show_tbl">
                                     <table>
                                        <tbody>
                                        <tr>
                                        <th style="padding:10px; font-weight:700;" > <label class="barcodeForm_label" style=" font-weight:700;">Order ID:</label></th>
                                        <td><?php echo $result2['order_id'] ?></td>

                                        </tr>
                                        <tr>
                                        <th colspan="2" style="padding:10px;"> <label class="barcodeForm_label" style=" font-weight:700;">Pick-up Details:</label></th>
                                        </tr>
                                        <tr> 
                                           
                                            <td> <label class="barcodeForm_label">Merchant Code: </label></td>
                                            <td><?php echo $result2['merchant_code'] ?></td>
                                        </tr>
                                        <tr>
                                            <td> <label class="barcodeForm_label">Sub Merchant Name: </label></td>
                                            <td><?php echo $result2['sub_merchant_name'] ?></td>
                                        </tr>
                                        <tr>
                                        <td> <label class="barcodeForm_label">Picked Quantity: </label></td>
                                            <td><?php echo $result2['picked_qty'] ?></td>
                                        </tr>
                                        
                                        </tbody>
                                     </table>
                                </div>
                               
                           <!--End show details data -->
                              <div  class="prod_info_tbl_two">  <table>
                              <tbody>
                              <tr>
                              <td><label class="barcodeForm_label">Barcode Number</label></td>
                              <td><input type="number" name="prod_barcode"  id="barcode_input" required value="<?php echo $result2['barcodeNumber'] ; echo isset($_POST['prod_barcode']) ? $_POST['prod_barcode'] : ''?>" readonly></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label" id="barcodeForm_label">Enter Length</label></td>
                              <td><input type="number" name="prod_length" required placeholder="Length"  id="barcodeForm_input" step=".01" value="<?php echo isset($_POST['prod_length']) ? $_POST['prod_length'] : '' ?>"></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Width</label></td>
                              <td><input type="number" name="prod_width" required placeholder="Width" step=".01"  value="<?php echo isset($_POST['prod_width']) ? $_POST['prod_width'] : '' ?>"> </td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Height</label></td>
                              <td><input type="number" name="prod_height" required placeholder="Height" step=".01" value="<?php echo isset($_POST['prod_height']) ? $_POST['prod_height'] : '' ?>" ></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Weight</label></td>
                              <td><input type="number" name="prod_weight" required placeholder="Weight" step=".01" value="<?php echo isset($_POST['prod_weight']) ? $_POST['prod_weight'] : '' ?>" ></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Received Quantity</label></td>
                              <td> <input type="number" name="prod_qty" required placeholder="Quantity"  value="<?php  echo $result2['picked_qty'] ; echo isset($_POST['prod_qty']) ? $_POST['prod_qty'] : '' ?>"  ></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Return Quantity</label></td>
                              <td> <input type="number" name="prod_return_qty" placeholder="Return Quantity"  value="<?php echo isset($_POST['prod_return_qty']) ? $_POST['prod_return_qty'] : '' ?>"  ></td>
                              </tr>
                              
                              <tr>
                              <td><label class="barcodeForm_label">Select Bin</label></td>
                              <td>
                              <select name="inventory_bins" id="inventory_bins"  onchange="FetchBinInfo()" required>
                                  <option data-binVol=""   data-binName="" data-rackName="" data-lineName="" data-invName=""  data-pointCode="" value="">Select Bin</option>
                                  <?=get_all_bins()?>
                                  </select>
                              </td>
                              </tr>

                              <tr>
                              <td>
                              <input name="invName" id="invName" type="hidden" readonly>
                                  <input name="lineName" id="lineName" type="hidden" readonly>
                                  <input name="rackName" id="rackName" type="hidden" readonly>
                                  <input name="binName" id="binName" type="hidden" readonly>
                                  <input name="pointCode" id="pointCode" type="hidden" readonly>
                                  <input name="binVolume_cubic_ft" id="binVolume_cubic_ft" type="hidden" readonly>
                                  <h4 name="binchk" id="binchk"> </h4>
                               
                              </td>
                              </tr>
                              <tr>
                              <td colspan="2"> <input class="btn btn-demo btn-ok" type="submit" name="submitMeasurements" value="Submit" id="submitMeasurements">
                             </td>
                              </tr>
                              </tbody>
                              </table></div>
                             
                               </form>
                        </div>
                     
        </div>
        <script>
      function FetchBinInfo() {

        var index = document.getElementById("inventory_bins").selectedIndex;
        //alert(index);
        
        var invName = document.getElementById("inventory_bins").options[index].getAttribute("data-invName");
        var lineName = document.getElementById("inventory_bins").options[index].getAttribute("data-lineName");
        var rackName = document.getElementById("inventory_bins").options[index].getAttribute("data-rackName");
        var binName = document.getElementById("inventory_bins").options[index].getAttribute("data-binName");
        var pointCode = document.getElementById("inventory_bins").options[index].getAttribute("data-pointCode");
        var binVolume_cubic_ft = document.getElementById("inventory_bins").options[index].getAttribute("data-binVol");
        
        document.getElementsByName("invName")[0].value = invName;
        document.getElementsByName("lineName")[0].value = lineName;
        document.getElementsByName("rackName")[0].value = rackName;
        document.getElementsByName("binName")[0].value = binName;
        document.getElementsByName("pointCode")[0].value = pointCode;
        document.getElementsByName("binVolume_cubic_ft")[0].value = binVolume_cubic_ft;

      }

    </script>
        <?php
     if(isset($_POST['submitMeasurements'])){

      $prod_barcode = $_POST['prod_barcode'];
      $prod_length = $_POST['prod_length'];
      $prod_width = $_POST['prod_width'];
      $prod_height = $_POST['prod_height'];
      $prod_weight = $_POST['prod_weight'];
      $prod_qty = $_POST['prod_qty'];
      $prod_return_qty = $_POST['prod_return_qty'];
      $prod_bin_id = $_POST['inventory_bins'];
      $prod_bin_name = $_POST['binName'];
      $prod_rack_name = $_POST['rackName'];
      $prod_line_name = $_POST['lineName'];
      $prod_inv_name = $_POST['invName'];
      $inv_point = $_POST['pointCode'];
      $user_check = $_SESSION['login_user'];
      $binVol = $_POST['binVolume_cubic_ft'];
      $prod_vol = $prod_length * $prod_width * $prod_height;
      $remainingSpace = $binVol -$prod_vol;

      
  
      $sql = '';

  if ($prod_qty == 1){

            if (!empty($prod_barcode) and !empty($prod_length) and !empty($prod_width) and !empty($prod_height)and !empty($prod_weight)and !empty($prod_qty) and !empty($prod_return_qty))
            {
                if($prod_vol < $binVol || $prod_vol < $remainingSpace){

                    $sql = "UPDATE `tbl_barcode_factory_fulfillment` SET `prod_length`='$prod_length',`prod_width`='$prod_width',`prod_height`='$prod_height',`prod_weight`='$prod_weight',
                    `prod_qty`='$prod_qty', `prod_return_qty`='$prod_return_qty', `state`= 2, 
                    `prod_vol`='$prod_vol',`prod_bin_no`='$prod_bin_id',`prod_bin_name`='$prod_bin_name',`prod_rack_name`='$prod_rack_name',
                    `prod_line_name`='$prod_line_name',`prod_inv_name`='$prod_inv_name',`prod_inv_pointCode`='$inv_point',`updated_by`='$user_check' WHERE `barcodeNumber`='$prod_barcode'";
                
                }else if($prod_vol == $binVol || $prod_vol > $remainingSpace || $prod_vol == $remainingSpace || $prod_vol > $binVol){
                    echo ' <script>
                    $( document ).ready(function() {
                        
                        $("#measureform").show();
                
                    });
                    alert("Form not submitted"); 
                    </script>';
                }
            
        }
        else if (!empty($prod_barcode) and !empty($prod_length) and !empty($prod_width) and !empty($prod_height)and !empty($prod_weight)and !empty($prod_qty) and empty($prod_return_qty))
            {
                if($prod_vol < $binVol || $prod_vol < $remainingSpace){
                $sql = "UPDATE `tbl_barcode_factory_fulfillment` SET `prod_length`='$prod_length',`prod_width`='$prod_width',`prod_height`='$prod_height',`prod_weight`='$prod_weight',`prod_qty`='$prod_qty', `prod_return_qty`= 0, `state`= 2 
                `prod_vol`='$prod_vol',`prod_bin_no`='$prod_bin_id',`prod_bin_name`='$prod_bin_name',`prod_rack_name`='$prod_rack_name',
                `prod_line_name`='$prod_line_name',`prod_inv_name`='$prod_inv_name',`prod_inv_pointCode`='$inv_point',`updated_by`='$user_check' WHERE `barcodeNumber`='$prod_barcode'";
                
                }else if($prod_vol == $binVol || $prod_vol > $remainingSpace || $prod_vol == $remainingSpace || $prod_vol > $binVol){
                echo ' <script>
                $( document ).ready(function() {
                    
                    $("#measureform").show();
            
                });
                alert("Form not submitted"); 
                </script>';
            }
            
        }

  }
  else if($prod_qty > 1)
  {

                if (!empty($prod_barcode) and !empty($prod_length) and !empty($prod_width) and !empty($prod_height)and !empty($prod_weight)and !empty($prod_qty) and !empty($prod_return_qty))
                {
                    if($prod_vol < $binVol || $prod_vol < $remainingSpace){

                        $sql = "UPDATE `tbl_barcode_factory_fulfillment` SET `prod_length`='$prod_length',`prod_width`='$prod_width',`prod_height`='$prod_height',`prod_weight`='$prod_weight',
                        `prod_qty`='$prod_qty', `prod_return_qty`='$prod_return_qty', `state`= 2, 
                        `prod_vol`='$prod_vol',`prod_bin_no`='$prod_bin_id',`prod_bin_name`='$prod_bin_name',`prod_rack_name`='$prod_rack_name',
                        `prod_line_name`='$prod_line_name',`prod_inv_name`='$prod_inv_name',`prod_inv_pointCode`='$inv_point',`updated_by`='$user_check' WHERE `barcodeNumber`='$prod_barcode'";
                    

                    /* echo '<script type="text/javascript">location.href = "messageModal";</script>'; */

                    echo ' <script>
                                $(function() {
                                    var messageModal = document.getElementById("messageModal");
                                    messageModal.style.display = "block";
                                    var span = document.getElementsByClassName("close")[0];
                                    span.onclick = function ()
                                    {
                                        messageModal.style.display = "none";
                                    }
                                });
                                $("#childBarcodeForm").on("submit", function(event){
                
                                    event.preventDefault();
            
                                $.ajax({
                                url:"childBarcode.php",
                                method:"POST",
                                data:$("#childBarcodeForm").serialize(),
                                beforeSend:function(){
                                        $("#submitchildBarcodes").val("Inserting");
                                },
                                success:function(data){
                                    var str = response;
                                    var n = str.search("Error");
                                        var submitCount = data;
                                        alert(submitCount);
                                        $("#childBarcodeForm")[0].reset();
                                        $("#messageModal").modal("hide");
                                        $("#submitchildBarcodes").val("Insert");
                                            }
                                        });
                                    
                                });
                    </script>';


                    }else if($prod_vol == $binVol || $prod_vol > $remainingSpace || $prod_vol == $remainingSpace || $prod_vol > $binVol){
                        echo ' <script>
                        $( document ).ready(function() {
                            
                            $("#measureform").show();
                    
                        });
                        alert("Form not submitted"); 
                        </script>';
                    }
                
            }
            else if (!empty($prod_barcode) and !empty($prod_length) and !empty($prod_width) and !empty($prod_height)and !empty($prod_weight)and !empty($prod_qty) and empty($prod_return_qty))
                {
                    if($prod_vol < $binVol || $prod_vol < $remainingSpace){
                    $sql = "UPDATE `tbl_barcode_factory_fulfillment` SET `prod_length`='$prod_length',`prod_width`='$prod_width',`prod_height`='$prod_height',`prod_weight`='$prod_weight',`prod_qty`='$prod_qty', `prod_return_qty`= 0, `state`= 2 
                    `prod_vol`='$prod_vol',`prod_bin_no`='$prod_bin_id',`prod_bin_name`='$prod_bin_name',`prod_rack_name`='$prod_rack_name',
                    `prod_line_name`='$prod_line_name',`prod_inv_name`='$prod_inv_name',`prod_inv_pointCode`='$inv_point',`updated_by`='$user_check' WHERE `barcodeNumber`='$prod_barcode'";
                    

                    // echo '<script type="text/javascript">location.href = "view_product_measure_multiple.php";</script>';

                    }else if($prod_vol == $binVol || $prod_vol > $remainingSpace || $prod_vol == $remainingSpace || $prod_vol > $binVol){
                    echo ' <script>
                    $( document ).ready(function() {
                        
                        $("#measureform").show();
                
                    });
                    alert("Form not submitted"); 
                    </script>';
                }
                
            }
      

  }
     
      mysqli_query($conn, $sql);
  
      if (mysqli_affected_rows($conn)) {
          return true;
  
      }
  
     
      return false;

    }else{
        header('location: view_product_measure_fulfillment.php?error=Error');
    
    }


  /*   if(isset($_POST['submitchildBarcodes'])){
     
    $prod_barcode = $_POST['prod_barcode_parent'];
    $prod_child_barcode = $_POST['child_barcode'];

    $sql = "INSERT INTO `tbl_child_barcode_fulfillement`(`childBarcode`, `parentBarcode`) VALUES ('$prod_child_barcode','$prod_barcode')";
       

        }else{
            header('location: view_product_measure_fulfillment.php?error=Error');

        } */
     ?>

     <script>
   
     </script>

     <!-- /page content -->