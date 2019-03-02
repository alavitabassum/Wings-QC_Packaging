<?php

include 'config.php';  



function debug($arg)
{
    echo '<pre>';
    print_r($arg);
    echo '<pre>';
    exit;
}


function add_new_inventory($invntry)
{

    global $conn;

    $invntry_name = $invntry['inventory_name'];
    $invntry_address = $invntry['inventory_address'];
    $invntry_size = $invntry['inventory_size'];
    $point = $invntry['inv_point'];
   

    $sql = '';

    if (!empty($invntry_name) and !empty($invntry_address) and !empty($invntry_size) and !empty($point)) {
        $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `inventory_address`, `size`, `pointCode`) VALUES ('$invntry_name','$invntry_address','$invntry_size','$point')";

    } else if (!empty($invntry_name) and !empty($invntry_address) and empty($invntry_size) and !empty($point)) {
        $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `inventory_address`,  `pointCode`) VALUES ('$invntry_name','$invntry_address','$point')";

    } else if (!empty($invntry_name) and empty($invntry_address) and !empty($invntry_size) and !empty($point)) {
        $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`,  `size`, `pointCode`) VALUES ('$invntry_name','$invntry_size','$point')";

    } else if (!empty($invntry_name) and empty($invntry_address) and empty($invntry_size) and !empty($point)) {
        $sql = "INSERT INTO `tbl_inventory_info`( `inventory_name`, `pointCode`) VALUES ('$invntry_name','$point')";
    }

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)) {
        return true;

    }

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
function get_all_inventoriesByName()
{

    global $conn;
    $sql = "SELECT * FROM `tbl_inventory_info`";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {

        $inv = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $inv .= '<option value="' . $row['inventory_name'] .'">' . $row['inventory_name'] . '</option>';

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
            $inv .= '<option value="' . $row['userName'] .'">' . $row['userName'] . '</option>';

        }
        //debug($inv);

        return $inv;
    }
}

function assign_inventory($invntry)
{

    global $conn;
    $invntry_name = $invntry['inventory_name'];
    $invntry_user = $invntry['inventory_user'];


    $sql = '';

    if (!empty($invntry_name) and !empty($invntry_user)) {
        $sql = "INSERT INTO `tbl_inventory_user`(`inventoryName`, `userName`) VALUES ('$invntry_name','$invntry_user')";

    } 

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)) {
        return true;

    }

    debug($user);
    //return false;
}

function delete_inventory($invntry)
{
    global $conn;
    $id = $invntry['inventory_id'];
    $sql = "DELETE FROM `tbl_inventory_info` WHERE  `id`= $id";

    mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn)) {
        return true;

    }
    return false;
}