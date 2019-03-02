
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




function get_all_inventoriesByName()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_inventory_info`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option  data-inventoryid="' . $row['id'] .'" value="' . $row['inventory_name'] .'">' . $row['inventory_name'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}

function get_all_users()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_user_info`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option data-userName="' . $row['userName'] .'"  value="' . $row['user_id'] .'">' . $row['userName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}




?>

      <!-- page content -->
      <div class="pg_title">
        <h4>Assign Inventory</h4>
      </div>
      <div class="form-content">
                 
                        <div class="form-wrapper">
                   
                          <form action="inventory_assignment.php" method="post">
                          <select name="inventory_name" id="inventory_name" onchange="getInventoryID()">
                              <option data-inventoryid="" value="">Select a inventory</option>
                              <?=get_all_inventoriesByName()?>
                              </select>
                              <input name="invID" id="invID" type="hidden" readonly>
                              <select name="inventory_user" id="inventory_user" onchange="getUserID()">
                              <option data-userName=""   value="">Select a User</option>
                              <?=get_all_users()?>
                              </select>
                              <input name="userName" id="userName" type="hidden" readonly>
                              <input class="btn btn-demo btn-dlt" type="submit" name="submit_asgn" value="Assign Inventory">
                          </form>
                   </div>
                   
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
    

    if(isset($_POST['submit_asgn'])){
   
    $invntry_name = $_POST['inventory_name'];
    $invntry_userid = $_POST['inventory_user'];
    $invntry_userName = $_POST['userName'];
    $invntry_id = $_POST['invID'];


    $sql = '';

    if (!empty($invntry_name) and !empty($invntry_userid)) {
        $sql = "INSERT INTO `tbl_inventory_user`(`inventoryName`, `user_id`,`userName`,`inventory_id`) VALUES ('$invntry_name','$invntry_userid','$invntry_userName','$invntry_id')";

    } 

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)) {
        return true;

    }

    debug($user);
    //return false;
    }

    else{
        header('location: inventory_assignment.php?error=Error occured while assigning inventory');
    
    }

    
?>