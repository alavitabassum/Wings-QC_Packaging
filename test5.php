<?php
    $ts = strtotime('2017-4-1') + 21600;
    echo $ts;
    echo 'First Date    = ' . date('Y-m-d H:i:s')  . '<br />';
    echo 'Last Date     = ' . date('Y-m-t', $ts)  . '<br />';
    
    echo substr(date('Y-m-d', $ts),-2);

    echo "<br>";

                    $statementDate = new DateTime(date('Y-m-d', strtotime('+6 hour')));
                    $statementDate->sub(new DateInterval('P1D'));
                    $statementDate = date_format($statementDate, 'Y-m-d');

                    echo "Statement Date : ".$statementDate;

    echo "<br>";
    echo "MYSQL output in JSON format<br>";

    include_once('config.php');

    $sql = "select districtId from tbl_district_info";
    $reslult = mysqli_query($conn, $sql);
    $data ='';
    $i= 0;
    foreach($reslult as $row){
        $data .= 'row'+$i. json_encode($row);    
    }
    


    echo $data;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
