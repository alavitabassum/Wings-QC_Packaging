<?php
    include('session.php');
    if($user_check !=''){
        include('config.php');

        $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and barcodeWarehouse = 'Y'"));
        if ($userPrivCheckRow['barcodeWarehouse'] != 'Y'){
            exit();
        }

        require('ean13_upca_barcodeV2.php');

    class PDF extends FPDF
    {    
        // Page header
        function Footer()
        {
	        // Position at 1.5 cm from bottom
	        $this->SetY(-15);
	        // Arial italic 8
	        $this->SetFont('Arial','I',5);
	        // Page number
	        $this->Cell(0,30,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

        if (isset($_GET['barcodeQty'])){

            $barcodeQty = trim($_GET['barcodeQty']);

            if($barcodeQty < 999){
                $pdf=new PDF_EAN13('L','mm',array(30,42));
                $pdf->SetTitle('Barcode Warehouse');
                $barcodePrefix = date('yHis', strtotime('+6 hours'));
                for($i = 1; $i <= $barcodeQty; $i++){
                    $pdf->AddPage();
                    $pdf->Image('paperfly.jpg',0, -21, -300, 'JPG');
                    if(strlen($i) == 1){
                        $i = '00'.$i;
                    }
                    if(strlen($i) == 2){
                        $i = '0'.$i;
                    }
                    $pdf->UPC_A(5,9,$barcodePrefix.$i);
                   
                }
                $pdf->Output();                
            } else {
                $pdf=new PDF_EAN13('L','mm',array(37,38));
                $pdf->SetTitle('Barcode Warehouse');
                $barcodePrefix = date('yHis', strtotime('+6 hours'));
                $pdf->AddPage();
                $pdf->SetFont('Helvetica','',10);
                $pdf->SetXY(9, 10);
                $pdf ->MultiCell(60,4,'Error', '', 'L', 0);
                $pdf->Output();                
            }

        }
    } else {
        header("location: login");
    }
?>