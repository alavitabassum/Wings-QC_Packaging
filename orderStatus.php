<?php
    include('config.php');
    //$deletePendingResult = mysqli_query($conn, "delete from tbl_pending_orders");

    //$deleteOnHoldPendingResult = mysqli_query($conn, "delete from tbl_onHoldPending_orders");

    $insertPendingOrdersResult = mysqli_query($conn, "insert into tbl_pending_orders SELECT NULL, dropPointCode, count(1) as pending, NOW() + INTERVAl 6 HOUR FROM `tbl_order_details` WHERE Shtl = 'Y' and Cash is null and Ret is null and partial is null and Rea is null and close is null group by dropPointCode");

    $insertOnHoldPendingResult = mysqli_query($conn, "insert into tbl_onHoldPending_orders SELECT NULL, tbl_order_details.dropPointCode, count(1) as onHoldPending, NOW() + INTERVAl 6 HOUR from tbl_order_details where DATE_FORMAT(tbl_order_details.orderDate,'%Y-%m-%d') < NOW() + INTERVAL 6 HOUR and (Rea = 'Y' and close is null and Cash is null and Ret is null and partial is null) group by  dropPointCode");

    $orderSLAMissedResult = mysqli_query($conn, "insert into tbl_sla_missed SELECT dropPointCode, count(1) as slaCnt, NOW() + INTERVAL 6 HOUR as creationDate, 'admin' as createdBy  FROM `tbl_order_details` WHERE Cash is null and Ret is null and partial is null and close is null and Shtl='Y' and orderDate < (DATE_SUB(curdate(), INTERVAL 1 DAY)) and dropPointCode in (select pointCode from tbl_point_info where sla = 1)  group by dropPointCode
union all
SELECT dropPointCode, count(1) as slaCnt, NOW() + INTERVAL 6 HOUR as creationDate, 'admin' as createdBy  FROM `tbl_order_details` WHERE Cash is null and Ret is null and partial is null and close is null and Shtl='Y' and orderDate < (DATE_SUB(curdate(), INTERVAL 2 DAY)) and dropPointCode in (select pointCode from tbl_point_info where sla = 2)  group by dropPointCode
union all
SELECT dropPointCode, count(1) as slaCnt, NOW() + INTERVAL 6 HOUR as creationDate, 'admin' as createdBy  FROM `tbl_order_details` WHERE Cash is null and Ret is null and partial is null and close is null and Shtl='Y' and orderDate < (DATE_SUB(curdate(), INTERVAL 3 DAY)) and dropPointCode in (select pointCode from tbl_point_info where sla = 3)  group by dropPointCode
");
?>


