<?php
include('session.php');
// checks user loggin or not
if ($user_check!=''){    
    $ordermaxid = trim($_GET['ordermaxid']);
    $recmaxid = trim($_GET['recmaxid']);
    $merchantname = trim($_GET['merchantname']);
    $orderDate = date("d-M-Y", strtotime(trim($_GET['orderDate'])));
    include('fpdf/fpdf.php');
    class PDF extends FPDF
    {    
        // Page header
        function Header()
        {
        // Logo
	         $this->Image('C:\wamp\www\image\paperfly.png',10,6,30);
	        // Arial bold 15
	        $this->SetFont('Arial','B',15);
	        // Move to the right
	        $this->Cell(1);
	        // Title
            $this ->Cell (0, 0, "PaperFly Ltd.",0,1,'C');
            $this ->ln(6);
            $this ->SetFont("Arial","",10);
            $this ->Cell (0,0, "Batch Order Process Report",0,1,'C');
            $this ->ln(5);
        }
        // Page footer
        function Footer()
        {
	        // Position at 1.5 cm from bottom
	        $this->SetY(-15);
	        // Arial italic 8
	        $this->SetFont('Arial','I',8);
	        // Page number
	        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
    $pdf = new PDF('L','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    //set initial y axis position per page
    $y_axis_initial = 25;
    //Set Row hieght
    $row_height = 6;
    //print column titles for the actual page
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetY($y_axis_initial-6);
    $pdf->Cell(35,6,'Order Date :'. $orderDate, 0, 0, 'L',1);
    $pdf->SetX(50);
    $pdf->Cell(35,6,'Merchant Name : '. $merchantname, 0, 0, 'L',1);
    $pdf->SetFillColor(80,80,80);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(10);
    $pdf->Cell(15,6,'Order ID', 1, 0, 'L',1);
    $pdf->Cell(60,6,'Product Detials', 1, 0, 'L',1);
    $pdf->Cell(15,6,'Size', 1, 0, 'L',1);
    $pdf->Cell(15,6,'Weight',1,0,'L',1);
    $pdf->Cell(15,6,'Price',1,0,'C',1);
    $pdf->Cell(15,6,'Charge',1,0,'C',1);
    $pdf->Cell(15,6,'Total',1,0,'C',1);
    $pdf->Cell(30,6,'Customer Name',1,0,'L',1);
    $pdf->Cell(40,6,'Customer Address',1,0,'L',1);
    $pdf->Cell(20,6,'Thana',1,0,'L',1);
    $pdf->Cell(20,6,'District',1,0,'L',1);
    $pdf->Cell(20,6,'Phone',1,0,'L',1);
    $pdf->SetTextColor(0, 0, 0);
    $y_axis = $y_axis_initial + $row_height;
    $connection = mysqli_connect("localhost", "root", "root123", "db_paperfly");
    if (mysqli_connect_errno()){
        $error = "ERROR: ".mysqli_connect_errno(). " - ". mysqli_connect_error();
    } else {
        // Initialize per page line counter variable
        $l = 0;
        // Set maximum number of line per page
        $maxline = 15;
        $colorformat =0;
        $sql ="select orderId, productDetails, size, weight, productPrice, 
        deliveryCharge, customerName, customerAddress, tbl_thana_info.thanaName, 
        tbl_district_info.districtName, phone from tbl_order_details, tbl_thana_info, 
        tbl_district_info where tbl_order_details.thanaId = tbl_thana_info.thanaId and 
        tbl_order_details.districtId = tbl_district_info.districtId and orderId between '$ordermaxid' and '$recmaxid'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_array($result)) {
                    if ($l == $maxline) {
                        $pdf->AddPage();
                        //print column titles for the actual page
                        $pdf->SetFillColor(80,80,80);
                        $pdf->SetTextColor(255, 255, 255);
                        $pdf->SetFont('Arial', 'B', 8);
                        $pdf->SetY($y_axis_initial);
                        $pdf->SetX(10);
                        $pdf->Cell(15,6,'Order ID', 1, 0, 'L',1);
                        $pdf->Cell(60,6,'Product Detials', 1, 0, 'L',1);
                        $pdf->Cell(15,6,'Size', 1, 0, 'L',1);
                        $pdf->Cell(15,6,'Weight',1,0,'L',1);
                        $pdf->Cell(15,6,'Price',1,0,'C',1);
                        $pdf->Cell(15,6,'Del. Charge',1,0,'C',1);
                        $pdf->Cell(15,6,'Total',1,0,'C',1);
                        $pdf->Cell(30,6,'Customer Name',1,0,'L',1);
                        $pdf->Cell(40,6,'Customer Address',1,0,'L',1);
                        $pdf->Cell(20,6,'Thana',1,0,'L',1);
                        $pdf->Cell(20,6,'District',1,0,'L',1);
                        $pdf->Cell(20,6,'Phone',1,0,'L',1);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->SetTextColor(0, 0, 0);
                        //Go to next row
                        $y_axis = $y_axis_initial + $row_height;
                    
                        //Set $i variable to 0 (first row)
                        $l = 0;
                    } else {
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->SetTextColor(0, 0, 0);
                    }
                    $orderId = $row['orderId'];
                    $productDetails = $row['productDetails'];
                    $size = $row['size'];
                    $weight = $row['weight'];
                    $productPrice = $row['productPrice'];
                    $deliveryCharge = $row['deliveryCharge'];
                    $customerName = $row['customerName'];
                    $customerAddress = $row['customerAddress'];
                    $thanaName = $row['thanaName'];
                    $districtName = $row['districtName'];
                    $phone = $row['phone'];
                    $pdf->SetXY(25,$y_axis);
                    $pdf->SetFillColor(255,255,255);
                    $orderIdStringValue = $pdf->GetStringWidth($orderId);
                    $productDetailsStringValue = $pdf->GetStringWidth($productDetails);
                    $sizeStringValue = $pdf->GetStringWidth($size);
                    $weightStringValue = $pdf->GetStringWidth($weight);
                    $productPriceStringValue = $pdf->GetStringWidth($productPrice);
                    $deliveryChargeStringValue = $pdf->GetStringWidth($deliveryCharge);
                    $customerNameStringValue = $pdf->GetStringWidth($customerName);
                    $customerAddressStringValue = $pdf->GetStringWidth($customerAddress);
                    $thanaNameStringValue = $pdf->GetStringWidth($thanaName);
                    $districtNameStringValue = $pdf->GetStringWidth($districtName);
                    $phoneStringValue = $pdf->GetStringWidth($phone);
                    $maxstringvalue =max($orderIdStringValue, $productDetailsStringValue, $sizeStringValue, $weightStringValue, $productPriceStringValue, $deliveryChargeStringValue, $customerNameStringValue, $customerAddressStringValue, $thanaNameStringValue, $districtNameStringValue, $phoneStringValue);
                    if (ceil($orderIdStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/30)*6;
                    }
                    if (ceil($productDetailsStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/30)*6;
                    }
                    if (ceil($sizeStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/50)*6;
                    }
                    if (ceil($weightStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/65)*6;
                    }
                    if (ceil($productPriceStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    if (ceil($deliveryChargeStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/30)*6;
                    }
                    if (ceil($customerNameStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    if (ceil($customerAddressStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    if (ceil($thanaNameStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    if (ceil($districtNameStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    if (ceil($phoneStringValue)==ceil($maxstringvalue)){
                        $row_height = ceil($maxstringvalue/25)*6;
                    }
                    $pdf->SetXY(10,$y_axis);
                         $pdf->MultiCell(15,6,$orderId,0,'L',TRUE);                   
                    $pdf->SetXY(25,$y_axis);
                        $pdf->MultiCell(60,6,$productDetails,0,'L',TRUE);
                    $pdf->SetXY(85,$y_axis);
                        $pdf->MultiCell(15,6,$size,0,'L',TRUE);
                    $pdf->SetXY(100,$y_axis);
                        $pdf->MultiCell(15,6,$weight,0,'L',TRUE);
                    $pdf->SetXY(115,$y_axis);
                        $pdf->MultiCell(15,6,$productPrice,0,'R',TRUE);
                    $pdf->SetXY(130,$y_axis);
                        $pdf->MultiCell(15,6,$deliveryCharge,0,'R',TRUE);
                    $pdf->SetXY(145,$y_axis);
                        $pdf->MultiCell(15,6,($productPrice+$deliveryCharge),0,'R',TRUE);
                    $pdf->SetXY(160,$y_axis);
                        $pdf->MultiCell(30,6,$customerName,0,'L',TRUE);
                    $pdf->SetXY(190,$y_axis);
                        $pdf->MultiCell(40,6,$customerAddress,0,'L',TRUE);
                    $pdf->SetXY(230,$y_axis);
                        $pdf->MultiCell(20,6,$thanaName,0,'L',TRUE);
                    $pdf->SetXY(250,$y_axis);
                        $pdf->MultiCell(20,6,$districtName,0,'L',TRUE);
                    $pdf->SetXY(270,$y_axis);
                        $pdf->MultiCell(20,6,$phone,0,'L',TRUE);
                    $pdf->SetXY(10,$y_axis);
                    $pdf->line(290, $y_axis, 10, $y_axis);
                    if ($row_height>12){
                        $y_axis = $y_axis + $row_height+6;    
                    } else{
                        $y_axis = $y_axis + $row_height;
                    }
                    $l = $l + 1;
            }
            $pdf->line(290, $y_axis, 10, $y_axis);
        }
        mysqli_close($connection);
       }

    $pdf ->output(); 
} else {
    // Redirect ot login page
    header("location:main");
}
?>