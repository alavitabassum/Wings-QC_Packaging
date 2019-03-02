

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

/* if(isset($_POST["data-id"]))
{
     $query = "SELECT * FROM `tbl_order_details` WHERE `ordId` = '".$_POST["data-id"]."'";
     $result = mysqli_query($conn, $query);
     $row = mysqli_fetch_array($result);
     echo json_encode($row);
}
 */
?>



    

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
                            <select  autofocus style="width: 100%;"  name="mName" id="mName"   required>
                            <option  data-mName="" value="<?php echo isset($_POST['mName']) ? $_POST['mName'] : '' ?>">Select Merchant</option>
                            <?=get_all_merchants()?>
                            </select>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>  
            </form>        
        </div>                   
    </div>
        


    
                        <!--selected merchant's orders' table-->

          <div class="row" style="margin-top: 15px; margin-left: 1%">
                 <div class="col-sm-12  pkg_ord_tbl_div" id="ordersTable">

                 </div>
         </div>
     


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
                 <table>

              
                              <tbody>
                              <tr>
                              <td><label class="pkgForm_label">Order ID</label></td>
                              <td><input id="order_id" name="order_id" required readonly ></td>
                              <td><input type="hidden"  id="prod_qty" name="prod_qty" required readonly ></td>
                             
                              </tr>

                              <tr>
                              <td><label class="pkgForm_label">Enter Product Barcode</label></td>
                              <td><input autocomplete="off" required type="number" id="cp_barcode" name="cp_barcode" value="" ></td>
                              </tr>
                              <tr><td colspan="2"><span id="scanResult" class="scanResult"></span></td></tr>
                              <tr>  <td colspan="2">
                              <span id="pkgCount" class="pkgCount"></span>
                              <input style="width:50%;"  autocomplete="off" required type="hidden" id="pkgNumber" name="pkgNumber"  value=""  readonly >
                              
                              </td></tr>
                              <tr>
                              <td colspan="2"> 
                                  <!-- <button type="button" id="submit_pkgScan"  class="btn btn-primary" name="submit_pkgScan" >Search</button> -->
                                  <input class="btn  btn-primary" type="button" name="submit_pkgScan" value="Search" id="submit_pkgScan">
                                  <input class="btn btn-success" type="button" name="packProd" value="Accept" id="packProd">
                                <!--   <input class="btn btn-success" type="button" name="complete" value="Complete" id="complete"> -->
                             </td>
                          
                              </tr>
                              <tr>
                              <td>
                              <input  type="hidden" name="texthidden"  id="texthidden">
                              </td></tr>
                              </tbody>
               
                              </table>
                       
                          </form>
                  <!--   <button id="btnSave" class="btn btn-primary">Save</button>
                    <button id="btnCancel" class="btn btn-default">Cancel</button> -->
                </div>
                </div>
            </div>
        </div>
  <!-- /Measurement modal -->
     <!-- /page content -->


  <script type="text/javascript">


        $('select').select2({ width: '200px'}); 
        
        

     	//fetch all orders according to merchantCode

         $('#mName').change(function ()
           {
               
               var merchantCode = $('#mName').val();
               console.log(merchantCode);

                $.ajax(
                   {
                       type: 'post',
                       url: 'findMerchantOrders.php',
                       data:
                       {
                           get_merchantCode: merchantCode,
                     
                       },
                       success: function (response)
                       {
                           var str = response;
                           var n = str.search("Error");
                           if (n < 0)
                           {
                               $('#ordersTable').html('');
                               $('#ordersTable').html(response);
                        
                               $('#orderPullTable').DataTable();
                           } else
                           {
                              
                               $('#ordersTable').html('');
                           }
                       }
                   });
               
           })

    
    //show modal & pass order id to the modal
   $(document).on('click', '#btnPrintpkg', function(){ 
 
    var messageModal = document.getElementById("scanpkgModal");
    
    messageModal.style.display = "block";
    document.getElementById("packProd").disabled = true;
    $("#cp_barcode").focus();
    var orderid = $(this).attr("data-id");
    var prod_qty = $(this).attr("data-qty");
    $("#order_id").val(orderid);
    $("#prod_qty").val(prod_qty);
    $("#scanResult").html(" ");
    $("#pkgCount").html(" ");
   
});


