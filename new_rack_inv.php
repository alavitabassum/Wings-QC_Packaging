
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

//get inventory

function get_all_inventory()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_inventory_info`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option value="' . $row['id'] .'">' . $row['inventory_name'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}
?>

<script>
    function fetch_select(val)
           {
               $.ajax({
                   type: 'post',
                   url: 'linefetch.php',
                   data: {
                    get_lineid: val
                   },
                   success: function (response)
                   {
                       document.getElementById("inventory_lines").innerHTML = response;
                   }
               });
           }
</script> 

<!-- 
<script>


    function myFunction(){
        var pointCode = $('#inventory').find(':selected').data('pointCode');
        $('#pointCode').val(pointCode);
      
    }
</script>
-->
      <!-- page content -->
      <div class="pg_title">
        <h4>Create New Rack For Inventory</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                          <h4>Create Rack </h4>
                              <form action="new_rack_inv.php" method="post">

                                  <select name="inventory" id="inventory"  onchange="fetch_select(this.value);" required>
                                  <option  value="">Select Inventory</option>
                                  <?=get_all_inventory()?>
                                  </select>

                                  <select name="inventory_lines" id="inventory_lines"  onchange="myFunctionfetch()" required>
                                  <option data-lineName="" data-invName="" data-pointCode="" value="">Select Line</option>
                                  </select>
                                  <input name="invName" id="invName" type="hidden" readonly>
                                  <input name="lineName" id="lineName" type="hidden" readonly>
                                  <input name="pointCode" id="pointCode" type="hidden" readonly>
                                  <input type="text" name="rack_name" placeholder="Rack name">
                                  <input type="number" step=".01" name="rack_length" placeholder="Rack Length (ft)">
                                  <input type="number" step=".01" name="rack_width" placeholder="Rack Width (ft)">
                                  <input type="number" step=".01" name="rack_height" placeholder="Rack Height (ft)">
                              
                                  
                                  <input class="btn btn-demo btn-ok" type="submit" name="submit" value="Create Rack">
                               </form>
                        </div>
                </div>
      <!-- /page content -->

      <script>
      function myFunctionfetch() {

        var index = document.getElementById("inventory_lines").selectedIndex;
        //alert(index);
        var invName = document.getElementById("inventory_lines").options[index].getAttribute("data-invName");
        var lineName = document.getElementById("inventory_lines").options[index].getAttribute("data-lineName");
        var pointCode = document.getElementById("inventory_lines").options[index].getAttribute("data-pointCode");

        document.getElementsByName("invName")[0].value = invName;
        document.getElementsByName("lineName")[0].value = lineName;
        document.getElementsByName("pointCode")[0].value = pointCode;
      }

    </script>

<?php
     
     $inventory = $_POST['inventory'];
     $inventoryName = $_POST['invName'];
     $inventory_lines = $_POST['inventory_lines'];
     $lineName = $_POST['lineName'];
     $pointCode = $_POST['pointCode'];
     $rack_name = $_POST['rack_name'];
     $rack_length = $_POST['rack_length'];
     $rack_width = $_POST['rack_width'];
     $rack_height = $_POST['rack_height'];
     $user_check = $_SESSION['login_user'];

     $rackSpace_cubic_ft = $rack_length * $rack_width * $rack_height ;
     $sql = '';
 
     if (!empty($inventory) and !empty($inventory_lines)and !empty($pointCode) and !empty($rack_name) and !empty($rack_length)and !empty($rack_width) ){
       $sql = "INSERT INTO `tbl_inventory_racks`( `inventoryCode`, `lineCode`,`pointCode`, `rackName`,`rackLength`,`rackWidth`,`rackHeight`,`rackSpace_cubic_ft`,`created_by`,`inventoryName`,`lineName`) VALUES ('$inventory','$inventory_lines','$pointCode','$rack_name','$rack_length','$rack_width','$rack_height','$rackSpace_cubic_ft','$user_check','$inventoryName','$lineName')";
     }
 
     mysqli_query($conn, $sql);
 
     if (mysqli_affected_rows($conn)) {
         return true;
     }
     return false;
    ?>





