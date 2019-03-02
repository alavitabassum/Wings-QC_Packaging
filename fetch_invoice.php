<?php
    if(isset($_POST['get_inv'])){
        include('config.php');
        $merchantCode = $_POST['get_inv'];
        $flag = $_POST['flagreq'];
        if ($flag == 'cheque'){
            $findInvoicesql="select invNum from tbl_invoice where merchantCode='$merchantCode' and chequeStatus = 'Y' order by inv_date desc";
            $findInvoiceresult = mysqli_query($conn, $findInvoicesql);
            echo "<option></option>";
            foreach ($findInvoiceresult as $row){
                echo "<option value=".$row['invNum'].">".$row['invNum']."</option>";
            }
            exit;            
        } else {
            $findInvoicesql="select invNum from tbl_invoice where merchantCode='$merchantCode' order by inv_date desc";
            $findInvoiceresult = mysqli_query($conn, $findInvoicesql);
            echo "<option></option>";
            foreach ($findInvoiceresult as $row){
                echo "<option value=".$row['invNum'].">".$row['invNum']."</option>";
            }
            exit;            
        }
    }
?>