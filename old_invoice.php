<?php
include('session.php');
include('num_format.php');
include('config.php');
$error=''; // Variable To Store Error Message
if (mysqli_connect_errno()){
    $error = "Failed to connect to Database: " .mysqli_connect_errno(). " - ". mysqli_connect_error();
} else {
    if(isset($_GET['xxCode'])){
        $invoiceNumber = trim($_GET['xxCode']);
        //$discAdj = trim($_GET['disc']);
        //$invComment1 = $_GET['invC1'];
        //$invComment2 = $_GET['invC2'];
        //$invComment3 = $_GET['invC3'];
        //$invComment4 = $_GET['invC4'];
        //$invComment5 = $_GET['invC5'];
        //$invComment6 = $_GET['invC6'];

        $invoiceHeaderSQL = "select * from tbl_invoice where invNum ='$invoiceNumber'";
        $invoiceHeaderResult = mysqli_query($conn, $invoiceHeaderSQL);
        $invoiceHeaderRow = mysqli_fetch_array($invoiceHeaderResult);

        $merchantCode = $invoiceHeaderRow['merchantCode'];

        $merNameSQL = "select merchantName, statementDate from tbl_merchant_info where merchantCode = '$merchantCode'";
        $merNameResult = mysqli_query($conn, $merNameSQL);
        $merchantrow = mysqli_fetch_array($merNameResult);

        $invoiceItemSQL = "select destination, productSizeWeight, deliveryOption, TotalOrder, cash, Ret, partial, deliveryCharge, cashCollection, CashCoD from tbl_invoice_details where invNum = '$invoiceNumber'";
        $invoiceItemResult = mysqli_query($conn, $invoiceItemSQL);

        //Itemized orders
        $invoiceDetailSQL = "select orderid, merOrderRef, productSizeWeight, deliveryOption, packagePrice, CashAmt, tbl_district_info.districtName, charge, case when Cash = 'Y' then 'Success' when Ret = 'Y' then 'Return' when partial = 'Y' then 'partial' end as deliveryStatus, case when Cash = 'Y' then cashComment when Ret = 'Y' then CONCAT_WS('',retReason ,' ',retRem ) when partial = 'Y' then partialReason when orderType='smartPick' then smartPickComment end as comment from tbl_order_details left join tbl_district_info on tbl_order_details.customerDistrict = tbl_district_info.districtId where merchantCode = '$merchantCode' and close = 'Y' and invNum = '$invoiceNumber' order by orderDate, deliveryStatus desc";
        $invoiceDetailResult = mysqli_query($conn, $invoiceDetailSQL);

        //User Name
        $usr = $invoiceHeaderRow['inv_user'];
        $empNamesql = "select * from tbl_employee_info where empCode = '$usr'";
        $empNameresult = mysqli_query($conn, $empNamesql);
        $empNameRow = mysqli_fetch_array($empNameresult);
    }
// PDF Report writing starts
if ($user_check!=''){    
//    require('fpdf/fpdf.php');
    require_once('draw.php');
    class PDF extends FPDF
    {    
        // Page header
        function Header()
        {
        // Logo
            $this->Image('http://paperflybd.com/image/pad.jpg',0,0,210,300);
	        //$this->Image('http://localhost:8000/image/pad.jpg',0,0,210,300);
	        // Arial bold 15
	        $this->SetFont('Arial','B',15);
	        // Move to the right
	        //$this->Cell(1);
	        //// Title
            //$this ->Cell (0, 0, "Gulshan Arms",0,1,'C');
            //$this ->ln(6);
            //$this ->SetFont("Arial","",10);
            //$this ->Cell (0,0, "Importer and seller of Arms and Amunitions",0,1,'C');
            //$this ->ln(5);
            //$this ->SetFont("Arial","",8);
            //$this ->Cell (0,0,"Dhaka North DCC Market, Shop No. F-44 (1st Floor), Gulshan - 2, Dhaka - 1212.",0,1,"C");
	        ////Line break
	        //$this->Ln(5);
        }
        // Page footer
        function Footer()
        {
	        // Position at 1.5 cm from bottom
	        $this->SetY(-15);
	        // Arial italic 8
	        $this->SetFont('Arial','I',8);
	        // Page number
	        $this->Cell(0,-30,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
    if (mysqli_connect_errno()){
        $error = "ERROR: ".mysqli_connect_errno(). " - ". mysqli_connect_error();
    } else {
        // Initialize per page line counter variable

        $pdf = new PDF('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf ->SetLineWidth(0.1);

        // Header Part
        $pdf->SetFillColor(96,96,96);
        $pdf->Rect(70, 26, 70, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Helvetica','B',12);
        $pdf->Cell(0, 38, 'Collection & Bill Statement' , 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Helvetica','',7);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(0, 40);
        $pdf ->MultiCell(35,4,"Merchant ID", '', 'R', 0);
        $pdf->SetXY(35, 40);
        $pdf ->MultiCell(35,4,$merchantCode, 'LTRB', 'C', 0);
        $pdf->SetXY(0, 44);
        $pdf ->MultiCell(35,4,"Merchant Name", '', 'R', 0);
        $pdf->SetXY(35, 44);
        $pdf ->MultiCell(35,4,$merchantrow['merchantName'], 'LTRB', 'C', 0);
        $pdf->SetXY(100, 36);
        $pdf ->MultiCell(50,4,"Reference", '', 'R', 0);
        $pdf->SetXY(150, 36);
        $pdf ->MultiCell(51,4, $invoiceHeaderRow['invNum'], 'LTRB', 'C', 0);
        $pdf->SetXY(100, 40);
        $pdf ->MultiCell(50,4,"Statement Date", '', 'R', 0);
        $pdf->SetXY(150, 40);
        $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['inv_date'])), 'LTRB', 'C', 0);
        $pdf->SetXY(100, 44);
        $pdf ->MultiCell(50,4,"Statement Period", '', 'R', 0);
        $pdf->SetXY(150, 44);
        $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['minDate']))." to ".date('d F, Y', strtotime($invoiceHeaderRow['maxDate'])) , 'LTRB', 'C', 0);
        $pdf->SetXY(100, 48);
        $pdf ->MultiCell(50,4,"Statement Prepared By", '', 'R', 0);
        $pdf->SetXY(150, 48);
        $pdf ->MultiCell(51,4, $empNameRow['empName'], 'LTRB', 'C', 0);
        //End of Header Part
        $localTotOrder = 0;
        $localTotCash = 0;
        $localTotReturn = 0;
        $localTotpartial = 0;
        $localTotDeliveryChg = 0;
        $localTotCashCollection = 0;
        $localTotCashCoD = 0;

        $interTotOrder = 0;
        $interTotCash = 0;
        $interTotReturn = 0;
        $interTotpartial = 0;
        $interTotDeliveryChg = 0;
        $interTotCashCollection = 0;
        $interTotCashCoD = 0;

        foreach ($invoiceItemResult as $invoiceItemRow) {
            //Local Part
            if ($invoiceItemRow['destination']=='local'){
                $localTotOrder = $localTotOrder + $invoiceItemRow['TotalOrder'];
                $localTotCash = $localTotCash + $invoiceItemRow['cash'];
                $localTotReturn = $localTotReturn + $invoiceItemRow['Ret'];
                $localTotpartial = $localTotpartial + $invoiceItemRow['partial'];
                $localTotDeliveryChg = $localTotDeliveryChg + $invoiceItemRow['deliveryCharge'];
                $localTotCashCollection = $localTotCashCollection + $invoiceItemRow['cashCollection'];
                $localTotCashCoD = $localTotCashCoD + $invoiceItemRow['CashCoD'];        
            }

            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'standard'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $SRTotalOrder = $invoiceItemRow['TotalOrder'];
                $SRCash = $invoiceItemRow['cash'];
                $SRReturn = $invoiceItemRow['Ret'];
                $SRpartial = $invoiceItemRow['partial'];
                $SRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SRcashCollection = $invoiceItemRow['cashCollection'];
                $SRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'standard'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $SETotalOrder = $invoiceItemRow['TotalOrder'];
                $SECash = $invoiceItemRow['cash'];
                $SEReturn = $invoiceItemRow['Ret'];
                $SEpartial = $invoiceItemRow['partial'];
                $SEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SEcashCollection = $invoiceItemRow['cashCollection'];
                $SECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'large'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $LRTotalOrder = $invoiceItemRow['TotalOrder'];
                $LRCash = $invoiceItemRow['cash'];
                $LRReturn = $invoiceItemRow['Ret'];
                $LRpartial = $invoiceItemRow['partial'];
                $LRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $LRcashCollection = $invoiceItemRow['cashCollection'];
                $LRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'large'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $LETotalOrder = $invoiceItemRow['TotalOrder'];
                $LECash = $invoiceItemRow['cash'];
                $LEReturn = $invoiceItemRow['Ret'];
                $LEpartial = $invoiceItemRow['partial'];
                $LEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $LEcashCollection = $invoiceItemRow['cashCollection'];
                $LECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'special'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $SPRTotalOrder = $invoiceItemRow['TotalOrder'];
                $SPRCash = $invoiceItemRow['cash'];
                $SPRReturn = $invoiceItemRow['Ret'];
                $SPRpartial = $invoiceItemRow['partial'];
                $SPRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SPRcashCollection = $invoiceItemRow['cashCollection'];
                $SPRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'special'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $SPETotalOrder = $invoiceItemRow['TotalOrder'];
                $SPECash = $invoiceItemRow['cash'];
                $SPEReturn = $invoiceItemRow['Ret'];
                $SPEpartial = $invoiceItemRow['partial'];
                $SPEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SPEcashCollection = $invoiceItemRow['cashCollection'];
                $SPECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'specialplus'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $SPPRTotalOrder = $invoiceItemRow['TotalOrder'];
                $SPPRCash = $invoiceItemRow['cash'];
                $SPPRReturn = $invoiceItemRow['Ret'];
                $SPPRpartial = $invoiceItemRow['partial'];
                $SPPRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SPPRcashCollection = $invoiceItemRow['cashCollection'];
                $SPPRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='local' and $invoiceItemRow['productSizeWeight'] == 'specialplus'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $SPPETotalOrder = $invoiceItemRow['TotalOrder'];
                $SPPECash = $invoiceItemRow['cash'];
                $SPPEReturn = $invoiceItemRow['Ret'];
                $SPPEpartial = $invoiceItemRow['partial'];
                $SPPEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $SPPEcashCollection = $invoiceItemRow['cashCollection'];
                $SPPECashCoD = $invoiceItemRow['CashCoD'];
            }
            //Inter District Part
            if ($invoiceItemRow['destination']=='interDistrict'){
                $interTotOrder = $interTotOrder + $invoiceItemRow['TotalOrder'];
                $interTotCash = $interTotCash + $invoiceItemRow['cash'];
                $interTotReturn = $interTotReturn + $invoiceItemRow['Ret'];
                $interTotpartial = $interTotpartial + $invoiceItemRow['partial'];
                $interTotDeliveryChg = $interTotDeliveryChg + $invoiceItemRow['deliveryCharge'];
                $interTotCashCollection = $interTotCashCollection + $invoiceItemRow['cashCollection'];
                $interTotCashCoD = $interTotCashCoD + $invoiceItemRow['CashCoD'];        
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'standard'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $ISRTotalOrder = $invoiceItemRow['TotalOrder'];
                $ISRCash = $invoiceItemRow['cash'];
                $ISRReturn = $invoiceItemRow['Ret'];
                $ISRpartial = $invoiceItemRow['partial'];
                $ISRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISRcashCollection = $invoiceItemRow['cashCollection'];
                $ISRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'standard'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $ISETotalOrder = $invoiceItemRow['TotalOrder'];
                $ISECash = $invoiceItemRow['cash'];
                $ISEReturn = $invoiceItemRow['Ret'];
                $ISEpartial = $invoiceItemRow['partial'];
                $ISEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISEcashCollection = $invoiceItemRow['cashCollection'];
                $ISECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'large'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $ILRTotalOrder = $invoiceItemRow['TotalOrder'];
                $ILRCash = $invoiceItemRow['cash'];
                $ILRReturn = $invoiceItemRow['Ret'];
                $ILRpartial = $invoiceItemRow['partial'];
                $ILRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ILRcashCollection = $invoiceItemRow['cashCollection'];
                $ILRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'large'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $ILETotalOrder = $invoiceItemRow['TotalOrder'];
                $ILECash = $invoiceItemRow['cash'];
                $ILEReturn = $invoiceItemRow['Ret'];
                $ILEpartial = $invoiceItemRow['partial'];
                $ILEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ILEcashCollection = $invoiceItemRow['cashCollection'];
                $ILECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'special'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $ISPRTotalOrder = $invoiceItemRow['TotalOrder'];
                $ISPRCash = $invoiceItemRow['cash'];
                $ISPRReturn = $invoiceItemRow['Ret'];
                $ISPRpartial = $invoiceItemRow['partial'];
                $ISPRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISPRcashCollection = $invoiceItemRow['cashCollection'];
                $ISPRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'special'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $ISPETotalOrder = $invoiceItemRow['TotalOrder'];
                $ISPECash = $invoiceItemRow['cash'];
                $ISPEReturn = $invoiceItemRow['Ret'];
                $ISPEpartial = $invoiceItemRow['partial'];
                $ISPEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISPEcashCollection = $invoiceItemRow['cashCollection'];
                $ISPECashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'specialplus'  and $invoiceItemRow['deliveryOption'] == 'regular') {
                $ISPPRTotalOrder = $invoiceItemRow['TotalOrder'];
                $ISPPRCash = $invoiceItemRow['cash'];
                $ISPPRReturn = $invoiceItemRow['Ret'];
                $ISPPRpartial = $invoiceItemRow['partial'];
                $ISPPRDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISPPRcashCollection = $invoiceItemRow['cashCollection'];
                $ISPPRCashCoD = $invoiceItemRow['CashCoD'];
            }
            if ($invoiceItemRow['destination']=='interDistrict' and $invoiceItemRow['productSizeWeight'] == 'specialplus'  and $invoiceItemRow['deliveryOption'] == 'express') {
                $ISPPETotalOrder = $invoiceItemRow['TotalOrder'];
                $ISPPECash = $invoiceItemRow['cash'];
                $ISPPEReturn = $invoiceItemRow['Ret'];
                $ISPPEpartial = $invoiceItemRow['partial'];
                $ISPPEDeliveryCharge = $invoiceItemRow['deliveryCharge'];
                $ISPPEcashCollection = $invoiceItemRow['cashCollection'];
                $ISPPECashCoD = $invoiceItemRow['CashCoD'];
            }
        }

        //Part A
        $pdf->SetXY(0, 56);
        $pdf->SetFillColor(160,160,160);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->MultiCell(35,4,"Part A", '', 'C', true);
        $pdf->SetFillColor(0,0,0);
        $pdf->SetXY(0, 60);
        $pdf ->MultiCell(35,4,"Within District", '', 'C', 0);
        $pdf->SetFont('Helvetica','',8);
        $pdf->SetXY(35, 64);
        $pdf->SetFillColor(160,160,160);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->MultiCell(70,4,"Orders and Delivery Charges", '', 'C', true);
        $pdf->SetXY(109, 64);
        $pdf ->MultiCell(91,4,"Collection & CoD Commission", '', 'C', true);
        $pdf->SetFont('Helvetica','B',7);
        $pdf->SetXY(35, 68);
        $pdf->SetFillColor(255,255,255);
        $pdf ->MultiCell(13,4,"Total Orders", 'LTRB', 'C', true);
        $pdf->SetXY(50, 68);
        $pdf ->MultiCell(14,8,"Delivered", 'LTRB', 'C', true);
        $pdf->SetXY(64, 68);
        $pdf ->MultiCell(13,8,"Returned", 'LTRB', 'C', true);
        $pdf->SetXY(77, 68);
        $pdf ->MultiCell(13,8,"Partial", 'LTRB', 'C', true);
        $pdf->SetXY(91.8, 68);
        $pdf ->MultiCell(13,4,"Delivery Charges", 'LTRB', 'C', true);
        $pdf->SetXY(109, 68);
        $pdf ->MultiCell(14,4,"Total Collection", 'LTRB', 'C', true);
        $pdf->SetXY(124, 68);
        $pdf ->MultiCell(14,4,"Cash Collection", 'LTRB', 'C', true);
        $pdf->SetXY(138, 68);
        $pdf ->MultiCell(15,4,"Card/Other Collection", 'LTRB', 'C', true);
        $pdf->SetXY(154, 68);
        $pdf ->MultiCell(15,4,"CoD from Cash", 'LTRB', 'C', true);
        $pdf->SetXY(169, 68);
        $pdf ->MultiCell(15,4,"CoD from Card/Other", 'LTRB', 'C', true);
        $pdf->SetXY(185, 68);
        $pdf ->MultiCell(15,8,"Total CoD", 'LTRB', 'C', true);
        $pdf->SetFont('Helvetica','',7);

        $pdf->SetXY(0, 76);
        $pdf ->MultiCell(35,4,"Standard -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 76);
        $pdf ->MultiCell(13,4,  num_to_format(round($SRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 76);
        $pdf ->MultiCell(14,4, num_to_format(round($SRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 76);
        $pdf ->MultiCell(13,4, num_to_format(round($SRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 76);
        $pdf ->MultiCell(13,4, num_to_format(round($SRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 76);
        $pdf ->MultiCell(13,4, num_to_format(round($SRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 76);
        $pdf ->MultiCell(14,4, num_to_format(round($SRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 76);
        $pdf ->MultiCell(14,4, num_to_format(round($SRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 76);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 76);
        $pdf ->MultiCell(15,4, num_to_format(round($SRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 76);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 76);
        $pdf ->MultiCell(15,4, num_to_format(round($SRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 80);
        $pdf ->MultiCell(35,4,"Standard -  Express", '', 'R', 0);
        $pdf->SetXY(35, 80);
        $pdf ->MultiCell(13,4, num_to_format(round($SETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 80);
        $pdf ->MultiCell(14,4, num_to_format(round($SECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 80);
        $pdf ->MultiCell(13,4, num_to_format(round($SEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 80);
        $pdf ->MultiCell(13,4, num_to_format(round($SEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 80);
        $pdf ->MultiCell(13,4, num_to_format(round($SEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 80);
        $pdf ->MultiCell(14,4, num_to_format(round($SEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 80);
        $pdf ->MultiCell(14,4, num_to_format(round($SEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 80);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 80);
        $pdf ->MultiCell(15,4, num_to_format(round($SECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 80);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 80);
        $pdf ->MultiCell(15,4, num_to_format(round($SECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 84);
        $pdf ->MultiCell(35,4,"Large -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 84);
        $pdf ->MultiCell(13,4, num_to_format(round($LRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 84);
        $pdf ->MultiCell(14,4, num_to_format(round($LRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 84);
        $pdf ->MultiCell(13,4, num_to_format(round($LRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 84);
        $pdf ->MultiCell(13,4, num_to_format(round($LRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 84);
        $pdf ->MultiCell(13,4, num_to_format(round($LRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 84);
        $pdf ->MultiCell(14,4, num_to_format(round($LRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 84);
        $pdf ->MultiCell(14,4, num_to_format(round($LRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 84);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 84);
        $pdf ->MultiCell(15,4, num_to_format(round($LRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 84);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 84);
        $pdf ->MultiCell(15,4, num_to_format(round($LRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 88);
        $pdf ->MultiCell(35,4,"Large -  Express", '', 'R', 0);
        $pdf->SetXY(35, 88);
        $pdf ->MultiCell(13,4, num_to_format(round($LETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 88);
        $pdf ->MultiCell(14,4, num_to_format(round($LECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 88);
        $pdf ->MultiCell(13,4, num_to_format(round($LEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 88);
        $pdf ->MultiCell(13,4, num_to_format(round($LEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 88);
        $pdf ->MultiCell(13,4, num_to_format(round($LEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 88);
        $pdf ->MultiCell(14,4, num_to_format(round($LEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 88);
        $pdf ->MultiCell(14,4, num_to_format(round($LEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 88);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 88);
        $pdf ->MultiCell(15,4, num_to_format(round($LECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 88);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 88);
        $pdf ->MultiCell(15,4, num_to_format(round($LECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 92);
        $pdf ->MultiCell(35,4,"Special -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 92);
        $pdf ->MultiCell(13,4, num_to_format(round($SPRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 92);
        $pdf ->MultiCell(14,4, num_to_format(round($SPRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 92);
        $pdf ->MultiCell(13,4, num_to_format(round($SPRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 92);
        $pdf ->MultiCell(13,4, num_to_format(round($SPRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 92);
        $pdf ->MultiCell(13,4, num_to_format(round($SPRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 92);
        $pdf ->MultiCell(14,4, num_to_format(round($SPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 92);
        $pdf ->MultiCell(14,4, num_to_format(round($SPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 92);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 92);
        $pdf ->MultiCell(15,4, num_to_format(round($SPRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 92);
        $pdf ->MultiCell(15,4, "", 'LTRB', 'R', true);
        $pdf->SetXY(185, 92);
        $pdf ->MultiCell(15,4, num_to_format(round($SPRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 96);
        $pdf ->MultiCell(35,4,"Special -  Express", '', 'R', 0);
        $pdf->SetXY(35, 96);
        $pdf ->MultiCell(13,4, num_to_format(round($SPETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 96);
        $pdf ->MultiCell(14,4, num_to_format(round($SPECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 96);
        $pdf ->MultiCell(13,4, num_to_format(round($SPEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 96);
        $pdf ->MultiCell(13,4, num_to_format(round($SPEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 96);
        $pdf ->MultiCell(13,4, num_to_format(round($SPEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 96);
        $pdf ->MultiCell(14,4, num_to_format(round($SPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 96);
        $pdf ->MultiCell(14,4, num_to_format(round($SPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 96);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 96);
        $pdf ->MultiCell(15,4, num_to_format(round($SPECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 96);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 96);
        $pdf ->MultiCell(15,4, num_to_format(round($SPECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 100);
        $pdf ->MultiCell(35,4,"Special Plus -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 100);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 100);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 100);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 100);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 100);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 100);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 100);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 100);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 100);
        $pdf ->MultiCell(15,4, num_to_format(round($SPPRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 100);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 100);
        $pdf ->MultiCell(15,4, num_to_format(round($SPPRCashCoD)), 'LTRB', 'R', true);


        $pdf->SetXY(0, 104);
        $pdf ->MultiCell(35,4,"Special Plus -  Express", '', 'R', 0);
        $pdf->SetXY(35, 104);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 104);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 104);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 104);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 104);
        $pdf ->MultiCell(13,4, num_to_format(round($SPPEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 104);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 104);
        $pdf ->MultiCell(14,4, num_to_format(round($SPPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 104);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 104);
        $pdf ->MultiCell(15,4, num_to_format(round($SPPECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 104);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 104);
        $pdf ->MultiCell(15,4, num_to_format(round($SPPECashCoD)), 'LTRB', 'R', true);

        $pdf->SetFont('Helvetica','B',7);
        $pdf->SetXY(0, 108);
        $pdf ->MultiCell(35,4,"Total Local Delivery", '', 'R', 0);
        $pdf->SetXY(35, 108);
        $pdf ->MultiCell(13,4, num_to_format(round($localTotOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 108);
        $pdf ->MultiCell(14,4, num_to_format(round($localTotCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 108);
        $pdf ->MultiCell(13,4, num_to_format(round($localTotReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 108);
        $pdf ->MultiCell(13,4, num_to_format(round($localTotpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 108);
        $pdf ->MultiCell(13,4, num_to_format(round($localTotDeliveryChg)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 108);
        $pdf ->MultiCell(14,4, num_to_format(round($localTotCashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 108);
        $pdf ->MultiCell(14,4, num_to_format(round($localTotCashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 108);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 108);
        $pdf ->MultiCell(15,4, num_to_format(round($localTotCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 108);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 108);
        $pdf ->MultiCell(15,4, num_to_format(round($localTotCashCoD)), 'LTRB', 'R', true);
        $pdf->SetFont('Helvetica','',7);

        //Part B
        $pdf->SetXY(0, 116);
        $pdf->SetFillColor(160,160,160);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->MultiCell(35,4,"Part B", '', 'C', true);
        $pdf->SetFillColor(0,0,0);
        $pdf->SetXY(0, 120);
        $pdf ->MultiCell(35,4,"Inter District", '', 'C', 0);
        $pdf->SetFont('Helvetica','',8);
        $pdf->SetXY(35, 124);
        $pdf->SetFillColor(160,160,160);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->MultiCell(70,4,"Orders and Delivery Charges", '', 'C', true);
        $pdf->SetXY(109, 124);
        $pdf ->MultiCell(91,4,"Collection & CoD Commission", '', 'C', true);
        $pdf->SetFont('Helvetica','B',7);
        $pdf->SetXY(35, 128);
        $pdf->SetFillColor(255,255,255);
        $pdf ->MultiCell(13,4,"Total Orders", 'LTRB', 'C', true);
        $pdf->SetXY(50, 128);
        $pdf ->MultiCell(14,8,"Delivered", 'LTRB', 'C', true);
        $pdf->SetXY(64, 128);
        $pdf ->MultiCell(13,8,"Returned", 'LTRB', 'C', true);
        $pdf->SetXY(77, 128);
        $pdf ->MultiCell(13,8,"Partial", 'LTRB', 'C', true);
        $pdf->SetXY(91.8, 128);
        $pdf ->MultiCell(13,4,"Delivery Charges", 'LTRB', 'C', true);
        $pdf->SetXY(109, 128);
        $pdf ->MultiCell(14,4,"Total Collection", 'LTRB', 'C', true);
        $pdf->SetXY(124, 128);
        $pdf ->MultiCell(14,4,"Cash Collection", 'LTRB', 'C', true);
        $pdf->SetXY(138, 128);
        $pdf ->MultiCell(15,4,"Card/Other Collection", 'LTRB', 'C', true);
        $pdf->SetXY(154, 128);
        $pdf ->MultiCell(15,4,"CoD from Cash", 'LTRB', 'C', true);
        $pdf->SetXY(169, 128);
        $pdf ->MultiCell(15,4,"CoD from Card/Other", 'LTRB', 'C', true);
        $pdf->SetXY(185, 128);
        $pdf ->MultiCell(15,8,"Total CoD", 'LTRB', 'C', true);
        $pdf->SetFont('Helvetica','',7);


        $pdf->SetXY(0, 136);
        $pdf ->MultiCell(35,4,"Standard -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 136);
        $pdf ->MultiCell(13,4,  num_to_format(round($ISRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 136);
        $pdf ->MultiCell(14,4, num_to_format(round($ISRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 136);
        $pdf ->MultiCell(13,4, num_to_format(round($ISRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 136);
        $pdf ->MultiCell(13,4, num_to_format(round($ISRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 136);
        $pdf ->MultiCell(13,4, num_to_format(round($ISRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 136);
        $pdf ->MultiCell(14,4, num_to_format(round($ISRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 136);
        $pdf ->MultiCell(14,4, num_to_format(round($ISRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 136);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 136);
        $pdf ->MultiCell(15,4, num_to_format(round($ISRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 136);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 136);
        $pdf ->MultiCell(15,4, num_to_format(round($ISRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 140);
        $pdf ->MultiCell(35,4,"Standard -  Express", '', 'R', 0);
        $pdf->SetXY(35, 140);
        $pdf ->MultiCell(13,4, num_to_format(round($ISETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 140);
        $pdf ->MultiCell(14,4, num_to_format(round($ISECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 140);
        $pdf ->MultiCell(13,4, num_to_format(round($ISEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 140);
        $pdf ->MultiCell(13,4, num_to_format(round($ISEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 140);
        $pdf ->MultiCell(13,4, num_to_format(round($ISEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 140);
        $pdf ->MultiCell(14,4, num_to_format(round($ISEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 140);
        $pdf ->MultiCell(14,4, num_to_format(round($ISEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 140);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 140);
        $pdf ->MultiCell(15,4, num_to_format(round($ISECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 140);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 140);
        $pdf ->MultiCell(15,4, num_to_format(round($ISECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 144);
        $pdf ->MultiCell(35,4,"Large -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 144);
        $pdf ->MultiCell(13,4, num_to_format(round($ILRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 144);
        $pdf ->MultiCell(14,4, num_to_format(round($ILRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 144);
        $pdf ->MultiCell(13,4, num_to_format(round($ILRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 144);
        $pdf ->MultiCell(13,4, num_to_format(round($ILRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 144);
        $pdf ->MultiCell(13,4, num_to_format(round($ILRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 144);
        $pdf ->MultiCell(14,4, num_to_format(round($ILRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 144);
        $pdf ->MultiCell(14,4, num_to_format(round($ILRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 144);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 144);
        $pdf ->MultiCell(15,4, num_to_format(round($ILRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 144);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 144);
        $pdf ->MultiCell(15,4, num_to_format(round($ILRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 148);
        $pdf ->MultiCell(35,4,"Large -  Express", '', 'R', 0);
        $pdf->SetXY(35, 148);
        $pdf ->MultiCell(13,4, num_to_format(round($ILETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 148);
        $pdf ->MultiCell(14,4, num_to_format(round($ILECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 148);
        $pdf ->MultiCell(13,4, num_to_format(round($ILEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 148);
        $pdf ->MultiCell(13,4, num_to_format(round($ILEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 148);
        $pdf ->MultiCell(13,4, num_to_format(round($ILEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 148);
        $pdf ->MultiCell(14,4, num_to_format(round($ILEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 148);
        $pdf ->MultiCell(14,4, num_to_format(round($ILEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 148);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 148);
        $pdf ->MultiCell(15,4, num_to_format(round($ILECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 148);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 148);
        $pdf ->MultiCell(15,4, num_to_format(round($ILECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 152);
        $pdf ->MultiCell(35,4,"Special -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 152);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 152);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 152);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 152);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 152);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 152);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 152);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 152);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 152);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 152);
        $pdf ->MultiCell(15,4, "", 'LTRB', 'R', true);
        $pdf->SetXY(185, 152);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPRCashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 156);
        $pdf ->MultiCell(35,4,"Special -  Express", '', 'R', 0);
        $pdf->SetXY(35, 156);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 156);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 156);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 156);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 156);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 156);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 156);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 156);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 156);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 156);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 156);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPECashCoD)), 'LTRB', 'R', true);

        $pdf->SetXY(0, 160);
        $pdf ->MultiCell(35,4,"Special Plus -  Regular", '', 'R', 0);
        $pdf->SetXY(35, 160);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPRTotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 160);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPRCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 160);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPRReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 160);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPRpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 160);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPRDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 160);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 160);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPRcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 160);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 160);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPPRCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 160);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 160);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPPRCashCoD)), 'LTRB', 'R', true);


        $pdf->SetXY(0, 164);
        $pdf ->MultiCell(35,4,"Special Plus -  Express", '', 'R', 0);
        $pdf->SetXY(35, 164);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPETotalOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 164);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPECash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 164);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPEReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 164);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPEpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 164);
        $pdf ->MultiCell(13,4, num_to_format(round($ISPPEDeliveryCharge)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 164);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 164);
        $pdf ->MultiCell(14,4, num_to_format(round($ISPPEcashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 164);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 164);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPPECashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 164);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 164);
        $pdf ->MultiCell(15,4, num_to_format(round($ISPPECashCoD)), 'LTRB', 'R', true);

        $pdf->SetFont('Helvetica','B',7);
        $pdf->SetXY(0, 168);
        $pdf ->MultiCell(35,4,"Total Inter District Delivery", '', 'R', 0);
        $pdf->SetXY(35, 168);
        $pdf ->MultiCell(13,4, num_to_format(round($interTotOrder)), 'LTRB', 'R', true);
        $pdf->SetXY(50, 168);
        $pdf ->MultiCell(14,4, num_to_format(round($interTotCash)), 'LTRB', 'R', true);
        $pdf->SetXY(64, 168);
        $pdf ->MultiCell(13,4, num_to_format(round($interTotReturn)), 'LTRB', 'R', true);
        $pdf->SetXY(77, 168);
        $pdf ->MultiCell(13,4, num_to_format(round($interTotpartial)), 'LTRB', 'R', true);
        $pdf->SetXY(91.8, 168);
        $pdf ->MultiCell(13,4, num_to_format(round($interTotDeliveryChg)), 'LTRB', 'R', true);
        $pdf->SetXY(109, 168);
        $pdf ->MultiCell(14,4, num_to_format(round($interTotCashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(124, 168);
        $pdf ->MultiCell(14,4, num_to_format(round($interTotCashCollection)), 'LTRB', 'R', true);
        $pdf->SetXY(138, 168);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(154, 168);
        $pdf ->MultiCell(15,4, num_to_format(round($interTotCashCoD)), 'LTRB', 'R', true);
        $pdf->SetXY(169, 168);
        $pdf ->MultiCell(15,4,"", 'LTRB', 'R', true);
        $pdf->SetXY(185, 168);
        $pdf ->MultiCell(15,4, num_to_format(round($interTotCashCoD)), 'LTRB', 'R', true);
        $pdf->SetFont('Helvetica','',7);

        //Part C
        $pdf->SetXY(0, 176);
        $pdf->SetFillColor(160,160,160);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->MultiCell(35,4,"Part C", '', 'C', true);
        $pdf->SetFillColor(0,0,0);
        $pdf->SetXY(0, 180);
        $pdf ->MultiCell(35,4,"Payment Summary", '', 'C', 0);
        $pdf->SetFont('Helvetica','',8);

        $pdf->SetXY(0, 192);
        $pdf ->MultiCell(35,4,"Total Collection", '', 'R', 0);
        $pdf->SetXY(35, 188);
        $pdf ->MultiCell(20,4,"Amount(BDT)", '', 'C', 0);
        $pdf->SetXY(35, 192);
        $pdf ->MultiCell(20,4, num_to_format(round($localTotCashCollection + $interTotCashCollection)), 'LTRB', 'R', 0);
        $pdf->SetXY(70, 192);
        $pdf ->MultiCell(35,4,"Total Delivery Charges", '', 'R', 0);
        $pdf->SetXY(105, 188);
        $pdf ->MultiCell(20,4,"Amount(BDT)", '', 'C', 0);
        $pdf->SetXY(105, 192);
        $pdf ->MultiCell(20,4, num_to_format(round($localTotDeliveryChg + $interTotDeliveryChg)), 'LTRB', 'R', 0);
        $pdf->SetXY(70, 196);
        $pdf ->MultiCell(35,4,"Total CoD Commission", '', 'R', 0);
        $pdf->SetXY(105, 196);
        $pdf ->MultiCell(20,4, num_to_format(round($localTotCashCoD + $interTotCashCoD)), 'LTRB', 'R', 0);
        $pdf->SetFont('Helvetica','B',8);
        $pdf->SetXY(70, 200);
        $pdf ->MultiCell(35,4,"Total Charges", '', 'R', 0);
        $pdf->SetXY(105, 200);
        $pdf ->MultiCell(20,4, num_to_format(round($localTotCashCoD + $interTotCashCoD + $localTotDeliveryChg + $interTotDeliveryChg)), 'LTRB', 'R', 0);
        $pdf->SetFont('Helvetica','',8);
        $pdf->SetXY(70, 204);
        $pdf ->MultiCell(35,4,"Discount/Adjustment", '', 'R', 0);
        $pdf->SetXY(105, 204);
        $pdf ->MultiCell(20,4, num_to_format(round($invoiceHeaderRow['Discount'])), 'LTRB', 'R', 0);
        $pdf->SetFont('Helvetica','B',8);
        $pdf->SetXY(70, 208);
        $pdf ->MultiCell(35,4,"Total Charges Deducted", '', 'R', 0);
        $pdf->SetXY(105, 208);
        $pdf ->MultiCell(20,4, num_to_format(round(($localTotCashCoD + $interTotCashCoD + $localTotDeliveryChg + $interTotDeliveryChg)-$invoiceHeaderRow['Discount'])), 'LTRB', 'R', 0);
        $pdf->SetFont('Helvetica','',8);

        $totCollections = round($localTotCashCollection + $interTotCashCollection);
        $totCharges = round(($localTotCashCoD + $interTotCashCoD + $localTotDeliveryChg + $interTotDeliveryChg)-$invoiceHeaderRow['Discount']);
        if (($totCollections - $totCharges) > 0){
            $pdf->SetXY(140, 192);
            $pdf ->MultiCell(35,4, "Net Payable to Merchant", '', 'R', 0);
        } else {
            $pdf->SetXY(130, 192);
            $pdf ->MultiCell(45,4, "Net Receivable from Merchant", '', 'R', 0);
        }
        $pdf->SetXY(175, 188);
        $pdf ->MultiCell(20,4,"Amount(BDT)", '', 'C', 0);
        $pdf->SetXY(175, 192);
        $pdf ->MultiCell(20,4, num_to_format($totCollections - $totCharges), 'LTRB', 'R', 0);

        $pdf->SetXY(35, 220);
        $pdf ->MultiCell(140,4, $invoiceHeaderRow['Message'], '', 'L', 0);

        //$pdf->SetXY(140, 258);
        //$pdf ->MultiCell(35,4, "", 'B', 'L', 0);
        //$pdf->SetXY(140, 262);
        //$pdf ->MultiCell(35,4, "For PAPERFLY", '', 'C', 0);

        //Order details
        

        if (mysqli_num_rows($invoiceDetailResult) > 0) {
            $pdf->AddPage();
            $lineCount = 0;
            $linePerPage = 0;
            $yPosition = 57;
            $pdf->SetFillColor(96,96,96);
            $pdf->Rect(70, 26, 70, 6, 'F');
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Helvetica','B',12);
            $pdf->Cell(0, 38, 'Itemized Bill' , 0, 0, 'C');
            $pdf->Ln();
            $pdf->SetFont('Helvetica','',7);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetXY(0, 40);
            $pdf ->MultiCell(35,4,"Merchant ID", '', 'R', 0);
            $pdf->SetXY(35, 40);
            $pdf ->MultiCell(35,4,$merchantCode, 'LTRB', 'C', 0);
            $pdf->SetXY(0, 44);
            $pdf ->MultiCell(35,4,"Merchant Name", '', 'R', 0);
            $pdf->SetXY(35, 44);
            $pdf ->MultiCell(35,4,$merchantrow['merchantName'], 'LTRB', 'C', 0);
            $pdf->SetXY(100, 36);
            $pdf ->MultiCell(50,4,"Reference", '', 'R', 0);
            $pdf->SetXY(150, 36);
            $pdf ->MultiCell(51,4, $invoiceHeaderRow['invNum'], 'LTRB', 'C', 0);
            $pdf->SetXY(100, 40);
            $pdf ->MultiCell(50,4,"Statement Date", '', 'R', 0);
            $pdf->SetXY(150, 40);
            $pdf ->MultiCell(51,4, date('d F, Y', strtotime($merchantrow['statementDate'])), 'LTRB', 'C', 0);
            $pdf->SetXY(100, 44);
            $pdf ->MultiCell(50,4,"Statement Period", '', 'R', 0);
            $pdf->SetXY(150, 44);
            $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['minDate']))." to ".date('d F, Y', strtotime($invoiceHeaderRow['maxDate'])) , 'LTRB', 'C', 0);
            $pdf->SetXY(100, 48);
            $pdf ->MultiCell(50,4,"Statement Prepared By", '', 'R', 0);
            $pdf->SetXY(150, 48);
            $pdf ->MultiCell(51,4, $empNameRow['empName'], 'LTRB', 'C', 0);
            
            $pdf->SetXY(5, 54);
            $pdf->SetFont('Helvetica','',6);
            $pdf ->MultiCell(8,3,"SL No", 'LBRT', 'L', 0);
            $pdf->SetXY(13, 54);
            $pdf ->MultiCell(22,3,"Order ID", 'LBRT', 'L', 0);
            $pdf->SetXY(35, 54);
            $pdf ->MultiCell(30,3,"Merchant Ref", 'LBRT', 'L', 0);
            $pdf->SetXY(65, 54);
            $pdf ->MultiCell(14,3,"Pack Opt", 'LBRT', 'L', 0);        
            $pdf->SetXY(79, 54);
            $pdf ->MultiCell(11,3,"Del. Opt", 'LBRT', 'L', 0);        
            $pdf->SetXY(90, 54);
            $pdf ->MultiCell(12,3,"Price", 'LBRT', 'C', 0);        
            $pdf->SetXY(102, 54);
            $pdf ->MultiCell(14,3,"Collection", 'LBRT', 'C', 0);        
            $pdf->SetXY(116, 54);
            $pdf ->MultiCell(15,3,"Cust Dist", 'LBRT', 'L', 0);        
            $pdf->SetXY(131, 54);
            $pdf ->MultiCell(10,3,"Charges", 'LBRT', 'C', 0);
            $pdf->SetXY(141, 54);
            $pdf ->MultiCell(10,3,"Status", 'LBRT', 'L', 0); 
            $pdf->SetXY(151, 54);
            $pdf ->MultiCell(52,3,"Comment", 'LBRT', 'L', 0);
            $orderCount = 1; 
            foreach ($invoiceDetailResult as $invoiceDetailRow){
                if ($lineCount == 60) {
                    $pdf->AddPage();
                    $lineCount = 0;
                    $yPosition = 54;

                    $pdf->SetFillColor(96,96,96);
                    $pdf->Rect(70, 26, 70, 6, 'F');
                    $pdf->SetTextColor(255,255,255);
                    $pdf->SetFont('Helvetica','B',12);
                    $pdf->Cell(0, 38, 'Itemized Bill' , 0, 0, 'C');
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica','',7);
                    $pdf->SetTextColor(0,0,0);
                    $pdf->SetXY(0, 40);
                    $pdf ->MultiCell(35,4,"Merchant ID", '', 'R', 0);
                    $pdf->SetXY(35, 40);
                    $pdf ->MultiCell(35,4,$merchantCode, 'LTRB', 'C', 0);
                    $pdf->SetXY(0, 44);
                    $pdf ->MultiCell(35,4,"Merchant Name", '', 'R', 0);
                    $pdf->SetXY(35, 44);
                    $pdf ->MultiCell(35,4,$merchantrow['merchantName'], 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 36);
                    $pdf ->MultiCell(50,4,"Reference", '', 'R', 0);
                    $pdf->SetXY(150, 36);
                    $pdf ->MultiCell(51,4, $invoiceHeaderRow['invNum'], 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 40);
                    $pdf ->MultiCell(50,4,"Statement Date", '', 'R', 0);
                    $pdf->SetXY(150, 40);
                    $pdf ->MultiCell(51,4, date('d F, Y', strtotime($merchantrow['statementDate'])), 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 44);
                    $pdf ->MultiCell(50,4,"Statement Period", '', 'R', 0);
                    $pdf->SetXY(150, 44);
                    $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['minDate']))." to ".date('d F, Y', strtotime($invoiceHeaderRow['maxDate'])) , 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 48);
                    $pdf ->MultiCell(50,4,"Statement Prepared By", '', 'R', 0);
                    $pdf->SetXY(150, 48);
                    $pdf ->MultiCell(51,4, $empNameRow['empName'], 'LTRB', 'C', 0);
                    
                    //Order detail
                    $pdf->SetXY(5, 54);
                    $pdf->SetFont('Helvetica','',6);
                    $pdf ->MultiCell(8,3,"SL No", 'LBRT', 'L', 0);
                    $pdf->SetXY(13, 54);
                    $pdf ->MultiCell(22,3,"Order ID", 'LBRT', 'L', 0);
                    $pdf->SetXY(35, 54);
                    $pdf ->MultiCell(30,3,"Merchant Ref", 'LBRT', 'L', 0);
                    $pdf->SetXY(65, 54);
                    $pdf ->MultiCell(14,3,"Pack Opt", 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, 54);
                    $pdf ->MultiCell(11,3,"Del. Opt", 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, 54);
                    $pdf ->MultiCell(12,3,"Price", 'LBRT', 'C', 0);        
                    $pdf->SetXY(102, 54);
                    $pdf ->MultiCell(14,3,"Collection", 'LBRT', 'C', 0);        
                    $pdf->SetXY(116, 54);
                    $pdf ->MultiCell(15,3,"Cust Dist", 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, 54);
                    $pdf ->MultiCell(10,3,"Charges", 'LBRT', 'C', 0);
                    $pdf->SetXY(141, 54);
                    $pdf ->MultiCell(10,3,"Status", 'LBRT', 'L', 0);
                    $pdf->SetXY(151, 54);
                    $pdf ->MultiCell(52,3,"Comment", 'LBRT', 'L', 0); 

                    $yPosition = $yPosition +3;
                    $pdf->SetXY(5,$yPosition);
                    $pdf->MultiCell(8,3, $orderCount, 'LBRT', 'R', 0);
                    $pdf->SetXY(13, $yPosition);
                    $pdf ->MultiCell(22,3, $invoiceDetailRow['orderid'], 'LBRT', 'L', 0);
                    $pdf->SetXY(35, $yPosition);
                    $pdf ->MultiCell(30,3, $invoiceDetailRow['merOrderRef'], 'LBRT', 'L', 0);
                    $pdf->SetXY(65, $yPosition);
                    $pdf ->MultiCell(14,3, $invoiceDetailRow['productSizeWeight'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, $yPosition);
                    $pdf ->MultiCell(11,3, $invoiceDetailRow['deliveryOption'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, $yPosition);
                    $pdf ->MultiCell(12,3, num_to_format(round($invoiceDetailRow['packagePrice'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(102, $yPosition);
                    $pdf ->MultiCell(14,3, num_to_format(round($invoiceDetailRow['CashAmt'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(116, $yPosition);
                    $pdf ->MultiCell(15,3," ".$invoiceDetailRow['districtName'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, $yPosition);
                    $pdf ->MultiCell(10,3, num_to_format(round($invoiceDetailRow['charge'])), 'LBRT', 'R', 0);
                    $pdf->SetXY(141, $yPosition);
                    $pdf ->MultiCell(10,3, $invoiceDetailRow['deliveryStatus'] , 'LBRT', 'L', 0);           
                    $pdf->SetXY(151, $yPosition);
                    $pdf->SetFont('Helvetica','',4.5);
                    $pdf ->MultiCell(52,3, substr($invoiceDetailRow['comment'],0,64) , 'LBRT', 'L', 0);    
                    $pdf->SetFont('Helvetica','',6);
                } else {

                    $pdf->SetXY(5,$yPosition);
                    $pdf->MultiCell(8,3, $orderCount, 'LBRT', 'R', 0);
                    $pdf->SetXY(13, $yPosition);
                    $pdf ->MultiCell(22,3, $invoiceDetailRow['orderid'], 'LBRT', 'L', 0);
                    $pdf->SetXY(35, $yPosition);
                    $pdf ->MultiCell(30,3, $invoiceDetailRow['merOrderRef'], 'LBRT', 'L', 0);
                    $pdf->SetXY(65, $yPosition);
                    $pdf ->MultiCell(14,3, $invoiceDetailRow['productSizeWeight'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, $yPosition);
                    $pdf ->MultiCell(11,3, $invoiceDetailRow['deliveryOption'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, $yPosition);
                    $pdf ->MultiCell(12,3, num_to_format(round($invoiceDetailRow['packagePrice'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(102, $yPosition);
                    $pdf ->MultiCell(14,3, num_to_format(round($invoiceDetailRow['CashAmt'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(116, $yPosition);
                    $pdf ->MultiCell(15,3," ".$invoiceDetailRow['districtName'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, $yPosition);
                    $pdf ->MultiCell(10,3, num_to_format(round($invoiceDetailRow['charge'])), 'LBRT', 'R', 0);
                    $pdf->SetXY(141, $yPosition);
                    $pdf ->MultiCell(10,3, $invoiceDetailRow['deliveryStatus'] , 'LBRT', 'L', 0);           
                    $pdf->SetXY(151, $yPosition);
                    $pdf->SetFont('Helvetica','',4.5);
                    $pdf ->MultiCell(52,3, substr($invoiceDetailRow['comment'],0,64) , 'LBRT', 'L', 0);
                    $pdf->SetFont('Helvetica','',6);                                                                                                                   
                }
                $yPosition = $yPosition +3;
                $lineCount ++;
                $orderCount ++; 
            }
        }

        //In progress orders with statement period
        // date('d F, Y', strtotime($invPeriodRow['statementDate']))." to ".date('d F, Y', strtotime($invEnddate))
        $PinvoiceDetailSQL = "select invNum, orderid, orderDate, merOrderRef, merchantCode, productSizeWeight, deliveryOption, packagePrice, customerDistrict, tbl_district_info.districtName FROM tbl_order_progress left join tbl_district_info on tbl_order_progress.customerDistrict = tbl_district_info.districtId where merchantCode = '$merchantCode' and invNum = '$invoiceNumber' order by orderDate";
        $PinvoiceDetailResult = mysqli_query($conn, $PinvoiceDetailSQL);

        if (mysqli_num_rows($PinvoiceDetailResult) > 0) {
            $pdf->AddPage();
            $lineCount = 0;
            $linePerPage = 0;
            $yPosition = 57;
            $pdf->SetFillColor(96,96,96);
            $pdf->Rect(70, 26, 70, 6, 'F');
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Helvetica','B',12);
            $pdf->Cell(0, 38, 'Orders in Progress' , 0, 0, 'C');
            $pdf->Ln();
            $pdf->SetFont('Helvetica','',7);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetXY(0, 40);
            $pdf ->MultiCell(35,4,"Merchant ID", '', 'R', 0);
            $pdf->SetXY(35, 40);
            $pdf ->MultiCell(35,4,$merchantCode, 'LTRB', 'C', 0);
            $pdf->SetXY(0, 44);
            $pdf ->MultiCell(35,4,"Merchant Name", '', 'R', 0);
            $pdf->SetXY(35, 44);
            $pdf ->MultiCell(35,4,$merchantrow['merchantName'], 'LTRB', 'C', 0);
            $pdf->SetXY(100, 36);
            $pdf ->MultiCell(50,4,"Reference", '', 'R', 0);
            $pdf->SetXY(150, 36);
            $pdf ->MultiCell(51,4, $invoiceHeaderRow['invNum'], 'LTRB', 'C', 0);
            $pdf->SetXY(100, 40);
            $pdf ->MultiCell(50,4,"Statement Date", '', 'R', 0);
            $pdf->SetXY(150, 40);
            $pdf ->MultiCell(51,4, date('d F, Y', strtotime($merchantrow['statementDate'])), 'LTRB', 'C', 0);
            $pdf->SetXY(100, 44);
            $pdf ->MultiCell(50,4,"Statement Period", '', 'R', 0);
            $pdf->SetXY(150, 44);
            $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['minDate']))." to ".date('d F, Y', strtotime($invoiceHeaderRow['maxDate'])) , 'LTRB', 'C', 0);
            $pdf->SetXY(100, 48);
            $pdf ->MultiCell(50,4,"Statement Prepared By", '', 'R', 0);
            $pdf->SetXY(150, 48);
            $pdf ->MultiCell(51,4, $empNameRow['empName'], 'LTRB', 'C', 0);
            
            $pdf->SetXY(5, 54);
            $pdf->SetFont('Helvetica','',6);
            $pdf ->MultiCell(8,3,"SL No", 'LBRT', 'L', 0);
            $pdf->SetXY(13, 54);
            $pdf ->MultiCell(22,3,"Order ID", 'LBRT', 'L', 0);
            $pdf->SetXY(35, 54);
            $pdf ->MultiCell(30,3,"Merchant Ref", 'LBRT', 'L', 0);
            $pdf->SetXY(65, 54);
            $pdf ->MultiCell(14,3,"Pack Opt", 'LBRT', 'L', 0);        
            $pdf->SetXY(79, 54);
            $pdf ->MultiCell(11,3,"Del. Opt", 'LBRT', 'L', 0);        
            $pdf->SetXY(90, 54);
            $pdf ->MultiCell(12,3,"Price", 'LBRT', 'C', 0);        
            $pdf->SetXY(102, 54);
            $pdf ->MultiCell(14,3,"Collection", 'LBRT', 'C', 0);        
            $pdf->SetXY(116, 54);
            $pdf ->MultiCell(15,3,"Cust Dist", 'LBRT', 'L', 0);        
            $pdf->SetXY(131, 54);
            $pdf ->MultiCell(10,3,"Charges", 'LBRT', 'C', 0);
            $pdf->SetXY(141, 54);
            $pdf ->MultiCell(10,3,"Status", 'LBRT', 'L', 0); 
            $pdf->SetXY(151, 54);
            $pdf ->MultiCell(52,3,"Comment", 'LBRT', 'L', 0);
            $orderCount = 1; 
            foreach ($PinvoiceDetailResult as $PinvoiceDetailRow){
                if ($lineCount == 60) {
                    $pdf->AddPage();
                    $lineCount = 0;
                    $yPosition = 54;

                    $pdf->SetFillColor(96,96,96);
                    $pdf->Rect(70, 26, 70, 6, 'F');
                    $pdf->SetTextColor(255,255,255);
                    $pdf->SetFont('Helvetica','B',12);
                    $pdf->Cell(0, 38, 'Orders in Progress' , 0, 0, 'C');
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica','',7);
                    $pdf->SetTextColor(0,0,0);
                    $pdf->SetXY(0, 40);
                    $pdf ->MultiCell(35,4,"Merchant ID", '', 'R', 0);
                    $pdf->SetXY(35, 40);
                    $pdf ->MultiCell(35,4,$merchantCode, 'LTRB', 'C', 0);
                    $pdf->SetXY(0, 44);
                    $pdf ->MultiCell(35,4,"Merchant Name", '', 'R', 0);
                    $pdf->SetXY(35, 44);
                    $pdf ->MultiCell(35,4,$merchantrow['merchantName'], 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 36);
                    $pdf ->MultiCell(50,4,"Reference", '', 'R', 0);
                    $pdf->SetXY(150, 36);
                    $pdf ->MultiCell(51,4, $invoiceHeaderRow['invNum'], 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 40);
                    $pdf ->MultiCell(50,4,"Statement Date", '', 'R', 0);
                    $pdf->SetXY(150, 40);
                    $pdf ->MultiCell(51,4, date('d F, Y', strtotime($merchantrow['statementDate'])), 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 44);
                    $pdf ->MultiCell(50,4,"Statement Period", '', 'R', 0);
                    $pdf->SetXY(150, 44);
                    $pdf ->MultiCell(51,4, date('d F, Y', strtotime($invoiceHeaderRow['minDate']))." to ".date('d F, Y', strtotime($invoiceHeaderRow['maxDate'])) , 'LTRB', 'C', 0);
                    $pdf->SetXY(100, 48);
                    $pdf ->MultiCell(50,4,"Statement Prepared By", '', 'R', 0);
                    $pdf->SetXY(150, 48);
                    $pdf ->MultiCell(51,4, $empNameRow['empName'], 'LTRB', 'C', 0);
                    
                    //Order detail
                    $pdf->SetXY(5, 54);
                    $pdf->SetFont('Helvetica','',6);
                    $pdf ->MultiCell(8,3,"SL No", 'LBRT', 'L', 0);
                    $pdf->SetXY(13, 54);
                    $pdf ->MultiCell(22,3,"Order ID", 'LBRT', 'L', 0);
                    $pdf->SetXY(35, 54);
                    $pdf ->MultiCell(30,3,"Merchant Ref", 'LBRT', 'L', 0);
                    $pdf->SetXY(65, 54);
                    $pdf ->MultiCell(14,3,"Pack Opt", 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, 54);
                    $pdf ->MultiCell(11,3,"Del. Opt", 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, 54);
                    $pdf ->MultiCell(12,3,"Price", 'LBRT', 'C', 0);        
                    $pdf->SetXY(102, 54);
                    $pdf ->MultiCell(14,3,"Collection", 'LBRT', 'C', 0);        
                    $pdf->SetXY(116, 54);
                    $pdf ->MultiCell(15,3,"Cust Dist", 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, 54);
                    $pdf ->MultiCell(10,3,"Charges", 'LBRT', 'C', 0);
                    $pdf->SetXY(141, 54);
                    $pdf ->MultiCell(10,3,"Status", 'LBRT', 'L', 0);
                    $pdf->SetXY(151, 54);
                    $pdf ->MultiCell(52,3,"Comment", 'LBRT', 'L', 0); 

                    $yPosition = $yPosition +3;
                    $pdf->SetXY(5,$yPosition);
                    $pdf->MultiCell(8,3, $orderCount, 'LBRT', 'R', 0);
                    $pdf->SetXY(13, $yPosition);
                    $pdf ->MultiCell(22,3, $PinvoiceDetailRow['orderid'], 'LBRT', 'L', 0);
                    $pdf->SetXY(35, $yPosition);
                    $pdf ->MultiCell(30,3, $PinvoiceDetailRow['merOrderRef'], 'LBRT', 'L', 0);
                    $pdf->SetXY(65, $yPosition);
                    $pdf ->MultiCell(14,3, $PinvoiceDetailRow['productSizeWeight'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, $yPosition);
                    $pdf ->MultiCell(11,3, $PinvoiceDetailRow['deliveryOption'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, $yPosition);
                    $pdf ->MultiCell(12,3, num_to_format(round($PinvoiceDetailRow['packagePrice'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(102, $yPosition);
                    $pdf ->MultiCell(14,3, "", 'LBRT', 'R', 0);        
                    $pdf->SetXY(116, $yPosition);
                    $pdf ->MultiCell(15,3," ".$PinvoiceDetailRow['districtName'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, $yPosition);
                    $pdf ->MultiCell(10,3, "", 'LBRT', 'R', 0);
                    $pdf->SetXY(141, $yPosition);
                    $pdf ->MultiCell(10,3, "" , 'LBRT', 'L', 0);           
                    $pdf->SetXY(151, $yPosition);
                    $pdf ->MultiCell(52,3, "" , 'LBRT', 'L', 0);    
                } else {
                    $pdf->SetXY(5,$yPosition);
                    $pdf->MultiCell(8,3, $orderCount, 'LBRT', 'R', 0);
                    $pdf->SetXY(13, $yPosition);
                    $pdf ->MultiCell(22,3, $PinvoiceDetailRow['orderid'], 'LBRT', 'L', 0);
                    $pdf->SetXY(35, $yPosition);
                    $pdf ->MultiCell(30,3, $PinvoiceDetailRow['merOrderRef'], 'LBRT', 'L', 0);
                    $pdf->SetXY(65, $yPosition);
                    $pdf ->MultiCell(14,3, $PinvoiceDetailRow['productSizeWeight'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(79, $yPosition);
                    $pdf ->MultiCell(11,3, $PinvoiceDetailRow['deliveryOption'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(90, $yPosition);
                    $pdf ->MultiCell(12,3, num_to_format(round($PinvoiceDetailRow['packagePrice'])), 'LBRT', 'R', 0);        
                    $pdf->SetXY(102, $yPosition);
                    $pdf ->MultiCell(14,3, "", 'LBRT', 'R', 0);        
                    $pdf->SetXY(116, $yPosition);
                    $pdf ->MultiCell(15,3," ".$PinvoiceDetailRow['districtName'], 'LBRT', 'L', 0);        
                    $pdf->SetXY(131, $yPosition);
                    $pdf ->MultiCell(10,3, "", 'LBRT', 'R', 0);
                    $pdf->SetXY(141, $yPosition);
                    $pdf ->MultiCell(10,3, "" , 'LBRT', 'L', 0);           
                    $pdf->SetXY(151, $yPosition);
                    $pdf ->MultiCell(52,3, "" , 'LBRT', 'L', 0);                                                                                                                   

                }
                $yPosition = $yPosition +3;
                $lineCount ++;
                $orderCount ++;
            }
        }

        mysqli_close($conn);
       }

    $pdf ->output(); 
} else {
    // Redirect ot login page
    header("location: login");
}
// PDF report writing ends
    //} 
}
mysqli_close($conn);
?>