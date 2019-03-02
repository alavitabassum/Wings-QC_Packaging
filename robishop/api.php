<?php

class API extends Rest{

	public function __construct() {
			parent::__construct();
			
		}

		public function generateToken() {
            $email = $this->validateParameter('userName', $this->param['userName'], STRING);
			$pass = $this->validateParameter('userPassword', $this->param['userPassword'], STRING);
            try {
			$stmt = $this->dbConn->prepare("SELECT * FROM tbl_user_info WHERE userName = :userName AND userPassword = :userPassword");
				$stmt->bindParam(":userName", $email);
				$stmt->bindParam(":userPassword", md5($pass));
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if(!is_array($user)) {
					$this->returnResponse(INVALID_USER_PASS, "Username or Password is incorrect.");
				}

				/*if( $user['active'] == 0 ) {
					$this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
				}
*/
				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (15*60),
					'userId' => $user['user_id']
				];

				$token = JWT::encode($paylod, SECRETE_KEY);
				$data = ['token' => $token];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
				} catch (Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

	
	public function addCustomer() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$addr = $this->validateParameter('addr', $this->param['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setName($name);
			$cust->setEmail($email);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));

			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
	
			
		public function pushOrder() {
               print_r($this->param);
			foreach ($this->param as $row) {
				
			$action = $this->validateParameter('action', $row['action'], STRING, false);
			$recipient_name = $this->validateParameter('recipient_name', $row['recipient_name'], STRING, false);
			$recipient_email = $this->validateParameter('recipient_email', $row['recipient_email'], STRING, false);
			$recipient_type = $this->validateParameter('recipient_type',$row['recipient_type'], STRING, false);

			$recipient_mobile = $this->validateParameter('recipient_mobile', $row['recipient_mobile'], STRING, false);
			$recipient_thana = $this->validateParameter('recipient_thana', $row['recipient_thana'], STRING, false);
			$recipient_district = $this->validateParameter('recipient_district', $row['recipient_district'], STRING, false);
			$price = $this->validateParameter('price', $row['price'], STRING, false);


			$weight = $this->validateParameter('weight', $row['weight'], STRING, false);
			$payment_method = $this->validateParameter('payment_method', $row['payment_method'], STRING, false);
			$order_id = $this->validateParameter('order_id', $row['order_id'], STRING, false);
			$recipient_address = $this->validateParameter('recipient_address', $row['recipient_address'], STRING, false);

			$pick_address = $this->validateParameter('pick_address', $row['pick_address'], STRING, false);
			$emi_detail = $this->validateParameter('emi_detail', $row['emi_detail'], STRING, false);
			$freebee_detail = $this->validateParameter('freebee_detail', $row['freebee_detail'], STRING, false);
			$products_description = $this->validateParameter('products_description', $row['products_description'], STRING, false);


			$comments = $this->validateParameter('comments', $row['comments'], STRING, false);

			$order = new Order;
			$order->setAction($action);
			$order->setR_Name($recipient_name);
			$order->setR_Email($recipient_email);
			$order->setR_type($recipient_type);
			$order->setR_Mobile($recipient_mobile);
			$order->setR_Thana($recipient_thana);
            
            $order->setR_District($recipient_district);
			$order->setPrice($price);
			$order->setWeight($weight);
			$order->setPayment_method($payment_method);
			$order->setOrderID($order_id);
			$order->setR_Address($recipient_address);

			$order->setP_Address($pick_address);
			$order->setEmi_Detail($emi_detail);
			$order->setFreebee_Detail($freebee_detail);
			$order->setP_description($products_description);
			$order->setComments($comments);
			

			if(!$order->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}
			
		}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

	

		
}
?>