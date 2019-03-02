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
$sql = "SELECT * FROM `tbl_order_details` where `barcode` = '$barcode' ";
$result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));


if(mysqli_num_rows($result) > 0){
 $result2 = mysqli_fetch_array($result);
 
    //close the db connection
  //  mysqli_close($conn);
} else {
    echo '';
}

//get merchant name from database
$mers = $result2['merchantCode'];

$sql2 = "SELECT * from tbl_merchant_info where merchantCode='$mers'";
$result3 = mysqli_query($conn,$sql2);

if(mysqli_num_rows($result3) > 0){
$result4 = mysqli_fetch_array($result3);

} else {
   echo '';
}
?>

<script>
     $( document ).ready(function() {
           if( $("#barcode_hidden_field").val() == '') {  
              }
              else{
          $("#measureform").show();
      }
    });

 /*    $(document).ready( function() {
    $('.prod_info_show_tbl').each( function() {
        if( $(this).find('td').length == 0 ) {
            $(this).hide();
        }
    });
});
     */                             
  </script>

      <!-- page content -->
      <div class="pg_title">
        <h4>Product Measurement</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                          
                              <form  action="view_product_measure.php" method="POST">
                          <div class="prod_info_tbl_one">
                             <table>
                              <tbody>
                              <tr>
                              <td> <label class="barcodeForm_label" style="font-weight:700;">Enter Barcode Number</label></td>
                              <td> <input type="number" name="input_barcode" placeholder="Barcode number" id="barcode_num" required></td>
                              </tr>
                              <tr>
                              <td> <input class="btn btn-demo btn-ok" type="submit" name="submitBarcode" id="submitBarcode" value="Submit"></td>
                              <td><input type="hidden" name="b_field" id="barcode_hidden_field" value="<?php echo isset($_POST['input_barcode']) ? $_POST['input_barcode'] : '' ?>" /></td>
                             
                              </tr>
                              </tbody>
                              </table>
                          </div>
                              
                              </form>
                             
                           
                         

                              <form id="measureform"  action="view_product_measure.php" method="POST" style="display:none;">
                                <!--Start Show details Data-->

                                <div  class="prod_info_show_tbl">
                                     <table>
                                        <tbody>
                                        <tr>
                                        <th style="padding:10px; font-weight:700;" > <label class="barcodeForm_label" style=" font-weight:700;">Order ID:</label></th>
                                        <td><?php echo $result2['ordId'] ?></td>

                                        </tr>
                                        <tr>
                                        <th colspan="2" style="padding:10px;"> <label class="barcodeForm_label" style=" font-weight:700;">Pick-up Details:</label></th>
                                        </tr>
                                        <tr> 
                                           
                                            <td> <label class="barcodeForm_label">Merchant Order Reference: </label></td>
                                            <td><?php echo $result2['merOrderRef'] ?></td>
                                        </tr>
                                        <tr>
                                            <td> <label class="barcodeForm_label">Merchant Code: </label></td>
                                            <td><?php echo $result2['merchantCode'] ?></td>
                                        </tr>
                                        <tr>
                                        <td> <label class="barcodeForm_label">Merchant Name: </label></td>
                                            <td><?php echo $result4['merchantName'] ?></td>
                                        </tr>
                                        <tr>
                                        <th colspan="2" style="padding:10px; font-weight:700;"> <label class="barcodeForm_label" style=" font-weight:700;">Customer Details:</label></th>
                                        </tr>
                                        <tr>
                                            <td> <label class="barcodeForm_label">Customer name: </label></td>
                                        <td><?php echo $result2['custname'] ?></td>
                                        </tr>
                                        <tr>
                                        <td> <label class="barcodeForm_label">Customer Phone: </label></td>
                                        <td><?php echo $result2['custphone'] ?></td>
                                        </tr>

                                        <tr>
                                        <td> <label class="barcodeForm_label">Customer Address: </label></td>
                                        <td><?php echo $result2['custaddress'] ?></td>
                                        </tr>

                                        <tr>
                                        <td> <label class="barcodeForm_label">Product Brief: </label></td>
                                        <td><?php echo $result2['productBrief'] ?></td>
                                        
                                        </tr>
                                        
                                        </tbody>
                                     </table>
                                </div>
                               
                           <!--End show details data -->
                              <div  class="prod_info_tbl_two">  <table>
                              <tbody>
                              <tr>
                              <td><label class="barcodeForm_label">Barcode Number</label></td>
                              <td><input type="number" name="prod_barcode"  id="barcode_input" required value=<?php echo $result2['barcode']?>></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label" id="barcodeForm_label">Enter Length</label></td>
                              <td><input type="number" name="prod_length" required placeholder="Length"  id="barcodeForm_input" step=".01"></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Width</label></td>
                              <td><input type="number" name="prod_width" required placeholder="Width" step=".01"> </td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Height</label></td>
                              <td><input type="number" name="prod_height" required placeholder="Height" step=".01"></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Weight</label></td>
                              <td><input type="number" name="prod_weight" required placeholder="Weight" step=".01"></td>
                              </tr>
                              <tr>
                              <td> <label class="barcodeForm_label">Enter Quantity</label></td>
                              <td> <input type="number" name="prod_qty" required placeholder="Quantity" ></td>
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

      <?php
     
      $prod_barcode = $_POST['prod_barcode'];
       $prod_length = $_POST['prod_length'];
       $prod_width = $_POST['prod_width'];
       $prod_height = $_POST['prod_height'];
       $prod_weight = $_POST['prod_weight'];
       $prod_qty = $_POST['prod_qty'];
   
       $sql = '';
   
       if (!empty($prod_barcode) and !empty($prod_length) and !empty($prod_width) and !empty($prod_height)and !empty($prod_weight)and !empty($prod_qty) ){
         /*   $sql = "INSERT INTO `tbl_order_details`(`barcode`, `prod_length`, `prod_width`, `prod_height`, `prod_weight`,`prod_qty`) VALUES ('$prod_barcode','$prod_length','$prod_width','$prod_height','$prod_weight','$prod_qty')"; */
         $sql = "UPDATE `tbl_order_details` SET `prod_length`='$prod_length',`prod_width`='$prod_width',`prod_height`='$prod_height',`prod_weight`='$prod_weight',`prod_qty`='$prod_qty' WHERE `barcode`='$prod_barcode'";
       }
   
       mysqli_query($conn, $sql);
   
       if (mysqli_affected_rows($conn)) {
           return true;
   
       }
   
      
       return false;

       
      ?>
 
        <script>


        </script>
      <!-- /page content -->