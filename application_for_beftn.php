<?php
include('session.php');
include('num_format.php');
include('number_to_word.php');
include('config.php');
$error=''; // Variable To Store Error Message
if (mysqli_connect_errno()){
    $error = "Failed to connect to Database: " .mysqli_connect_errno(). " - ". mysqli_connect_error();
} else {
    if(isset($_GET['xxCode'])){
        $beftnID = trim($_GET['xxCode']);
        $beftnListSQL = "select substr(invNum,12,8) as refNo, invSeq, accountName, accountNumber, bankName, branch, routeNumber, paidAmt from tbl_beftn_info where beftnID = $beftnID";
        $beftnListResult = mysqli_query($conn, $beftnListSQL);

        $beftnTotAmtSQL = "select sum(paidAmt) as paidAmt from tbl_beftn_info where beftnID = $beftnID";
        $beftnTotAmtResult = mysqli_query($conn, $beftnTotAmtSQL);
        $beftnTotAmtRow = mysqli_fetch_array($beftnTotAmtResult);
        $beftnTotAmt = $beftnTotAmtRow['paidAmt'];


        //$beftnAmountSQL = "select sum(paidAmt) as amount from tbl_beftn_info where beftnID = $beftnID";
        //$beftnAmountResult = mysqli_query($conn, $beftnAmountSQL);
        //$beftnAmountRow = mysqli_fetch_array($beftnAmountResult);
        //$beftnAmount = $beftnAmountRow['amount'];
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
            //$this->Image('http://paperflybd.com/image/pad.jpg',0,0,210,300);
	        //$this->Image('http://localhost:8000/image/pad.jpg',0,0,210,300);
	        // Arial bold 15
	        //$this->SetFont('Arial','B',15);
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
	        $this->SetY(-10);
	        // Arial italic 8
	        $this->SetFont('Arial','I',8);
	        // Page number
	        $this->Cell(0,-25,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

        $pdf->SetFont('Helvetica','',8);

        $pdf->SetXY(19, 20);
        $pdf->MultiCell(30,3,'Date:'. date('d-M-Y'),'','L',0);
        $pdf->text(20, 30, 'To');
        $pdf->text(20, 35, 'The Head of Payroll & CP');
        $pdf->text(20, 40, 'Eastern Bank Ltd.');
        $pdf->text(20, 45, 'Dhaka');

        $pdf->text(20, 53, 'Subject: Requesting to transfer payment from A/C #1111350161119');

        $pdf->text(20, 60, 'Dear Sir,');
        $pdf->text(20, 65, 'We would like to inform you that we are operating an A/C #1111350161119 with your bank.');
        $pdf->SetXY(19, 67);
        $pdf ->MultiCell(170,5,"We are requesting you to transfer a total amount of BDT ".num_to_format(round($beftnTotAmt))." (".int_to_words($beftnTotAmt).") to the respective accounts according to mentioned below:", '', 'L', 0);         

        $pdf->SetFont('Helvetica','B',7.5);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetLineWidth(0.20);
        $pdf->SetXY(20, 77);
        $pdf->MultiCell(20,6,"Reference No.",'LTRB','L',0);
        $pdf->SetXY(40, 77);
        $pdf->MultiCell(40,6,"Receiver/Beneficiary's name",'LTRB','L',0);
        $pdf->SetXY(80, 77);
        $pdf->MultiCell(30,3,"Receiver/Beneficiary's a/c no:",'LTRB','L',0);
        $pdf->SetXY(110, 77);
        $pdf->MultiCell(30,6,"Bank Name",'LTRB','L',0);
        $pdf->SetXY(140, 77);
        $pdf->MultiCell(20,6,"Branch Name",'LTRB','L',0);
        $pdf->SetXY(160, 77);
        $pdf->MultiCell(20,3,"Branch Routing No:",'LTRB','L',0);
        $pdf->SetXY(180, 77);
        $pdf->MultiCell(20,6,"Amount",'LTRB','L',0);
        $pdf->SetFont('Helvetica','',7.5);

        $pdf->Line(160,255,200,255);
        $pdf->SetXY(165, 255);
        $pdf ->MultiCell(40,5,"Md. Razibul Islam", '', 'L', 0); 
        $pdf->SetXY(165, 259);
        $pdf ->MultiCell(40,5,"Chief Operating Officer", '', 'L', 0);                         
        $pdf->SetXY(165, 263);
        $pdf ->MultiCell(40,5,"Paperfly Private Limited", '', 'L', 0); 

        $beftnAmount = 0;
        $lineCount = 1;
        $initYpos = 83;
        $yPos = 83;
        $pdf->Line(20,$yPos,200,$yPos);
        foreach($beftnListResult as $beftnListRow){
            if($lineCount == 26){

                $pdf->Line(20,$initYpos,20,$yPos);
                $pdf->Line(40,$initYpos,40,$yPos);
                $pdf->Line(80,$initYpos,80,$yPos);
                $pdf->Line(110,$initYpos,110,$yPos);
                $pdf->Line(140,$initYpos,140,$yPos);
                $pdf->Line(160,$initYpos,160,$yPos);
                $pdf->Line(180,$initYpos,180,$yPos);
                $pdf->Line(200,$initYpos,200,$yPos);
                
                $pdf->AddPage();

                $lineCount = 1;
                $initYpos = 40;
                $yPos = 46;
                $pdf->SetXY(20, 40);
                $pdf->MultiCell(20,6,"Reference No.",'LTRB','L',0);
                $pdf->SetXY(40, 40);
                $pdf->MultiCell(40,6,"Receiver/Beneficiary's name",'LTRB','L',0);
                $pdf->SetXY(80, 40);
                $pdf->MultiCell(30,3,"Receiver/Beneficiary's a/c no:",'LTRB','L',0);
                $pdf->SetXY(110, 40);
                $pdf->MultiCell(30,6,"Bank Name",'LTRB','L',0);
                $pdf->SetXY(140, 40);
                $pdf->MultiCell(20,6,"Branch Name",'LTRB','L',0);
                $pdf->SetXY(160, 40);
                $pdf->MultiCell(20,3,"Branch Routing No:",'LTRB','L',0);
                $pdf->SetXY(180, 40);
                $pdf->MultiCell(20,6,"Amount",'LTRB','L',0);
                $pdf->SetFont('Helvetica','',7.5);
                $pdf->SetXY(20, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['refNo'].'-'.$beftnListRow['invSeq'],'','L',0);
                $pdf->SetXY(40, $yPos);
                $pdf->MultiCell(40,3,$beftnListRow['accountName'],'','L',0);
                $pdf->SetXY(80, $yPos);
                $pdf->MultiCell(30,3,$beftnListRow['accountNumber'],'','L',0);
                $pdf->SetXY(110, $yPos);
                $pdf->MultiCell(30,3,$beftnListRow['bankName'],'','L',0);
                $pdf->SetXY(140, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['branch'],'','L',0);
                $pdf->SetXY(160, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['routeNumber'],'','L',0);
                $pdf->SetXY(180, $yPos);
                $pdf->MultiCell(20,3,num_to_format(round($beftnListRow['paidAmt'])),'','R',0);

                $yPos = $yPos + 6;

                $pdf->Line(160,255,200,255);
                $pdf->SetXY(165, 255);
                $pdf ->MultiCell(40,5,"Md. Razibul Islam", '', 'L', 0); 
                $pdf->SetXY(165, 259);
                $pdf ->MultiCell(40,5,"Chief Operating Officer", '', 'L', 0);                         
                $pdf->SetXY(165, 263);
                $pdf ->MultiCell(40,5,"Paperfly Private Limited", '', 'L', 0);  

            } else {
                $pdf->SetXY(20, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['refNo'].'-'.$beftnListRow['invSeq'],'','L',0);
                $pdf->SetXY(40, $yPos);
                $pdf->MultiCell(40,3,$beftnListRow['accountName'],'','L',0);
                $pdf->SetXY(80, $yPos);
                $pdf->MultiCell(30,3,$beftnListRow['accountNumber'],'','L',0);
                $pdf->SetXY(110, $yPos);
                $pdf->MultiCell(30,3,$beftnListRow['bankName'],'','L',0);
                $pdf->SetXY(140, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['branch'],'','L',0);
                $pdf->SetXY(160, $yPos);
                $pdf->MultiCell(20,3,$beftnListRow['routeNumber'],'','L',0);
                $pdf->SetXY(180, $yPos);
                $pdf->MultiCell(20,3,num_to_format(round($beftnListRow['paidAmt'])),'','R',0);
                               
                //$lineCount++;
                $pdf->Line(20,$yPos,200,$yPos);
                $yPos = $yPos + 6;                

            }
            $lineCount = $lineCount+1;
            $beftnAmount = $beftnAmount + $beftnListRow['paidAmt'];
            $pdf->Line(20,$yPos,200,$yPos);
        }
        $pdf->Line(20,$yPos,200,$yPos);
        $pdf->SetXY(160, $yPos);
        $pdf->MultiCell(20,3,"Total",'','C',0);
        $pdf->SetXY(180, $yPos);
        $pdf->MultiCell(20,3,num_to_format(round($beftnAmount)),'','R',0);

        $pdf->Line(20,$initYpos,20,$yPos);
        $pdf->Line(40,$initYpos,40,$yPos);
        $pdf->Line(80,$initYpos,80,$yPos);
        $pdf->Line(110,$initYpos,110,$yPos);
        $pdf->Line(140,$initYpos,140,$yPos);
        $pdf->Line(160,$initYpos,160,$yPos);
        $pdf->Line(180,$initYpos,180,$yPos);
        $pdf->Line(200,$initYpos,200,$yPos);

        $yPos = $yPos + 3;
        $pdf->Line(20,$yPos,200,$yPos);

        mysqli_close($conn);
       }

    $pdf ->output(); 
} else {
    // Redirect ot login page
    header("location: http://paperfly.com.bd/login");
}
// PDF report writing ends
    //} 
}
mysqli_close($conn);
?>