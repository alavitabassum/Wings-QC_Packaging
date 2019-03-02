<?php
    include('session.php');
    if ($user_check!=''){
        if(isset($_POST['payto'])){
            include('config.php');
            $payto = trim($_POST['payto']);
            $payto = mysqli_real_escape_string($conn, $payto);
            $paidamt = trim($_POST['paidamt']);
            $chequeno = trim($_POST['chequeno']);
            $chequeno = mysqli_real_escape_string($conn, $chequeno);
            $bank = trim($_POST['bank']);
            $payreason = trim($_POST['payreason']);
            $payreason = mysqli_real_escape_string($conn, $payreason);
            $invNum = trim($_POST['invNum']);
            $chequeFor = trim($_POST['chequefor']);
            $flag = trim($_POST['flag']);
            if ($flag == 'general'){
                $chequeInsertSQL = "Insert into tbl_cheque_print (payTo, paidAmt, chequeNo, bankID, payReason, chequeType, creationDate, createdBy) values ('$payto', '$paidamt', '$chequeno', '$bank', '$payreason', 'general', NOW() + INTERVAL 6 HOUR, '$user_check')";
                if (!mysqli_query($conn, $chequeInsertSQL)){
                    $error ="Insert Error : " . mysqli_error($conn);
                    echo $error;
                } else {
                    $printID = mysqli_insert_id($conn);
                    echo "success".$printID ;                
                }                
            }

            if($flag == 'pendingInvoice'){
                $chequeInsertSQL = "Insert into tbl_cheque_print (payTo, paidAmt, chequeNo, bankID, payReason, chequeType, invNum, chequeFor, creationDate, createdBy) values ('$payto', '$paidamt', '$chequeno', '$bank', '$payreason', 'pending', '$invNum', '$chequeFor', NOW() + INTERVAL 6 HOUR, '$user_check')";
                if (!mysqli_query($conn, $chequeInsertSQL)){
                    $error ="Insert Error : " . mysqli_error($conn);
                    echo $error;
                } else {
                    $printID = mysqli_insert_id($conn);
                    $updateInvoiceStatusSQL = "update tbl_invoice set chequeStatus = 'Y', beftn = 'Y', updateDate = NOW() + INTERVAL 6 HOUR, updatedBy = '$user_check' where invNum = '$invNum'";
                    if (!mysqli_query($conn, $updateInvoiceStatusSQL)){
                        $error = "Update Error :" . mysqli_error($conn);
                        echo $error;
                    } else {
                        echo "success".$printID ;                    
                    }
                }                 
            }

            if($flag == 'rePrintCheque'){
                $chequeInsertSQL = "Insert into tbl_cheque_print (payTo, paidAmt, chequeNo, bankID, payReason, chequeType, invNum, chequeFor, creationDate, createdBy) values ('$payto', '$paidamt', '$chequeno', '$bank', '$payreason', 'rePrintCheque', '$invNum', '$chequeFor', NOW() + INTERVAL 6 HOUR, '$user_check')";
                if (!mysqli_query($conn, $chequeInsertSQL)){
                    $error ="Insert Error : " . mysqli_error($conn);
                    echo $error;
                } else {
                    $printID = mysqli_insert_id($conn);
                    echo "success".$printID ;                    
                }                
            }

            mysqli_close($conn);        
        }
    } else {
        echo "Session Expired. Please loggin again.";
    }
?>

