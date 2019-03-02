<?php
include('session.php');
include('config.php');
include('header.php');
$userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and smart_pick = 'Y'"));
   if ($userPrivCheckRow['smart_pick'] != 'Y'){
       exit();
   }



$sql = "SELECT * FROM tbl_merchant_fulfillment";
$resultset = mysqli_query($conn, $sql);

$sql2 = "SELECT name FROM tbl_submerchant";
$resultset2 = mysqli_query($conn,$sql2);
$datas = array();
if(mysqli_num_rows($resultset2)>0){
    while($row = mysqli_fetch_assoc($resultset2))
    {
        $datas[]=$row['name'];
        
    }
    
}


if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        
        $file = fopen($fileName, "r");
        $datas2 = array();
        $current_date = date("d-M-Y");

        
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if($row == 1){ $row++; continue; }
            $datas2[] = $column[0];
            $sqlInsert = "INSERT into tbl_fullfillment_pickuplist (main_merchant,supplier_name,supplier_phone,supplier_address,product_id,product_name,product_quantity,order_id,order_date,process_status_change_date,process_status,created_at)
                   values ('".$_POST["merchant"]."','" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "','" . $column[7] . "','" . $column[8] . "','" . $column[9] . "','".$current_date."')";
                   
                  

            /*$sqlInsert2 = "INSERT into submerchant (name)
                   values ('" . $column[0] . "')";
            mysqli_query($conn,$sqlInsert2);*/
            $result = mysqli_query($conn, $sqlInsert);
            
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
        
        $diff = array_unique(array_diff($datas2,$datas));
        //print_r($diff);
        if(empty($diff)){
        //echo "do nothing";
        }
        else{
        foreach ($diff as $row => $rows) {
        mysqli_query($conn,"INSERT INTO tbl_submerchant (name,main_merchant) VALUES ('$rows','".$_POST["merchant"]."')");
        }
        }

    }
}
?>
<style type="text/css">
   #response {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 2px;
    display:none;
}

.success {
    background: #c7efd9;
    border: #bbe2cd 1px solid;
}

.error {
    background: #fbcfcf;
    border: #f3c6c7 1px solid;
}

div#response.display-block {
    display: block;
    margin-left: 21px;
    margin-right: 23px;
}
</style>



<script type="text/javascript">
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>

<div style="background-color: #dad8d8">
            <p style="font-weight: bold; color: #808080">Fulfillment Service</p>
        </div>
        <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
        <div style="width: 100%; padding-left: 20px; padding-right: 20px; " >
            <form action="" method="post" enctype="multipart/form-data" style="width: 100%; border-color: #dad8d8; border-radius: 5px" name="frmCSVImport" id="frmCSVImport">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">Pickup List Upload</p>
                <select name = "merchant">
                    <?php
                     while ($rows = $resultset->fetch_assoc()) {
                         $merchant = $rows['name'];
                         $id = $rows['id'];
                         echo "<option value = '$merchant'>$merchant</option>";
                     }
                    ?>
                </select>
                <div class="input-row">
                     <input type="file" name="file"
                        id="file" accept=".csv" ><br>
                    <button style="width: 120px; border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25); color: #fff; height: 30px;text-shadow: 0 -1px 0 rgba(0,0,0,0.25); background-color:#006dcc; border-radius: 5%;float: left" type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>
               
                <!-- <div style="float: right">
                    <input type="submit" name="submit" value="Upload Orders" class="btn-primary" style="width: 120px; height: 30px; border-radius: 5%">&nbsp;
                    <br>
                    <br>
                </div> -->
                <br>
                <br>
            </form>
        </div>

  <!--   <h2>Import CSV file into Mysql using PHP</h2>
    
    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <select name = "merchant">
                    <?php
                     while ($rows = $resultset->fetch_assoc()) {
                         $merchant = $rows['name'];
                         $id = $rows['id'];
                         echo "<option value = '$merchant'>$merchant</option>";
                     }
                    ?>
                </select>
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="file"
                        id="file" >
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

        </div>
               <?php
            $sqlSelect = "SELECT * FROM tbl_fullfillment_pickuplist";
            $result = mysqli_query($conn, $sqlSelect);
            
            if (mysqli_num_rows($result) > 0) {
                ?>
            <table id='userTable'>
            <thead>
                <tr>
                   
                    <th>Main merchant</th>
                    <th>Suuplier Name</th>

                </tr>
            </thead>
<?php
                
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    
                <tbody>
                <tr>
                    <td><?php  echo $row['main_merchant']; ?></td>
                    <td><?php  echo $row['supplier_name']; ?></td>
                </tr>
                    <?php
                }
                ?>
                </tbody>
        </table>
        <?php } ?>
    </div> -->

</body>

</html>