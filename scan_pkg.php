<?php
include('config.php');


                $barcode_p = mysqli_real_escape_string($conn, $_POST["prod_barcode_parent"]);
               $barcode_c= mysqli_real_escape_string($conn, $_POST["child_barcode"]);
               $rec_qty= mysqli_real_escape_string($conn, $_POST["prod_count"]);
               $plength= mysqli_real_escape_string($conn, $_POST["pl"]);
               $pwidth= mysqli_real_escape_string($conn, $_POST["pw"]);
               $pheight= mysqli_real_escape_string($conn, $_POST["ph"]);
               $c_point= mysqli_real_escape_string($conn, $_POST["pCode"]);
               $c_inv= mysqli_real_escape_string($conn, $_POST["iName"]);
               $c_line= mysqli_real_escape_string($conn, $_POST["lName"]);
               $c_rack= mysqli_real_escape_string($conn, $_POST["rName"]);
               $c_bin= mysqli_real_escape_string($conn, $_POST["bin_name"]);
               $master_prod_vol = $plength * $pwidth * $pheight;
               $child_prod_vol =  $master_prod_vol / $rec_qty ;
          

              $sql = "INSERT INTO tbl_child_barcode_fulfillment (childBarcode, parentBarcode, occupiedSpace, c_bin, c_rack, c_line, c_inv, c_point)
              VALUES ('$barcode_c', '$barcode_p','$child_prod_vol', '$c_bin', '$c_rack', '$c_line', '$c_inv', '$c_point')";

               if ($conn->query($sql) === TRUE) {
                     echo $rec_qty;
                   //echo "New record created successfully";
                   //echo  $_SESSION['submitchildBarcodes'];

                  
               } else {

                   echo "Error: " . $sql . "<br>" . $conn->error;
                 
               }
?>