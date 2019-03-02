<?php
    if (isset($_GET['xxCode'])){
        include('config.php');
        include('session.php');
        $merchantCode = $_GET['xxCode'];
        $output .= '';
        $filename = 'pickUP '.date('dmy');
        $apiOrdersResult = mysqli_query($conn, "select tbl_api_orders.*, tbl_pickup_merchant_info.pickMerchantName, tbl_pickup_merchant_info.pickMerchantAddress, tbl_pickup_merchant_info.phone1, tbl_pickup_merchant_info.phone2 from tbl_api_orders left join tbl_pickup_merchant_info on tbl_pickup_merchant_info.pickMerchantID = tbl_api_orders.pickUpMerchantID where tbl_api_orders.merchantCode = '$merchantCode' and processed = 'N'");
        $output .=  '<table id="orderPullTable" class="table table-hover" style="font-size: 0.7em">';
            $output .=  '<thead><tr><th>Pick Point</th><th>Pickup Merchant</th><th>Pickup Merchant Address</th><th>Pickup Merchant Phone</th><th>Order ID</th><th>Drop Point</th><th>Destination Type</th><th>Name</th><th>Address</th><th>Phone</th><th>Package Price</th><th>Package Type</th><th>Delivery Type</th><th>Product Type</th><th>Status</th></tr></thead></tbody>';
            foreach($apiOrdersResult as $apiOrderRow){
                $output .=  '<tr id="'.$apiOrderRow['apiOrderID'].'">';
                    $output .=  '<td>'.$apiOrderRow['pickPointCode'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['pickMerchantName'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['pickMerchantAddress'].'</td>';
                    $output .=  '<td>"'.$apiOrderRow['phone1'].','.$apiOrderRow['phone2'].'"</td>';
                    $output .=  '<td>'.$apiOrderRow['merOrderRef'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['dropPointCode'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['destination'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['customerName'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['customerAddress'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['customerNumber'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['packagePrice'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['packageType'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['deliveryType'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['productType'].'</td>';
                    $output .=  '<td>'.$apiOrderRow['status'].'</td>';
                $output .=  '</tr>';
            }
        $output .=  '</tbody></table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');
        echo $output;
    }
?>


