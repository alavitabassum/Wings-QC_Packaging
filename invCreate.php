<?php
    include('session.php');
    include('config.php');
    if(isset($_POST['data'])){
        $sno = $_POST['data'];
        $asAmt = $_POST['asAmt'];
        $asReg = $_POST['asReg'];
        $asExp = $_POST['asExp'];
        $asLar = $_POST['asLar'];
        $asSpc = $_POST['asSpc'];
        $asCod = $_POST['asCod'];
        $amerCode = $_POST['amerCode'];
        $aregRate = $_POST['aregRate'];
        $aExpRate = $_POST['aExpRate'];
        $alarRate = $_POST['alarRate'];
        $aspcRate = $_POST['aspcRate'];
        $acodRate = $_POST['acodRate'];

        $arno = $_POST['arno'];
        $arReg = $_POST['arReg'];
        $arExp = $_POST['arExp'];
        $arLar = $_POST['arLar'];
        $arSpc = $_POST['arSpc'];

        $anono = $_POST['anono'];
        $anoReg = $_POST['anoReg'];
        $anoExp = $_POST['anoExp'];
        $anoLar = $_POST['anoLar'];
        $anoSpc = $_POST['anoSpc'];

        $adisc = $_POST['adisc'];  
        $ainvmsg = $_POST['ainvmsg'];
        $ainvmsg = mysqli_real_escape_string($conn, $ainvmsg);

        $amerCode = $_POST['amerCode'];
        //Merchant wise max invoice sequence no
        $merMaxInvSQL = "Select max(invSeq) as max_inv from tbl_invoice where merchantCode ='$amerCode'";
        $merMaxInvresult = mysqli_query($conn,$merMaxInvSQL);
        $merMaxInvRow = mysqli_fetch_array($merMaxInvresult);

        $invseq = ($merMaxInvRow['max_inv'] + 1);
        $timeSQL="select NOW() + INTERVAL 6 HOUR as currenttime";
        $timeResult = mysqli_query($conn, $timeSQL);
        $timeRow = mysqli_fetch_array($timeResult);
        $dt = date("Y-m-d", strtotime($timeRow['currenttime']));
        $invnum = date("Y-m-d", strtotime($timeRow['currenttime']))."/".$amerCode."/".$invseq;

        $ordUpdate ="update tbl_order_details set invNum = '$invnum' where (invNum = '' or invNum is null) and merchantCode ='$amerCode' and close = 'Y';";
        if (!mysqli_multi_query($conn,$ordUpdate)){
            $error ="Insert Error : " . mysqli_error($conn);
            echo $error;
        } else {
            $CashInsSQL = "Insert into tbl_invoice (Date, merchantCode, invSeq, invNum, DeliveryCategory, NoOfOrders, CashCollection, RegularRate, ExpressRate, LargeRate, SpecialRate, codPer, RegularAmt, ExpressAmt, LargeAmt, SpecialAmt, CoD, Discount, Message) values ('$dt','$amerCode','$invseq','$invnum','Successfull','$sno','$asAmt','$aregRate','$aExpRate','$alarRate','$aspcRate','$acodRate','$asReg','$asExp','$asLar','$asSpc','$asCod','$adisc','$ainvmsg');";
            $CashInsSQL .= "Insert into tbl_invoice (Date, merchantCode, invSeq, invNum, DeliveryCategory, NoOfOrders, RegularRate, ExpressRate, LargeRate, SpecialRate, codPer, RegularAmt, ExpressAmt, LargeAmt, SpecialAmt) values ('$dt','$amerCode','$invseq','$invnum','Return','$arno','$aregRate','$aExpRate','$alarRate','$aspcRate','$acodRate','$arReg','$arExp','$arLar','$arSpc'); ";
            $CashInsSQL .= "Insert into tbl_invoice (Date, merchantCode, invSeq, invNum, DeliveryCategory, NoOfOrders, RegularRate, ExpressRate, LargeRate, SpecialRate, codPer, RegularAmt, ExpressAmt, LargeAmt, SpecialAmt) values ('$dt','$amerCode','$invseq','$invnum','No Show','$anono','$aregRate','$aExpRate','$alarRate','$aspcRate','$acodRate','$anoReg','$anoExp','$anoLar','$anoSpc'); ";
            if (!mysqli_multi_query($conn,$CashInsSQL)){
                $error ="Insert Error : " . mysqli_error($conn);
                echo $error;
            } else {
                echo "success";
            }            
        }


        mysqli_close($conn);
    }
?>