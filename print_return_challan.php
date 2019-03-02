<?php
    include('session.php');
    include('num_format.php');
    include('number_to_word.php');
    include('config.php');
    if ($user_check!=''){
        require('fpdf/fpdf.php');
        class PDF extends FPDF
        {    
            // Page header
            function Header()
            {
            // Logo
                $this->Image('http://paperflybd.com/image/paperfly.jpg',150,-15,60,60);
	            //$this->Image('http://localhost:8000/image/pad.jpg',0,0,210,300);
	            // Arial bold 15
	            //$this->SetFont('Arial','B',15);
	            // Move to the right
	            //$this->Cell(1);
	            //// Title
                //$this ->Cell (0, 0, "S.S. Corporation",0,1,'C');
                //$this ->ln(6);
                //$this ->SetFont("Arial","",10);
                //$this ->Cell (0,0, "Mona Complex, Babu Bazar, Dhaka",0,1,'C');
                $this ->ln(15);
                $this ->SetFont("Arial","B",12);
                $this ->Cell (0, 0, "Return Orders Delivery Challan",0,1,'C');
                //$this ->SetFont("Arial","",8);
                //$this ->Cell (0,0,"Dhaka North DCC Market, Shop No. F-44 (1st Floor), Gulshan - 2, Dhaka - 1212.",0,1,"C");
	            //Line break
	            $this->Ln(5);
            }
            // Page footer
            function Footer()
            {
	            // Position at 1.5 cm from bottom
	            //$this->SetY(-15);
	            // Arial italic 8
	            //$this->SetFont('Arial','I',8);
	            // Page number
	            //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }
        }


        if(isset($_GET['xxCode'])){
            $retInv = trim($_GET['xxCode']);

            $challanDetailSQL = "SELECT tbl_order_details.merchantCode, v_merchant.merchantCode, v_merchant.merchantName, v_merchant.address, v_merchant.thanaName, v_merchant.districtName, v_merchant.companyPhone, orderid, merOrderRef, retInv, retInvDate, CONCAT(if(retReason is not null, retReason, ''),' ',if(retRem is not null, retRem, ''),' ',if(partialReason is not null, partialReason, '')) as retReason, retChallanBy, IF(RetTime is not null, DATE_FORMAT(RetTime,'%d-%b-%Y'),DATE_FORMAT(partialTime,'%d-%b-%Y')) as retDate, v_employee.empName, v_employee.designation, v_employee.contactNumber FROM `tbl_order_details` left join (SELECT merchantCode, merchantName, address, tbl_thana_info.thanaName, tbl_district_info.districtName, companyPhone FROM tbl_merchant_info left join tbl_thana_info on tbl_merchant_info.thanaid = tbl_thana_info.thanaId left join tbl_district_info on tbl_merchant_info.districtid = tbl_district_info.districtId WHERE merchantCode = (select distinct merchantCode from tbl_order_details where retInv = '$retInv')) as v_merchant on tbl_order_details.merchantCode = v_merchant.merchantCode left join (SELECT tbl_user_info.userName, tbl_employee_info.empName, tbl_designation_info.Name as designation, tbl_employee_info.contactNumber, tbl_user_info.merchEmpCode FROM tbl_user_info left join tbl_employee_info on tbl_user_info.merchEmpCode = tbl_employee_info.empCode left join tbl_designation_info on tbl_employee_info.desigid = tbl_designation_info.desigid WHERE userName = (select distinct retChallanBy from tbl_order_details WHERE retInv = '$retInv') ) as v_employee on tbl_order_details.retChallanBy = v_employee.userName WHERE retInv = '$retInv'";
            $challanDetailResult = mysqli_query($conn, $challanDetailSQL);
            $challanHeaderRow = mysqli_fetch_array($challanDetailResult);
            
            $pdf = new PDF('P','mm','A4');
            $pdf->AliasNbPages();
            $pdf->AddPage();

            $pdf ->SetFont("Arial","B",8);
            $pdf->SetXY(10,25);
            $pdf->MultiCell(30,5,'Challan Date. :','','L',0);
            $pdf->SetXY(10,30);
            $pdf->MultiCell(30,5,'Challan No. :','','L',0);
            $pdf->SetXY(10,35);
            $pdf->MultiCell(30,5,'Merchant Name :','','L',0);
            $pdf->SetXY(10,40);
            $pdf->MultiCell(30,5,'Address :','','L',0);
            $pdf->SetXY(10,45);
            $pdf->MultiCell(30,5,'Phone :','','L',0);

            $pdf ->SetFont("Arial","",8);
            $pdf->SetXY(35,25);
            $pdf->MultiCell(40,5,date('d-M-Y',strtotime($challanHeaderRow['retInvDate'])),'','L',0);
            $pdf->SetXY(35,30);
            $pdf->MultiCell(40,5,$challanHeaderRow['retInv'],'','L',0);
            $pdf->SetXY(35,35);
            $pdf->MultiCell(60,5,$challanHeaderRow['merchantName'],'','L',0);
            $pdf->SetXY(35,40);
            $pdf->MultiCell(150,5,$challanHeaderRow['address'].','.$challanHeaderRow['thanaName'].','.$challanHeaderRow['districtName'],'','L',0);
            $pdf->SetXY(35,45);
            $pdf->MultiCell(30,5,$challanHeaderRow['companyPhone'],'','L',0);

            $pdf ->SetFont("Arial","B",7);
            $pdf->SetXY(11,50);
            $pdf->MultiCell(11,5,'SL. NO.','LBTR','C',0);
            $pdf->SetXY(22,50);
            $pdf->MultiCell(28,5,'Order ID','LBTR','C',0);
            $pdf->SetXY(50,50);
            $pdf->MultiCell(40,5,'Merchant Order Ref.','LBTR','C',0);
            $pdf->SetXY(90,50);
            $pdf->MultiCell(20,5,'Return Date','LBTR','C',0);
            $pdf->SetXY(110,50);
            $pdf->MultiCell(60,5,'Return Reason','LBTR','C',0);
            $pdf->SetXY(170,50);
            $pdf->MultiCell(31,5,'Remarks','LBTR','C',0);
            $pdf ->SetFont("Arial","",8);

            $pdf->Line(11,55, 11,185);
            $pdf->Line(22,55, 22,185);
            $pdf->Line(50,55, 50,185);            
            $pdf->Line(90,55, 90,185);
            $pdf->Line(110,55, 110,185);
            $pdf->Line(170,55, 170,185);
            $pdf->Line(201,55, 201,185);

            $pdf->Line(11,185, 201,185);

            $lineCount = 1; 
            $yPos = 55;
            foreach($challanDetailResult as $challanDetailRow){
                if($lineCount == 26){
                    $pdf->AddPage();
                    
                    $yPos = 55;

                    $pdf ->SetFont("Arial","B",7);
                    $pdf->SetXY(11,50);
                    $pdf->MultiCell(11,5,'SL. NO.','LBTR','C',0);
                    $pdf->SetXY(22,50);
                    $pdf->MultiCell(28,5,'Order ID','LBTR','C',0);
                    $pdf->SetXY(50,50);
                    $pdf->MultiCell(40,5,'Merchant Order Ref.','LBTR','C',0);
                    $pdf->SetXY(90,50);
                    $pdf->MultiCell(20,5,'Return Date','LBTR','C',0);
                    $pdf->SetXY(110,50);
                    $pdf->MultiCell(60,5,'Return Reason','LBTR','C',0);
                    $pdf->SetXY(170,50);
                    $pdf->MultiCell(31,5,'Return Reason','LBTR','C',0);
                    $pdf ->SetFont("Arial","",8);

                    $pdf->Line(11,55, 11,185);
                    $pdf->Line(22,55, 22,185);
                    $pdf->Line(50,55, 50,185);            
                    $pdf->Line(90,55, 90,185);
                    $pdf->Line(110,55, 110,185);
                    $pdf->Line(170,55, 170,185);
                    $pdf->Line(201,55, 201,185);

                    $pdf->Line(11,185, 201,185);

                    $pdf->SetXY(11,$yPos);
                    $pdf->MultiCell(11,5,$lineCount,'','R',0);
                    $pdf->SetXY(22,$yPos);
                    $pdf->MultiCell(28,5,$challanDetailRow['orderid'],'','C',0);
                    $pdf->SetXY(50,$yPos);
                    $pdf->MultiCell(40,5,$challanDetailRow['merOrderRef'],'','C',0);
                    $pdf->SetXY(90,$yPos);
                    $pdf->MultiCell(20,5,$challanDetailRow['retDate'],'','C',0);
                    $pdf ->SetFont("Arial","",7);
                    $pdf->SetXY(110,$yPos);
                    $pdf->MultiCell(62,5,substr($challanDetailRow['retReason'], 0, 50),'','L',0);
                    $pdf ->SetFont("Arial","",8);

                } else {
                    $pdf->SetXY(11,$yPos);
                    $pdf->MultiCell(11,5,$lineCount,'','R',0);
                    $pdf->SetXY(22,$yPos);
                    $pdf->MultiCell(28,5,$challanDetailRow['orderid'],'','C',0);
                    $pdf->SetXY(50,$yPos);
                    $pdf->MultiCell(40,5,$challanDetailRow['merOrderRef'],'','C',0);
                    $pdf->SetXY(90,$yPos);
                    $pdf->MultiCell(20,5,$challanDetailRow['retDate'],'','C',0);
                    $pdf ->SetFont("Arial","",7);
                    $pdf->SetXY(110,$yPos);
                    $pdf->MultiCell(62,5,substr($challanDetailRow['retReason'], 0, 50),'','L',0);
                    $pdf ->SetFont("Arial","",8);
                }
                $lineCount++;
                $yPos = $yPos+5;
            }


            $pdf ->SetFont("Arial","B",9);
            $pdf->SetXY(10,190);
            $pdf->MultiCell(37,5,'Total returned orders : ','','L',0);
            $pdf ->SetFont("Arial","",9);
            $pdf->SetXY(47,190);
            $pdf->MultiCell(35,5,($lineCount - 1).' ('.int_to_words($lineCount-1).')','','L',0);

            $pdf ->SetFont("Arial","",8);
            $pdf->SetXY(10,210);
            $pdf->MultiCell(50,5,'Received above returned orders','','L',0);
            $pdf->SetXY(10,230);
            $pdf->MultiCell(50,5,'Authorized Signature','','L',0);
            $pdf->SetXY(10,235);
            $pdf->MultiCell(50,5,'Name','','L',0);
            $pdf->SetXY(10,240);
            $pdf->MultiCell(50,5,'Contact Number','','L',0);
            $pdf->SetXY(10,245);
            $pdf->MultiCell(50,5,'Company Seal','','L',0);


            $pdf->SetXY(90,210);
            $pdf->MultiCell(50,5,'Checked By','','L',0);
            $pdf->SetXY(90,230);
            $pdf->MultiCell(50,5,'Signature','','L',0);
            $pdf->SetXY(90,234);
            $pdf->MultiCell(50,5,'Name','','L',0);
            $pdf->SetXY(90,238);
            $pdf->MultiCell(50,5,'Contact Number','','L',0);



            $pdf->SetXY(165,210);
            $pdf->MultiCell(50,5,'Issued By','','L',0);
            $pdf->SetXY(165,230);
            $pdf->MultiCell(50,5,$challanHeaderRow['empName'],'','L',0);
            $pdf->SetXY(165,234);
            $pdf->MultiCell(50,5,$challanHeaderRow['designation'],'','L',0);
            $pdf->SetXY(165,238);
            $pdf->MultiCell(50,5,'Phone : '.$challanHeaderRow['contactNumber'],'','L',0);
            $pdf->SetXY(165,242);
            $pdf->MultiCell(50,5,'Paperfly Private Limited','','L',0);


            mysqli_close($conn);
            $pdf ->output();
        }
    } else {
        header("location: main");
    }    
?>