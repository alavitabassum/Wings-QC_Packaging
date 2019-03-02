
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

   function get_all_points()
{
    global $conn;
    $sql = "SELECT * FROM `tbl_point_info`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option value="' . $row['pointCode'] .'">' . $row['pointName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}

function get_all_inventories()
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

      <!-- page content -->
      <div class="pg_title">
        <h4>Create New Inventory</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                         
                              <form action="new_inventory.php" method="post">
                                  <input type="text" name="inventory_name" placeholder="Inventory name">
                                  <input type="text" name="inventory_address" placeholder="Inventory address">
                                  <input type="text" name="inventory_size" placeholder="Inventory area (sq ft)">
                                  <select name="inv_point">
                                  <option value="">Select a point</option>
                                  <?=get_all_points()?>
                                  </select>
                                  
                                  <input class="btn btn-demo btn-ok" type="submit" name="submit_inv" value="Add Inventory">
                              </form>
                        </div>
                       
                   <!--  <div class="form-wrapper">
                      <h3>Delete Inventory</h3>
                          <form action="delete_inventory.php" method="post">
                          <select name="inventory_id">
                              <option value="">Select a inventory to delete</option>
                              <?=get_all_inventories()?>
                              </select>
                              <input class="btn btn-demo btn-dlt" type="submit" name="submit_dlt" value="Delete Menu Item">
                          </form>
                   </div> -->
                </div>
      <!-- /page content -->
      <script>


        function getInventoryID() {

            var index = document.getElementById("inventory_name").selectedIndex;
            //alert(index);
            var invID = document.getElementById("inventory_name").options[index].getAttribute("data-inventoryid");
         

            document.getElementsByName("invID")[0].value = invID;
        
            }

      function getUserID() {

        var index = document.getElementById("inventory_user").selectedIndex;
        //alert(index);
        var userName = document.getElementById("inventory_user").options[index].getAttribute("data-userName");

        document.getElementsByName("userName")[0].value = userName;
      }

    </script>


<?php


    global $conn;
    
    if(isset($_POST['submit_inv'])){

        $invntry_name = $_POST['inventory_name'];
        $invntry_address = $_POST['inventory_address'];
        $invntry_size = $_POST['inventory_size'];
        $point = $_POST['inv_point'];
        $user_check = $_SESSION['login_user'];
       
    
        $sql = '';
    
        if (!empty($invntry_name) and !empty($invntry_address) and !empty($invntry_size) and !empty($point)) {
            $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `inventory_address`, `size_sq_feet`, `pointCode`, `created_by`) VALUES ('$invntry_name','$invntry_address','$invntry_size','$point','$user_check')";
    
        } else if (!empty($invntry_name) and !empty($invntry_address) and empty($invntry_size) and !empty($point)) {
            $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `inventory_address`,  `pointCode`, `created_by`) VALUES ('$invntry_name','$invntry_address','$point','$user_check')";
    
        } else if (!empty($invntry_name) and empty($invntry_address) and !empty($invntry_size) and !empty($point)) {
            $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`,  `size_sq_feet`, `pointCode`, `created_by`) VALUES ('$invntry_name','$invntry_size','$point','$user_check')";
    
        } else if (!empty($invntry_name) and empty($invntry_address) and empty($invntry_size) and !empty($point)) {
            $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `pointCode`, `created_by`) VALUES ('$invntry_name','$point','$user_check')";
        }
    
        mysqli_query($conn, $sql);
    
        if (mysqli_affected_rows($conn)) {
            return true;
    
        }
    
    }else{
        header('location: new_inventory.php?error=Error occured while creating inventory');
    
    }
   


    

    if(isset($_POST['submit_dlt'])){
    $id = $_POST['inventory_id'];
    $sql = "DELETE FROM `tbl_inventory_info` WHERE  `id`= $id";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)) {
        return true;

    }
    return false;
    }
    else{
        header('location: new_inventory.php?error=Error occured while deleting inventory');
    
    }

?>