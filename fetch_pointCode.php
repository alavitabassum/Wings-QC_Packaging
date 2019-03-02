<?php
    if(isset($_POST['get_thanaid'])){
        include('config.php');
        $thanaid = $_POST['get_thanaid'];
        $findsql="select tbl_point_coverage.pointCode, pointName from tbl_point_coverage, tbl_point_info 
                    where tbl_point_coverage.pointCode=tbl_point_info.pointCode and tbl_point_coverage.thanaid='$thanaid'";
        $findresult = mysqli_query($conn, $findsql);
        foreach ($findresult as $row){
            echo "<option value=".$row['pointCode'].">".$row['pointName']."</option>";
        }
        exit;
    }
?>