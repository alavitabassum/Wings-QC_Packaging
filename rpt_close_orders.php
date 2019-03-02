<?php
    include('session.php');
    if ($user_check !=''){
        include('config.php');
        $menuSQL = "Select * from tbl_menu_report where user_id = $user_id_chk";
        $menuResult = mysqli_query($conn, $menuSQL);
        $menuRow = mysqli_fetch_array($menuResult);
        if ($menuRow['closedOrders'] != 'Y') {
            echo "You are not authorized";
            exit;
        }
        if(isset($_GET['startDate'])){
            $startDate = date("Y-m-d", strtotime(trim($_GET['startDate'])));
            $startDate = mysqli_real_escape_string($conn, $startDate);
            $endDate = date("Y-m-d", strtotime(trim($_GET['endDate'])));
            $endDate = mysqli_real_escape_string($conn, $endDate);
            $merchantCode = trim($_GET['merchantCode']);
            $merchantCode = mysqli_real_escape_string($conn, $merchantCode);
            $searchText = trim($_GET['searchText']);
            $searchText = mysqli_real_escape_string($conn, $searchText);
        }
    } else {
        echo "You are not logged in";
        exit;
    }
    include('fpdf/fpdf.php');
    class PDF extends FPDF
    {    
        // Page header
        function Header()
        {
        // Logo
	         $this->Image('../www/image/Wings.jpg',22,6,30);
	        // Arial bold 15
	        $this->SetFont('Arial','B',15);
	        // Move to the right
	        $this->Cell(1);
	        // Title
            $this ->Cell (0, 0, "PAPERFLY",0,1,'C');
            $this ->ln(6);
            $this ->SetFont("Arial","",10);
            $this ->Cell (0,0, "Importer and seller of Arms and Amunitions",0,1,'C');
            $this ->ln(5);
            $this ->SetFont("Arial","",8);
            $this ->Cell (0,0,"Dhaka North DCC Market, Shop No. F-44 (1st Floor), Gulshan - 2, Dhaka - 1212.",0,1,"C");
	        //Line break
	        $this->Ln(5);
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
    $pdf->SetFillColor(80,80,80);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(10);
    $pdf->Cell(30, 6, 'Customar Name', 1, 0, 'L',1);
    $pdf->Cell(30, 6, 'Father Name', 1, 0, 'L',1);
    $pdf->Cell(50, 6, 'Address', 1, 0, 'L',1);
    $pdf->Cell(25,6,'License Number',1,0,'L',1);
    $pdf->Cell(30,6,'Issuing Authority',1,0,'L',1);
    $pdf->Cell(25,6,'Issue Date',1,0,'C',1);
    $pdf->Cell(25,6,'Renew Date',1,0,'C',1);
    $pdf->Cell(65,6,$startDate." To ".$endDate." Merchant ".$merchantCode,1,0,'L',1);
    $pdf->SetTextColor(0, 0, 0);
    $y_axis = $y_axis_initial + $row_height;
    $pdf ->output();    
?>
