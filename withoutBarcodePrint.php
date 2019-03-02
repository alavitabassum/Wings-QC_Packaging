<?php
    include('session.php');
    if($user_check !=''){
        include('config.php');

        $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and barcodeOne = 'Y'"));
        if ($userPrivCheckRow['barcodeOne'] != 'Y'){
            exit();
        }

        require('ean13_upca_barcode.php');

    class PDF extends FPDF
    {    
        // Page header
        function Footer()
        {
	        // Position at 1.5 cm from bottom
	        $this->SetY(-15);
	        // Arial italic 8
	        $this->SetFont('Arial','I',8);
	        // Page number
	        $this->Cell(0,30,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

        if (isset($_GET['orderid'])){
            $merchant = trim($_GET['merchant']);
            //$startDate = date("Y-m-d", strtotime($_GET['startDate'])); 
            $orderID = $_GET['orderid'];
            $pickOrderListSQL = "select orderid, barcode, merOrderRef, tbl_merchant_info.merchantName, tbl_merchant_info.contactNumber, pickPointCode, dropPointCode, productBrief, packagePrice, custname, custaddress, custphone from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where orderid = '$orderID'";
            $pickOrderListResult = mysqli_query($conn, $pickOrderListSQL);

            $pdf=new PDF_EAN13('P','mm',array(76.2,101.6));
            $pdf->SetTitle('Barcode Generated');
            foreach ($pickOrderListResult as $pickOrderListRow){
                $pickBarcode = substr($pickOrderListRow['barcode'], 0, 11);
                $pdf->AddPage();
                $pdf->Image('paperfly.jpg',15, -17, -300, 'JPG');
                $pdf->SetFont('Helvetica','',8);
                $pdf->SetXY(0, 11);
                $pdf->MultiCell(78,5,'Nationwide E-commerce Door to Door Delivery Solution','','C',0);
                $pdf->SetFont('Helvetica','',13);
                $pdf->SetXY(0, 17);
                $pdf ->MultiCell(78,4,$pickOrderListRow['orderid'], '', 'C', 0);
                $pdf->SetFont('Helvetica','',10);
                $pdf->SetXY(0, 21);
                $pdf ->MultiCell(78,4,substr($pickOrderListRow['merOrderRef'],0,30), '', 'C', 0);
                $pdf->SetFont('Helvetica','',7.5);
                $pdf->SetXY(3, 26);
                $pdf ->MultiCell(78,3,'Customer Name    : '.$pickOrderListRow['custname'], '', 'L', 0);
                $pdf->SetXY(3, 29);
                $pdf ->MultiCell(78,3,'Customer Phone   : '.$pickOrderListRow['custphone'], '', 'L', 0);
                $pdf->SetXY(3, 32);
                $pdf ->MultiCell(74,3,'Customer Address : '.substr($pickOrderListRow['custaddress'],0,148), '', 'L', 0);
                $pdf->SetXY(3, 43);
                $pdf ->MultiCell(78,3,'Package price    : '.$pickOrderListRow['packagePrice'], '', 'L', 0);
                $pdf->SetXY(3, 46);
                $pdf ->MultiCell(74,3,'Package breif    : '.substr($pickOrderListRow['productBrief'],0,140), '', 'L', 0);
                $pdf->SetXY(3, 55);
                $pdf ->MultiCell(78,3,'Merchant Name    : '.$pickOrderListRow['merchantName'], '', 'L', 0);
                $pdf->SetXY(3, 58);
                $pdf ->MultiCell(78,3,'Merchant Phone   : '.$pickOrderListRow['contactNumber'], '', 'L', 0);


                $pdf->SetFont('Helvetica','B',40);
                //$pdf->SetXY(9, 69);
                //$pdf ->MultiCell(60,4,$pickOrderListRow['pickPointCode'], '', 'L', 0);
                $pdf->SetXY(3, 70);
                $pdf ->MultiCell(68,4,$pickOrderListRow['dropPointCode'], '', 'C', 0);
                
                //$pdf->UPC_A(19,62,$pickBarcode);        

                $pdf->Image('paperflyweb.jpg',14, 82, -200, 'JPG');



            }
            $pdf->Output();
        }
    } else {
        header("location: login");
    }
?>