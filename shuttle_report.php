<?php
include('session.php');
include('num_format.php');
include('config.php');
$error=''; // Variable To Store Error Message
if (mysqli_connect_errno()){
    $error = "Failed to connect to Database: " .mysqli_connect_errno(). " - ". mysqli_connect_error();
} else {
    if(isset($_GET['xxCode'])){
        $shuttleDate = strtotime($_GET['xxCode']);
        $shuttleEndTime = strtotime($_GET['shuttleEndTime']);
        $pointCode = trim($_GET['pointCode']);
        $shuttleDt = date("Y-m-d H:i", $shuttleDate);
        $shuttleEndTime = date("Y-m-d H:i", $shuttleEndTime);

        $shuttlePickSQL = "Select orderid, merOrderRef, tbl_order_details.merchantCode, tbl_merchant_info.merchantName, packagePrice from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') >= '$shuttleDt' and DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') <= '$shuttleEndTime' and dropPointCode = '$pointCode'and Shtl = 'Y'";
        $shuttlePickResult = mysqli_query($conn, $shuttlePickSQL);
        
        $pointSQL = "select pointCode, pointName from tbl_point_info where pointCode = '$pointCode'";
        $pointResult = mysqli_query($conn, $pointSQL);
        $pointRow = mysqli_fetch_array($pointResult);
    if ($user_type == 'Merchant'){
        $sql = "select * from tbl_merchant_info where merchantCode = '$user_code'";
        $result = mysqli_query($conn, $sql);
        foreach ($result as $row){
            $uName = $row['merchantName'];
        }
    } 
    if ($user_type == 'Employee'){
        $sql = "select * from tbl_employee_info where empCode = '$user_code'";
        $result = mysqli_query($conn, $sql);
        foreach ($result as $row){
            $uName = $row['empName'];
        }
    }
    if ($user_type == 'Administrator'){
        $uName = "Administrator";
    }
    }

if ($user_check!=''){    
//    require('fpdf/fpdf.php');
    require_once('draw.php');
    class PDF extends FPDF
    {  
        // Page header
        function Header()
        {
            //$this->Image('http://paperfly.com.bd/image/pad.jpg',0,0,210,300);
            $this->SetXY(55, 5);
            $this->SetFont('Helvetica','B',11);
            $this->SetTextColor(0,0,0);
            //$this->SetFillColor(96,96,96);
            $this ->MultiCell(100,4, "Shuttle Report", '', 'C', FALSE);

        }
        function Footer()
        {
	        $this->SetY(-0.001);
	        $this->SetFont('Arial','I',8);
	        $this->Cell(0,-30,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
        $pdf = new PDF('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf ->SetLineWidth(0.1);

        $pdf->SetFont('Helvetica','B',8);
        $pdf ->SetXY(10, 10);
        $pdf -> MultiCell(100,5, 'Shuttle Period :   '.date('d-M-Y H:i',$shuttleDate).'    To    '.date('d-M-Y H:i', strtotime($shuttleEndTime)),'','L',0);
        $pdf ->SetXY(10, 15);
        $pdf -> MultiCell(80,5, 'Point Name : '.$pointRow['pointCode'].' - '.$pointRow['pointName'],'','L',0);

        $pdf ->SetXY(10, 25);
        $pdf -> MultiCell(11,10, 'S/L No','LBTR','L',0);
        $pdf ->SetXY(21, 25);
        $pdf -> MultiCell(27,10, 'Paperfly Order ID','LBTR','L',0);
        $pdf ->SetXY(48, 25);
        $pdf -> MultiCell(32,10, 'Merchant Reference','LBTR','L',0);
        $pdf ->SetXY(80, 25);
        $pdf -> MultiCell(40,10, 'Merchant Name','LBTR','L',0);
        $pdf ->SetXY(120, 25);
        $pdf -> MultiCell(12,10, 'Price','LBTR','L',0);
        $pdf ->SetXY(132, 25);
        $pdf -> MultiCell(33,5, 'Received Confirmation Please (   )','LBTR','L',0);
        $pdf ->SetXY(143, 30);
        $pdf->SetFont('ZapfDingbats','',8);
        $pdf -> MultiCell(35,5,chr(51),'','L',0);
        $pdf->SetFont('Helvetica','B',8);
        $pdf ->SetXY(165, 25);
        $pdf -> MultiCell(40,10, 'Remarks','LBTR','L',0);
        $pdf->SetFont('Helvetica','',8);

        $pdf ->SetXY(10, 260);
        $pdf -> MultiCell(20,4, 'Shuttle By ','LBTR','L',0);
        $pdf ->SetXY(30, 260);
        $pdf -> MultiCell(50,4, $uName,'LBTR','L',0);
        $pdf ->SetXY(10, 264);
        $pdf -> MultiCell(20,4, 'Signature  ','LBTR','L',0);                 
        $pdf ->SetXY(30, 264);
        $pdf -> MultiCell(50,4, '','LBTR','L',0);
        $pdf ->SetXY(10, 268);
        $pdf -> MultiCell(20,4, 'Date       ','LBTR','L',0);                 
        $pdf ->SetXY(30, 268);
        $pdf -> MultiCell(50,4, '','LBTR','L',0);

        $pdf ->SetXY(130, 260);
        $pdf -> MultiCell(20,4, 'Received By ','LBTR','L',0);
        $pdf ->SetXY(150, 260);
        $pdf -> MultiCell(50,4, '','LBTR','L',0);
        $pdf ->SetXY(130, 264);
        $pdf -> MultiCell(20,4, 'Signature   ','LBTR','L',0);                 
        $pdf ->SetXY(150, 264);
        $pdf -> MultiCell(50,4, '','LBTR','L',0);
        $pdf ->SetXY(130, 268);
        $pdf -> MultiCell(20,4, 'Date        ','LBTR','L',0);                 
        $pdf ->SetXY(150, 268);
        $pdf -> MultiCell(50,4, '','LBTR','L',0);

        $itemCount = 1;
        $yPosition = 35;
        $fillColor = FALSE;
        $oddRow = 0;
        foreach($shuttlePickResult as $shuttlePickRow){
            if ($lineCount == 55) {
                $pdf->AddPage();
                $lineCount = 0;
                $yPosition = 35;
                $pdf->SetFont('Helvetica','B',8);
                $pdf ->SetXY(10, 10);
                $pdf -> MultiCell(40,5, 'Order Date : '.date("d-M-Y", $shuttleDate),'','L',0);
                $pdf ->SetXY(10, 15);
                $pdf -> MultiCell(80,5, 'Point Name : '.$pointRow['pointCode'].' - '.$pointRow['pointName'],'','L',0);

                $pdf ->SetXY(10, 25);
                $pdf -> MultiCell(11,10, 'S/L No','LBTR','L',0);
                $pdf ->SetXY(21, 25);
                $pdf -> MultiCell(27,10, 'Paperfly Order ID','LBTR','L',0);
                $pdf ->SetXY(48, 25);
                $pdf -> MultiCell(32,10, 'Merchant Reference','LBTR','L',0);
                $pdf ->SetXY(80, 25);
                $pdf -> MultiCell(40,10, 'Merchant Name','LBTR','L',0);
                $pdf ->SetXY(120, 25);
                $pdf -> MultiCell(12,10, 'Price','LBTR','L',0);
                $pdf ->SetXY(132, 25);
                $pdf -> MultiCell(33,5, 'Received Confirmation Please (   )','LBTR','L',0);
                $pdf ->SetXY(143, 30);
                $pdf->SetFont('ZapfDingbats','',8);
                $pdf -> MultiCell(35,5,chr(51),'','L',0);
                $pdf->SetFont('Helvetica','B',8);
                $pdf ->SetXY(165, 25);
                $pdf -> MultiCell(40,10, 'Remarks','LBTR','L',0);
                $pdf->SetFont('Helvetica','',7);


                $pdf ->SetXY(10, 260);
                $pdf -> MultiCell(20,4, 'Shuttle By ','LBTR','L',0);
                $pdf ->SetXY(30, 260);
                $pdf -> MultiCell(50,4, $uName,'LBTR','L',0);
                $pdf ->SetXY(10, 264);
                $pdf -> MultiCell(20,4, 'Signature  ','LBTR','L',0);                 
                $pdf ->SetXY(30, 264);
                $pdf -> MultiCell(50,4, '','LBTR','L',0);
                $pdf ->SetXY(10, 268);
                $pdf -> MultiCell(20,4, 'Date       ','LBTR','L',0);                 
                $pdf ->SetXY(30, 268);
                $pdf -> MultiCell(50,4, '','LBTR','L',0);

                $pdf ->SetXY(130, 260);
                $pdf -> MultiCell(20,4, 'Received By ','LBTR','L',0);
                $pdf ->SetXY(150, 260);
                $pdf -> MultiCell(50,4, '','LBTR','L',0);
                $pdf ->SetXY(130, 264);
                $pdf -> MultiCell(20,4, 'Signature   ','LBTR','L',0);                 
                $pdf ->SetXY(150, 264);
                $pdf -> MultiCell(50,4, '','LBTR','L',0);
                $pdf ->SetXY(130, 268);
                $pdf -> MultiCell(20,4, 'Date        ','LBTR','L',0);                 
                $pdf ->SetXY(150, 268);
                $pdf -> MultiCell(50,4, '','LBTR','L',0);             
                            
                //$yPosition = $yPosition +4;
                $pdf->SetXY(10,$yPosition);
                $pdf->MultiCell(11,4, $itemCount, 'LBRT', 'R', 0);
                $pdf->SetXY(21,$yPosition);
                $pdf->MultiCell(27,4, $shuttlePickRow['orderid'], 'LBRT', 'C', 0);
                $pdf->SetXY(48,$yPosition);
                $pdf->MultiCell(32,4, $shuttlePickRow['merOrderRef'], 'LBRT', 'C', 0);
                $pdf->SetXY(80,$yPosition);
                $pdf->MultiCell(40,4, $shuttlePickRow['merchantName'], 'LBRT', 'L', 0);
                $pdf->SetXY(120,$yPosition);
                $pdf->MultiCell(12,4, num_to_format(round($shuttlePickRow['packagePrice'])), 'LBRT', 'R', 0);
                $pdf->SetXY(132,$yPosition);
                $pdf->MultiCell(33,4, '            ', 'LBRT', 'L', 0);
                $pdf->SetXY(165,$yPosition);
                $pdf->MultiCell(40,4, '            ', 'LBRT', 'L', 0);
                                        
            } else {
                $pdf->SetFont('Helvetica','',7);
                $pdf->SetFillColor(216,216,216);
                if($oddRow == 0){
                    $fillColor = FALSE;
                } else {
                    $fillColor = TRUE;
                }
                $pdf->SetXY(10,$yPosition);
                $pdf->MultiCell(11,4, $itemCount, 'LBRT', 'R', $fillColor);
                $pdf->SetXY(21,$yPosition);
                $pdf->MultiCell(27,4, $shuttlePickRow['orderid'], 'LBRT', 'C', $fillColor);
                $pdf->SetXY(48,$yPosition);
                $pdf->MultiCell(32,4, $shuttlePickRow['merOrderRef'], 'LBRT', 'C', $fillColor);
                $pdf->SetXY(80,$yPosition);
                $pdf->MultiCell(40,4, $shuttlePickRow['merchantName'], 'LBRT', 'L', $fillColor);
                $pdf->SetXY(120,$yPosition);
                $pdf->MultiCell(12,4, num_to_format(round($shuttlePickRow['packagePrice'])), 'LBRT', 'R', $fillColor);                
                $pdf->SetXY(132,$yPosition);
                $pdf->MultiCell(33,4, '            ', 'LBRT', 'L', $fillColor);
                $pdf->SetXY(165,$yPosition);
                $pdf->MultiCell(40,4, '            ', 'LBRT', 'L', $fillColor);
            }
            $yPosition = $yPosition +4;
            $itemCount++;
            $oddRow ++;
            $lineCount++;
            if($oddRow >1){
                $oddRow = 0;
            }
        }

    $pdf ->output(); 
} else {
    header("location:index.php");
}
}
mysqli_close($conn);
?>