<?php
    if(isset($_POST['get_thanaid'])){
        include('config.php');
        $thanaid = $_POST['get_thanaid'];
        $findsql="select thanaid, thanaName from tbl_thana_info where districtid='$thanaid' and isActive = 'Y'";
        $findresult = mysqli_query($conn, $findsql);
        echo "<option></option>";
        foreach ($findresult as $row){
            echo "<option value=".$row['thanaid'].">".$row['thanaName']."</option>";
        }
        exit;
    }
?>