
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
    //$user_check = $_SESSION['login_user'];
    $user_id_chk = $_SESSION['userId'];
    $sql = "SELECT tbl_inventory_info.id,tbl_inventory_info.pointCode, tbl_inventory_user.* FROM tbl_inventory_info left join tbl_inventory_user on tbl_inventory_info.id = tbl_inventory_user.inventory_id WHERE tbl_inventory_user.user_id = $user_id_chk";
  
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {

            $inv .= '<option data-pointCode="' . $row['pointCode'] .'" data-invName="' . $row['inventoryName'] .'"  value="' . $row['inventory_id'] .'">' . $row['inventoryName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}
?>



      <!-- page content -->
      <div class="pg_title">
        <h4>Create New Line For Inventory</h4>
      </div>
      <div class="form-content">
                        <div class="form-wrapper">
                          <h4>Create Line </h4>
                              <form action="new_line_inv.php" method="post">
                                    <select name="inventory" id="inventory" onchange="myFunction()" required>
                                  <option data-pointCode="" data-invName="" value="">Select Inventory</option>
                                  <?=get_all_inventory()?>
                                  </select>
                                  <input type="text" name="line_name" placeholder="Line name">
                                  <input name="invName" id="invName" type="hidden" readonly>
                                  <input name="pointCode" id="pointCode" type="hidden" readonly>
                              
                                  
                                  <input class="btn btn-demo btn-ok" type="submit" name="submit" value="Create Line">
                              </form>
                        </div>
                </div>
      <!-- /page content -->
      
      <script>
      function myFunction() {

        var index = document.getElementById("inventory").selectedIndex;
        //alert(index);
        var invName = document.getElementById("inventory").options[index].getAttribute("data-invName");
        var pointCode = document.getElementById("inventory").options[index].getAttribute("data-pointCode");

        document.getElementsByName("invName")[0].value = invName;
        document.getElementsByName("pointCode")[0].value = pointCode;
        

      }

    </script>

      <?php
     
      $inventory = $_POST['inventory'];
      $inventoryName = $_POST['invName'];
      $line_name = $_POST['line_name'];
      $pointCode = $_POST['pointCode'];
      $user_check = $_SESSION['login_user'];
      $sql = '';
  
      if (!empty($inventory) and !empty($line_name)and !empty($pointCode) ){
        $sql = "INSERT INTO `tbl_inventory_lines`( `inventoryCode`,`inventoryName`, `lineName`,`pointCode`,`created_by`) VALUES ('$inventory','$inventoryName','$line_name','$pointCode','$user_check')";
      }
  
      mysqli_query($conn, $sql);
  
      if (mysqli_affected_rows($conn)) {
          return true;
      }
      return false;
     ?>




