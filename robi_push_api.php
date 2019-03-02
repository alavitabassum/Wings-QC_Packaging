<?php

require_once('config.php');
$response = array(); 
mysqli_set_charset($conn, "utf8");

if(isset($_POST['userName'])){
	$username = $_POST['userName'];
	$password = $_POST['pass'];
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysqli_real_escape_string($conn, $username);
	$password = mysqli_real_escape_string($conn, $password);
	$password = md5($password);
	$action = $_POST['action'];
	$recipient_name = $_POST['recipient_name'];
	$recipient_email  = $_POST['recipient_email'];
	$recipient_type = $_POST['recipient_type'];
	$recipient_mobile = $_POST['recipient_mobile'];
	$recipient_thana = $_POST['recipient_thana'];
	$recipient_district  = $_POST['recipient_district'];
	$price = $_POST['price'];
	$weight = $_POST['weight'];
	$payment_method = $_POST['payment_method'];
	$order_id  = $_POST['order_id'];
	$recipient_address = $_POST['recipient_address'];
	$pick_address  = $_POST['pick_address'];
	$emi_detail = $_POST['emi_detail'];
	$freebee_detail = $_POST['freebee_detail'];
	$products_description = $_POST['products_description'];
	$comments  = $_POST['comments'];
	$query = "select * from tbl_user_info where userPassword='$password' AND userName='$username' AND isActive = 'Y'";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 0) {
        	//Need to check access level i.e. test , prod or none.

		$accessCheckSQL = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_api_client where userName = '$username'"));
		if($accessCheckSQL['userName'] !=''){
			$row = mysqli_fetch_array($result);
			if($accessCheckSQL['push'] == 'prod'){
				$Sql_Query = "insert into robishop (action,recipient_name,recipient_email,recipient_type,recipient_mobile,recipient_thana,recipient_district,price,weight,payment_method,order_id,recipient_address,pick_address,emi_detail,freebee_detail,products_description,comments) values ('$action','$recipient_name','$recipient_email',
				'$recipient_type','$recipient_mobile','$recipient_thana','$recipient_district','$price','$weight','$payment_method','$order_id','$recipient_address','$pick_address','$emi_detail','$freebee_detail','$products_description','$comments')";

				if(mysqli_query($conn,$Sql_Query)){

					$response['error'] = false; 
					echo json_encode($response);

				}
				else{

					$response['error'] = true; 
					echo json_encode($response);

				}

			}
			if($accessCheckSQL['push'] == 'test'){
				$Sql_Query = "insert into robishop (action,recipient_name,recipient_email,recipient_type,recipient_mobile,recipient_thana,recipient_district,price,weight,payment_method,order_id,recipient_address,pick_address,emi_detail,freebee_detail,products_description,comments) values ('$action','$recipient_name','$recipient_email',
				'$recipient_type','$recipient_mobile','$recipient_thana','$recipient_district','$price','$weight','$payment_method','$order_id','$recipient_address','$pick_address','$emi_detail','$freebee_detail','$products_description','$comments')";

				if(mysqli_query($conn,$Sql_Query)){

					$response['error'] = false; 
					echo json_encode($response);
                                         
					echo "Successful";

				}
				else{

					$response['error'] = true; 
					echo json_encode($response);
					echo "Unsuccessful";

				}

			}

		}
	} else {
		echo '[{"error":"invalid username and password"}]';
	}
					    mysqli_close($conn); // mysqli connection
					}


					?>