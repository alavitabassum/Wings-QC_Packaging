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
        $printID = trim($_GET['xxCode']);
        //$discAdj = trim($_GET['disc']);
        //$invComment1 = $_GET['invC1'];
        //$invComment2 = $_GET['invC2'];
        //$invComment3 = $_GET['invC3'];
        //$invComment4 = $_GET['invC4'];
        //$invComment5 = $_GET['invC5'];
        //$invComment6 = $_GET['invC6'];

        $printChequeSQL = "select * from tbl_cheque_print where printID ='$printID'";
        $printChequeResult = mysqli_query($conn, $printChequeSQL);
        $printChequeRow = mysqli_fetch_array($printChequeResult);

    }
// PDF Report writing starts
if ($user_check!=''){    
//    require('fpdf/fpdf.php');
    //require_once('draw.php');
    require_once('rotation.php');
    class PDF extends PDF_Rotate
    {    
        // Page header
        function Header()
        {
        // Logo
	        //$this->Image('../Moin Arms/images/GulshanArmsLogo.png',22,6,30);
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
	        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
        function RotatedText($x,$y,$txt,$angle)
        {
            //Text rotated around its origin
            $this->Rotate($angle,$x,$y);
            $this->Text($x,$y,$txt);
            $this->Rotate(0);
        }

        function RotatedImage($file,$x,$y,$w,$h,$angle)
        {
            //Image rotated around its upper-left corner
            $this->Rotate($angle,$x,$y);
            $this->Image($file,$x,$y,$w,$h);
            $this->Rotate(0);
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

        //End of Header Part

        // Cheque Date
        $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(161, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),0,1), '', 'C', 0);
        $pdf->RotatedText(19,54,substr(date('dmY', strtotime('+6 hour')),0,1),90);
        $pdf->SetXY(167.5, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),1,1), '', 'C', 0);
        $pdf->RotatedText(19,47,substr(date('dmY', strtotime('+6 hour')),1,1),90);
        $pdf->SetXY(174, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),2,1), '', 'C', 0);
        $pdf->RotatedText(19,41,substr(date('dmY', strtotime('+6 hour')),2,1),90);
        $pdf->SetXY(180.5, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),3,1), '', 'C', 0);
        $pdf->RotatedText(19,34,substr(date('dmY', strtotime('+6 hour')),3,1),90);
        $pdf->SetXY(187, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),4,1), '', 'C', 0);
        $pdf->RotatedText(19,27,substr(date('dmY', strtotime('+6 hour')),4,1),90);
        $pdf->SetXY(193.5, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),5,1), '', 'C', 0);
        $pdf->RotatedText(19,20,substr(date('dmY', strtotime('+6 hour')),5,1),90);
        $pdf->SetXY(200, 14);
        //$pdf ->MultiCell(6.5,4, substr(date('dmY', strtotime('+6 hour')),6,1), '', 'C', 0);
        $pdf->RotatedText(19,14,substr(date('dmY', strtotime('+6 hour')),6,1),90);
        $pdf->SetXY(206.5, 14);
        //$pdf ->MultiCell(5,4, substr(date('dmY', strtotime('+6 hour')),7,1), '', 'C', 0);
        $pdf->RotatedText(19,7,substr(date('dmY', strtotime('+6 hour')),7,1),90);
        //$pdf ->MultiCell(50,4, "  ".substr(date('dmY', strtotime('+6 hour')),0,1)."   ".substr(date('dmY', strtotime('+6 hour')),1,1)."   ".substr(date('dmY', strtotime('+6 hour')),2,1)."   ".substr(date('dmY', strtotime('+6 hour')),3,1)."   ".substr(date('dmY', strtotime('+6 hour')),4,1)."   ".substr(date('dmY', strtotime('+6 hour')),5,1)."   ".substr(date('dmY', strtotime('+6 hour')),6,1)."   ".substr(date('dmY', strtotime('+6 hour')),7,1), '', 'L', 0);

        $pdf->SetXY(50, 20);
        //$pdf ->MultiCell(100,4, $merchantrow['accountName'], '', 'L', 0);
        $pdf->RotatedText(33,170,$printChequeRow['payTo'],90);

        $pdf->SetFont('Helvetica','',8);
        $pdf->SetXY(55, 36);
        //$pdf ->MultiCell(90,10, int_to_words($totCollections - $totCharges)." only", '', 'L', 0);
        $pdf->RotatedText(42,163,int_to_words($printChequeRow['paidAmt'])." only",90);

        $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(162, 40);
        //$pdf ->MultiCell(50,4, "=".num_to_format($totCollections - $totCharges)."/=", '', 'L', 0);
        $pdf->RotatedText(47,53, "=".num_to_format($printChequeRow['paidAmt'])."/=",90);


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