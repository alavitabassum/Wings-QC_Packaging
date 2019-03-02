
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


           function fetch_rack(val)
           {
               $.ajax({
                   type: 'post',
                   url: 'rackfetch.php',
                   data: {
                    get_rackid: val
                   },
                   success: function (response)
                   {
                       document.getElementById("inventory_racks").innerHTML = response;
                   }
               });
           }
</script> 

      <!-- page content -->
      <div class="pg_title">
        <h4>Create New Bin For Inventory</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                          <h4>Create Bin </h4>
                              <form action="new_bin_inv.php" method="post">

                                  <select name="inventory" id="inventory"  onchange="fetch_select(this.value);" required>
                                  <option value="">Select Inventory</option>
                                  <?=get_all_inventory()?>
                                  </select>

                                  <select name="inventory_lines" id="inventory_lines"  onchange="fetch_rack(this.value);" required>
                                  <option data-pointCode="" value="">Select Line</option>
                                  </select>

                                  <select name="inventory_racks" id="inventory_racks"  onchange="myFunctionfetch()" required>
                                  <option data-rackName="" data-lineName="" data-invName=""  data-pointCode="" value="">Select Rack</option>
                                  </select>

                                  <input name="invName" id="invName" type="hidden" readonly>
                                  <input name="lineName" id="lineName" type="hidden" readonly>
                                  <input name="rackName" id="rackName" type="hidden" readonly>
                                  <input name="pointCode" id="pointCode" type="hidden" readonly>
                                  <input type="text" name="bin_name" placeholder="Bin name">
                                  <input type="number" step=".01" name="bin_length" placeholder="Bin Length (ft)">
                                  <input type="number" step=".01" name="bin_width" placeholder="Bin Width (ft)">
                                  <input type="number" step=".01" name="bin_height" placeholder="Bin Height (ft)">
                              
                                  
                                  <input class="btn btn-demo btn-ok" type="submit" name="submit" value="Create Bin">
                               </form>
                        </div>
                </div>
      <!-- /page content -->

      <script>
      function myFunctionfetch() {

        var index = document.getElementById("inventory_racks").selectedIndex;
        //alert(index);
        var invName = document.getElementById("inventory_racks").options[index].getAttribute("data-invName");
        var lineName = document.getElementById("inventory_racks").options[index].getAttribute("data-lineName");
        var rackName = document.getElementById("inventory_racks").options[index].getAttribute("data-rackName");
        var pointCode = document.getElementById("inventory_racks").options[index].getAttribute("data-pointCode");

        document.getElementsByName("invName")[0].value = invName;
        document.getElementsByName("lineName")[0].value = lineName;
        document.getElementsByName("rackName")[0].value = rackName;
        document.getElementsByName("pointCode")[0].value = pointCode;
      }

    </script>

<?php
     
     $inventory = $_POST['inventory'];
     $inventoryName = $_POST['invName'];
     $inventory_lines = $_POST['inventory_lines'];
     $lineName = $_POST['lineName'];
     $inventory_racks = $_POST['inventory_racks'];
     $rackName = $_POST['rackName'];
     $pointCode = $_POST['pointCode'];
     $bin_name = $_POST['bin_name'];
     $bin_length = $_POST['bin_length'];
     $bin_width = $_POST['bin_width'];
     $bin_height = $_POST['bin_height'];
     $user_check = $_SESSION['login_user'];

     $binVolume_cubic_ft = $bin_length * $bin_width * $bin_height ;

     $sql = '';
 
     if (!empty($inventory) and !empty($inventory_lines) and !empty($inventory_racks) and !empty($pointCode) and !empty($bin_name) and
      !empty($bin_length)and !empty($bin_width) and !empty($bin_height) ){

       $sql = "INSERT INTO `tbl_inventory_bins`( `inventoryCode`, `lineCode`,`rackCode`,`pointCode`,`binName`,`binLength`,`binWidth`,`binHeight`,`binVolume_cubic_ft`,`created_by`,`inventoryName`,`lineName`,`rackName`,`binVol_remaining`) 
       VALUES ('$inventory','$inventory_lines', '$inventory_racks','$pointCode','$bin_name','$bin_length','$bin_width','$bin_height','$binVolume_cubic_ft','$user_check','$inventoryName','$lineName','$rackName','$binVolume_cubic_ft')";
     }
 
     mysqli_query($conn, $sql);
 
     if (mysqli_affected_rows($conn)) {
         return true;
     }
     return false;
    ?>





