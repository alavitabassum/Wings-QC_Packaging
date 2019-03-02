

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

//get all packaged orders
function get_all_packaging_products()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_packaging_records_fulfillment` GROUP BY `orderID` ";
    $pkgResult = mysqli_query($conn, $sql);

    if (mysqli_num_rows($pkgResult)) {

        $outputrow = '';
        while ($row = mysqli_fetch_assoc($pkgResult)) {
            $outputrow .= '<option data-pkgid="' . $row['id'] .'"   value="' . $row['orderID'] .'">' . $row['orderID'] . '</option>';

        }
        return $outputrow;
    }
}

if(isset($_GET['removeProduct']))
   {
    $delete_id =$_GET['removeProduct'];
    mysqli_query($conn,"DELETE FROM tbl_packaging_records_fulfillment where id = '$delete_id'");
    echo "<meta http-equiv='refresh' content='0;url=removePkgProduct.php'>";
  }
  


?>    

    <!-- page content -->


    <div class="pg_title">
    <h4>Remove Packaged Product</h4>
    </div>
    <div class="form-content">
        <div class="form-wrapper">
            <form  action="removePkgProduct.php" method="POST">
                <div class="search_merchant">
                    <table>
                    <tbody>
                        <tr>
                        <td><label class="pkgForm_label">Enter Order ID</label></td>
                        <td>
                            <select  autofocus style="width: 100%;"  name="pkgorderID" id="pkgorderID"   required>
                            <option  data-mName="" value="<?php echo isset($_POST['orderID']) ? $_POST['orderID'] : '' ?>">Enter Order ID</option>
                            <?=get_all_packaging_products()?>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" id="pkgID">
                                <input type="text" id="pkgproductBarcode">

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
     
     <!-- /page content -->

<script  type="text/javascript">

$('select').select2({ width: '200px'}); 


//fetch all packaged product for selected orderID

$('#pkgorderID').change(function ()
           {
               
               var pkgOID = $('#pkgorderID').val();
               console.log(pkgOID);

                $.ajax(
                   {
                       type: 'post',
                       url: 'findMerchantOrders.php',
                       data:
                       {
                           get_pkgOID: pkgOID,
                     
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
               
           });


               //show modal & pass order id to the modal
   $(document).on('click', '#btnDltpkg', function(){ 

  var pkgid = $(this).attr("data-id");
  var pkgpid = $(this).attr("data-barcode");
  $("#pkgID").val(pkgid);
  $("#pkgproductBarcode").val(pkgpid);


  var r = confirm("You are about to remove item from packaged product!");
  if (r == true) {
   // txt = "You pressed OK!";

               var pOID = $('#pkgID').val();
               var pkgPB =  $('#pkgproductBarcode').val();
               var pkgOID = $('#pkgorderID').val();
               console.log(pOID);

                $.ajax(
                   {
                       type: 'post',
                       url: 'findMerchantOrders.php',
                       data:
                       {
                           get_packageOrderID: pOID,
                           get_packageProductBarcode :pkgPB,
                           getOrderID :pkgOID,
                     
                       },
                       success: function (response)
                       {
                           var str = response;
                           var n = str.search("Error");
                           if (n < 0)
                           {
                               //alert(response);
                            $.notify("Product removed", "success");
                            //var pkgOID = $('#pkgorderID').val();
                                console.log(pkgOID);

                                    $.ajax(
                                    {
                                        type: 'post',
                                        url: 'findMerchantOrders.php',
                                        data:
                                        {
                                            get_pkgOID: pkgOID,
                                        
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
                           } else
                           {
                              
                            $.notify("Error", "warning");
                           }
                       }
                   });
  } else {
   // txt = "You pressed Cancel!";
  }
  //document.getElementById("demo").innerHTML = txt;





});
</script>