//check child barcode exist
 
    // $(document).on('click', '#submit_pkgScan', function(){ 

      //  $("#scanpkgForm").on("submit", function(event){
        $(document).on('click','#submit_pkgScan',function(event) {
                event.preventDefault();
               // console.log( $("#scanpkgForm").serialize() );

               var barcode = $('#cp_barcode').val();
               var order_id = $('#order_id').val();
               console.log(barcode);
               
                $.ajax(
                   {
                       type: 'post',
                       url: 'findMerchantOrders.php',
                       data:
                       {
                           get_barcode: barcode,
                           get_order_id : order_id,
                        
                           
                       },
                       success: function (response)
                       {
                           var str = response;
                           var PackageFlag = 'PackageProduct';
                       
                           var n = str.search("Error");
                           if (n < 0)
                           {
                            var j = str.search("not");
                            if(j>0)
                            {
                                $("#scanResult").html("Product Not Found");
                                document.getElementById("cp_barcode").value = "";
                                $("#scanResult").css({ 'color': 'red', 'font-weight':'bold'});
                                document.getElementById("packProd").disabled = true;
                               // $("#scanpkgForm")[0].reset();
                            }
                            else{

                                $("#scanResult").html("Product Found");
                                $("#scanResult").css({ 'color': 'green', 'font-weight':'bold'});
                                document.getElementById("packProd").disabled = false;
                            }

                           } else
                           {
                            alert(response);
                           }
                       }
                   });
               
           });



         

//check child barcode exist

$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
    event.preventDefault();

    var barcode = $('#cp_barcode').val();
    var order_id = $('#order_id').val();
    console.log(barcode);
               
    $.ajax(
        {
            type: 'post',
            url: 'findMerchantOrders.php',
            data:
            {
                get_barcode: barcode,
                get_order_id : order_id,
            
                
            },
            success: function (response)
            {
                var str = response;
                var PackageFlag = 'PackageProduct';
            
                var n = str.search("Error");
                if (n < 0)
                {
                var j = str.search("not");
                if(j>0)
                {
                    $("#scanResult").html("Product Not Found");
                    document.getElementById("cp_barcode").value = "";
                    $("#scanResult").css({ 'color': 'red', 'font-weight':'bold'});
                    document.getElementById("packProd").disabled = true;
                    // $("#scanpkgForm")[0].reset();
                }
                else{

                    $("#scanResult").html("Product Found");
                    $("#scanResult").css({ 'color': 'green', 'font-weight':'bold'});
                    document.getElementById("packProd").disabled = false;
                }

                } else
                {
                alert(response);
                }
            
            }
          });
     }
});



//inser child barcodes orderid wise n unpdate 'packaged'flag.

 $(document).on('click','#packProd',function(event) {


    var pkg_prod_qty = $('#prod_qty').val();
    console.log( $("#scanpkgForm").serialize() );
    event.preventDefault();

    $.ajax({
    url:"findMerchantOrders.php",
    method:"POST",
    data:$("#scanpkgForm").serialize(),
    beforeSend:function(){
            $("#packProd").val("Inserting");
    },
    success:function(data){
        
        pkgCountNum = data;
        var n = pkgCountNum.search("Error");
        if (n < 0)
        {
           // alert(pkgCountNum);
           // alert(pkg_prod_qty);
           // $("#pkgNumber").val(pkgCountNum);
            $("#pkgCount").html("Product Count:" + pkgCountNum );
            $("#pkgCount").css({ 'color': 'blue', 'font-weight':'bold'});
            $.notify("Product packaged", "success");
            document.getElementById("cp_barcode").value = "";
            document.getElementById("pkgNumber").value = pkgCountNum;
            $("#packProd").val("Accept");
            document.getElementById("packProd").disabled = true;

        } else
        {
            //alert(data + "<=");
            $.notify("Product duplicate", "warning");
            $("#packProd").val("Accept");
            document.getElementById("cp_barcode").value = "";
            document.getElementById("packProd").disabled = true;
    
        }  
        
                var messageModal = document.getElementById("scanpkgModal");
                var span = document.getElementsByClassName("close")[0];
                span.onclick = function ()
                {
                    messageModal.style.display = "none";
                    $("#scanpkgForm")[0].reset();
                    //$("#pqty").html(pkgCountNum);
                   // $("#orderPullTable").load(" #orderPullTable");

                   var merchantCode = $('#mName').val();
                    console.log(merchantCode);

                $.ajax(
                   {
                       type: 'post',
                       url: 'findMerchantOrders.php',
                       data:
                       {
                           get_merchantCode: merchantCode,
                     
                       },
                       success: function (response)
                       {
                           var str = response;
                           var n = str.search("Error");
                           if (n < 0)
                           {
                               $('#ordersTable').html('');
                               $('#ordersTable').html(response);
                        
                               $('#orderPullTable').DataTable();
                           } else
                           {
                              
                               $('#ordersTable').html('');
                           }
                       }
                   });
                    
                }      


                }
            });
        
    });


                  
 
  </script>

  