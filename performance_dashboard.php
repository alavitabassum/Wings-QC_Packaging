<?php
    include('session.php');
    include('header.php');
    include('config.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and dashboard = 'Y'"));
    if ($userPrivCheckRow['dashboard'] != 'Y'){
        exit();
    }
?>

<div class="container-fluid" style="margin-left: 15px; clear: both">
    <div class="row">
        <div class="col-sm-2">

            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Without Status Oldest Order</b></p>
            <?php 
                $oldestSQL="SELECT orderDate, orderid, tbl_merchant_info.merchantName FROM `tbl_order_details` left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE close is null and Cash is null and Ret is null and partial is null order by orderDate, ordSeq desc limit 1";
                $oldestResult = mysqli_query($conn, $oldestSQL);
                $oldestRow = mysqli_fetch_array($oldestResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 20px 'paperfly roman'"><b><?php  echo $oldestRow['orderid'];?></b></p>
            <p style="width: 100%; text-align: center; color: #E37222; font: 14px 'paperfly roman'"><?php  echo $oldestRow['merchantName'];?></p>
        </div>
        <div class="col-sm-2">
            <p style="width: 100%; text-align: left; color:  #b3b3b3; font: 12px 'paperfly roman'"><b>Receice vs Delivery</b></p>
            <?php 
                $orderReceiveSQL="SELECT count(1) as orders FROM tbl_order_details WHERE DATE(orderDate) = SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY)";
                $orderReceiveResult = mysqli_query($conn, $orderReceiveSQL);
                $orderReceiveRow = mysqli_fetch_array($orderReceiveResult);
                $orderDeliverySQL="SELECT count(1) as delivery FROM `tbl_order_details` WHERE DATE_FORMAT(CashTime, '%Y-%m-%d') = SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY) or DATE_FORMAT(RetTime, '%Y-%m-%d') = SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY) or DATE_FORMAT(partialTime, '%Y-%m-%d') = SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY)";
                $orderDeliveryResult = mysqli_query($conn, $orderDeliverySQL);
                $orderDeliveryRow = mysqli_fetch_array($orderDeliveryResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><span style="padding-right: 15px; border-right-style: solid"><b><?php  echo $orderReceiveRow['orders'];?></b></span><span style="padding-left: 15px"><b><?php  echo $orderDeliveryRow['delivery'];?></b></span></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">Yesterday</p>
        </div>
        <div class="col-sm-1">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>On Hold</b></p>
            <?php 
                $orderonholdSQL="SELECT count(1) as onhold FROM tbl_order_details WHERE Rea = 'Y' and close is null and Cash is null and Ret is null and partial is null";
                $orderonholdResult = mysqli_query($conn, $orderonholdSQL);
                $orderonholdRow = mysqli_fetch_array($orderonholdResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><b><?php  echo $orderonholdRow['onhold'];?></b></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">As of Today</p>
        </div>
        <div class="col-sm-2">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Top Merchant</b></p>
            <?php 
                $topMerchantSQL="SELECT tbl_merchant_info.merchantName, count(1) as orders FROM tbl_order_details left join tbl_merchant_info on tbl_merchant_info.merchantCode = tbl_order_details.merchantCode WHERE DATE(orderDate) = SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY) group by tbl_merchant_info.merchantName order by orders desc";
                $topMerchantResult = mysqli_query($conn, $topMerchantSQL);
                $topMerchantRow = mysqli_fetch_array($topMerchantResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #EEAA7B; font: 15px 'paperfly roman'"><b><?php  echo $topMerchantRow['merchantName'].' ('.$topMerchantRow['orders'].')';?></b></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">Yesterday</p>
        </div>
        <div class="col-sm-2">

            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Oldest Order</b></p>
            <?php 
                $oldestSQL="SELECT orderDate, orderid, tbl_merchant_info.merchantName FROM `tbl_order_details` left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE close is null order by orderDate, ordSeq desc limit 1";
                $oldestResult = mysqli_query($conn, $oldestSQL);
                $oldestRow = mysqli_fetch_array($oldestResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 20px 'paperfly roman'"><b><?php  echo $oldestRow['orderid'];?></b></p>
            <p style="width: 100%; text-align: center; color: #E37222; font: 14px 'paperfly roman'"><?php  echo $oldestRow['merchantName'];?></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Orders missed SLA (Local only)</b></p>
            <?php 
                $missedSLASQL="SELECT count(1) as orders FROM tbl_order_details WHERE DATE(orderDate) < SUBDATE(DATE_FORMAT( NOW()+INTERVAL 6 HOUR, '%Y-%m-%d'), INTERVAL 1 DAY) and close is null and Cash is null and Ret is null and partial is null and destination = 'local'";
                $missedSLAResult = mysqli_query($conn, $missedSLASQL);
                $missedSLARow = mysqli_fetch_array($missedSLAResult);
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><b><?php  echo $missedSLARow['orders'];?></b></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">As of Today<span style="float: right">&nbsp;<a href="missed-sla">Export Detail</a></span></p>
        </div>
        <div class="col-sm-2">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Orders & Collection for banking </b></p>
            <?php 
                $orderBankSQL="SELECT orderid, CashAmt as collection FROM `tbl_order_details` WHERE (Cash = 'Y' and bank is null and close is null) or (partial = 'Y' and bank is null and close is null) having CAST(CashAmt as UNSIGNED) > 0 order by orderDate, dropPointCode";
                $orderBankResult = mysqli_query($conn, $orderBankSQL);
                $bankOders = 0;
                $bankCollection = 0;
                foreach($orderBankResult as $orderBankRow){
                    $bankOders ++;
                    $bankCollection = $bankCollection + $orderBankRow['collection'];
                }
            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><span style="padding-right: 15px; border-right-style: solid"><b><?php  echo $bankOders;?></b></span><span style="padding-left: 15px"><b><?php  echo num_to_format(round($bankCollection));?></b></span></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">As of Today<span style="float: right">&nbsp;<a href="Bank-Pending">Export Detail</a></span></p>
        </div>
        <div class="col-sm-2">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Orders ready for close </b></p>
            <?php 
                $orderTotalSQL="SELECT count(1) as orders FROM `tbl_order_details` WHERE close is null";
                $orderTotalResult = mysqli_query($conn, $orderTotalSQL);
                $orderTotalRow = mysqli_fetch_array($orderTotalResult);
                $orderCloseSQL="SELECT count(1) as orders FROM `tbl_order_details` WHERE (case when Cash = 'Y' then bank = 'Y' when Ret = 'Y' then retcp1 = 'Y' when partial = 'Y' then (bank = 'Y' and retcp1 ='Y') end) and close is null";
                $orderCloseResult = mysqli_query($conn, $orderCloseSQL);
                $orderCloseRow = mysqli_fetch_array($orderCloseResult);

            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><span style="padding-right: 15px; border-right-style: solid"><b><?php  echo $orderTotalRow['orders'];?></b></span><span style="padding-left: 15px"><b><?php  echo $orderCloseRow['orders'];?></b></span></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">As of Today</p>
        </div>
        <div class="col-sm-3">
            <p style="width: 100%; text-align: left; color: #b3b3b3; font: 12px 'paperfly roman'"><b>Orders ready for invoice </b></p>
            <?php 
                $orderInvSQL="SELECT count(1) as orders, sum(CashAmt) as collection FROM `tbl_order_details` WHERE invNum is null and close = 'Y'";
                $orderInvResult = mysqli_query($conn, $orderInvSQL);
                $orderInvRow = mysqli_fetch_array($orderInvResult);

            ?>
            <p style="width: 100%; overflow-x: hidden; text-align: center; color: #07889B; font: 22px 'paperfly roman'"><span style="padding-right: 15px; border-right-style: solid"><b><?php  echo $orderInvRow['orders'];?></b></span><span style="padding-left: 15px"><b><?php  echo num_to_format(round($orderInvRow['collection']));?></b></span></p>
            <p style="width: 100%; text-align: center; color: #b3b3b3; font: 14px 'paperfly roman'">As of Today</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <?php
                $merchantMonthSQL = "SELECT  count(DISTINCT merchantCode) as merchant, MONTHNAME(orderDate) as month, YEAR(orderDate) as year FROM `tbl_order_details` where DATE_FORMAT(orderDate, '%Y-%m') > DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 6 MONTH), '%Y-%m') group by MONTH(orderDate), YEAR(orderDate) order by DATE_FORMAT(orderDate, '%Y-%m') desc limit 6";
                $merchantMonthResult = mysqli_query($conn, $merchantMonthSQL);      
                $merMonthArray = array();
                while($monthRow =mysqli_fetch_assoc($merchantMonthResult))
                {
                    $merMonthArray[] = $monthRow;
                }
                $count = count($merMonthArray);
                $dayOrder = 6;
                $strMonthDpoints ='';
                for ($c=0; $c < $count; $c++) {
                    $dayOrder = $dayOrder -1; 
                    if($dayOrder != 0){
                        $strMonthDpoints = $strMonthDpoints.'{y:'.$merMonthArray[$dayOrder]['merchant'].', label: "'.$merMonthArray[$dayOrder]['month'].'" },';     
                    } else {
                        $strMonthDpoints = $strMonthDpoints.'{y:'.$merMonthArray[$dayOrder]['merchant'].', label: "'.$merMonthArray[$dayOrder]['month'].'" }';
                    }
                }
                $strMonthDpoints = "[".$strMonthDpoints."]";
            ?>
	        <div id="chartMonthlyMerchant" style="height: 300px; width: 100%;">
	        </div>
        </div>
        <div class="col-sm-6">
            <?php
                $merchantCountSQL = "SELECT concat('x: new Date(',YEAR(orderDate),',', MONTH(orderDate)-1,',',DAY(orderDate),'), y: ') as xVal , orderDate, count(1) as merchant from v_distinct_merchant group by orderDate order by orderDate desc limit 30";
                $merchantCountResult = mysqli_query($conn, $merchantCountSQL);      
                $merArray = array();
                while($row =mysqli_fetch_assoc($merchantCountResult))
                {
                    $merArray[] = $row;
                }
                $count = count($merArray);
                $dayOrder = 30;
                $strDpoints ='';
                for ($c=0; $c < $count; $c++) {
                    $dayOrder = $dayOrder -1; 
                    if($dayOrder != 0){
                        $strDpoints = $strDpoints."{".$merArray[$dayOrder]['xVal'].$merArray[$dayOrder]['merchant']." },";     
                    } else {
                        $strDpoints = $strDpoints."{".$merArray[$dayOrder]['xVal'].$merArray[$dayOrder]['merchant']." }";
                    }
                }
                $strDpoints = "[".$strDpoints."]";                  
            ?>
	        <div id="chartDailyMerchant" style="height: 300px; width: 100%;">
	        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php
                $merchantAvgSQL = "SELECT `yearMonth`, ROUND(avg(`merchant`)) as merchant FROM `v_daily_merchant` group by `yearMonth` order by `year` DESC, `month` DESC LIMIT 6";
                $merchantAvgResult = mysqli_query($conn, $merchantAvgSQL);      
                $merAvgArray = array();
                while($avgRow =mysqli_fetch_assoc($merchantAvgResult))
                {
                    $merAvgArray[] = $avgRow;
                }
                $count = count($merAvgArray);
                $dayOrder = 6;
                $strAvgDpoints ='';
                for ($c=0; $c < $count; $c++) {
                    $dayOrder = $dayOrder -1; 
                    if($dayOrder != 0){
                        $strAvgDpoints = $strAvgDpoints.'{y:'.$merAvgArray[$dayOrder]['merchant'].', label: "'.$merAvgArray[$dayOrder]['yearMonth'].'" },';     
                    } else {
                        $strAvgDpoints = $strAvgDpoints.'{y:'.$merAvgArray[$dayOrder]['merchant'].', label: "'.$merAvgArray[$dayOrder]['yearMonth'].'" }';
                    }
                }
                $strAvgDpoints = "[".$strAvgDpoints."]";
            ?>
	        <div id="chartMonthlyAvgMerchant" style="height: 300px; width: 100%;">
	        </div>
        </div>
        <div class="col-sm-6">
            <?php
                $merchantOrderSQL = "select MONTHNAME(orderDate) as month, YEAR(orderDate) as year, count(1) as orders from tbl_order_details group by MONTHNAME(orderDate), YEAR(orderDate) order by  DATE_FORMAT(orderDate, '%Y%m') desc limit 6";
                $merchantOrderResult = mysqli_query($conn, $merchantOrderSQL);      
                $merOrderArray = array();
                while($orderRow =mysqli_fetch_assoc($merchantOrderResult))
                {
                    $merOrderArray[] = $orderRow;
                }
                $count = count($merOrderArray);
                $dayOrder = 6;
                $strOrderDpoints ='';
                for ($c=0; $c < $count; $c++) {
                    $dayOrder = $dayOrder -1; 
                    if($dayOrder != 0){
                        $strOrderDpoints = $strOrderDpoints.'{y:'.$merOrderArray[$dayOrder]['orders'].', label: "'.$merOrderArray[$dayOrder]['month'].'" },';     
                    } else {
                        $strOrderDpoints = $strOrderDpoints.'{y:'.$merOrderArray[$dayOrder]['orders'].', label: "'.$merOrderArray[$dayOrder]['month'].'" }';
                    }
                }
                $strOrderDpoints = "[".$strOrderDpoints."]";
            ?>
	        <div id="chartOrderMerchant" style="height: 300px; width: 100%;">
	        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php
                $merchantTopSQL = "select tbl_merchant_info.merchantName, count(1) as orders from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where orderDate > DATE_SUB(CURDATE(), INTERVAL 180 DAY) group by tbl_merchant_info.merchantName order by orders desc limit 10";
                $merchantTopResult = mysqli_query($conn, $merchantTopSQL);      
                $merTopArray = array();
                while($monthRow =mysqli_fetch_assoc($merchantTopResult))
                {
                    $merTopArray[] = $monthRow;
                }
                $count = count($merTopArray);
                $dayOrder = 6;
                $strTopDpoints ='';
                for ($c=0; $c < $count; $c++) {
                    $dayOrder = $dayOrder -1; 
                    if($dayOrder != 0){
                        $strTopDpoints = $strTopDpoints.'{y:'.$merTopArray[$dayOrder]['orders'].', label: "'.$merTopArray[$dayOrder]['merchantName'].'" },';     
                    } else {
                        $strTopDpoints = $strTopDpoints.'{y:'.$merTopArray[$dayOrder]['orders'].', label: "'.$merTopArray[$dayOrder]['merchantName'].'" }';
                    }
                }
                $strTopDpoints = "[".$strTopDpoints."]";
            ?>
	        <div id="chartTopMerchant" style="height: 300px; width: 100%;">
	        </div>
        </div>
    </div>
</div>
	<script type="text/javascript">
    window.onload = function () {
	    var chart = new CanvasJS.Chart("chartDailyMerchant",
	    {

		    title:{
			    text: "Daily Merchant Count",
			    fontSize: 30
		    },
                    exportEnabled: true,
                        animationEnabled: true,
		    axisX:{

			    gridColor: "Silver",
			    tickColor: "silver",
			    valueFormatString: "DD/MMM"

		    },                        
                        toolTip:{
                            shared:true
                        },
		    theme: "theme2",
		    axisY: {
			    gridColor: "Silver",
			    tickColor: "silver"
		    },
		    legend:{
			    verticalAlign: "center",
			    horizontalAlign: "right"
		    },
		    data: [
		    {        
			    type: "line",
			    showInLegend: true,
			    lineThickness: 2,
			    name: "Merchant Count",
			    markerType: "square",
			    color: "#F08080",
			    dataPoints: <?php echo $strDpoints;?>
		    }			
		    ],
            legend:{
            cursor:"pointer",
            itemclick:function(e){
                if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
                }
                else{
                e.dataSeries.visible = true;
                }
                chart.render();
            }
            }
	    });
    chart.render();

    var chart2 = new CanvasJS.Chart("chartMonthlyMerchant",
    {
        title:{
        text: "Monthly Merchant Engagement"    
        },
        exportEnabled: true,
        animationEnabled: true,
        axisY: {
        title: "Monthly Count"
        },
        legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
        },
        theme: "theme2",
        data: [

        {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Monthly Count",
        dataPoints: <?php echo $strMonthDpoints;?>
        }   
        ]
    });
    chart2.render();

    var chart3 = new CanvasJS.Chart("chartMonthlyAvgMerchant",
    {
        title:{
        text: "Monthly Average Merchant Engagement"    
        },
        exportEnabled: true,
        animationEnabled: true,
        axisY: {
        title: "Monthly Average Count"
        },
        legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
        },
        theme: "theme2",
        data: [

        {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Monthly Average Count",
        dataPoints: <?php echo $strAvgDpoints;?>
        }   
        ]
    });
    chart3.render();

    var chart4 = new CanvasJS.Chart("chartOrderMerchant",
    {
        title:{
        text: "Monthly Orders Volume"    
        },
        exportEnabled: true,
        animationEnabled: true,
        axisY: {
        title: "Monthly Count"
        },
        legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
        },
        theme: "theme2",
        data: [

        {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Monthly Orders Volume",
        dataPoints: <?php echo $strOrderDpoints;?>
        }   
        ]
    });
    chart4.render();




}
</script>