<?php 
	class Order {
		private $action;
		private $recipient_name;
		private $recipient_email;
		private $recipient_type;
		private $recipient_mobile;
		private $recipient_thana;
		private $recipient_district;
		private $price;
		private $weight;
		private $payment_method;
		private $order_id;
		private $recipient_address;
		private $pick_address;
		private $emi_detail;
		private $freebee_detail;
		private $products_description;
		private $setComments	;
		private $tableName = 'robishop';
		private $dbConn;

		function setAction($action) { $this->action = $action; }
		function getAction() { return $this->action; }
		function setR_Name($recipient_name) { $this->recipient_name = $recipient_name; }
		function getR_Name() { return $this->recipient_name; }
		function setR_Email($recipient_email) { $this->recipient_email = $recipient_email; }
		function getR_Email() { return $this->recipient_email; }
		function setR_type($recipient_type) { $this->recipient_type = $recipient_type; }
		function getR_type() { return $this->recipient_type; }
		function setR_Mobile($recipient_mobile) { $this->recipient_mobile = $recipient_mobile; }
		function getR_Mobile() { return $this->recipient_mobile; }
		function setR_Thana($recipient_thana) { $this->recipient_thana = $recipient_thana; }
		function getR_Thana() { return $this->recipient_thana; }
		function setR_District($recipient_district) { $this->recipient_district = $recipient_district; }
		function getR_District() { return $this->recipient_district; }
		function setPrice($price) { $this->price = $price; }
		function getPrice() { return $this->price; }
		function setWeight($weight) { $this->weight = $weight; }
		function getWeight() { return $this->weight; }

		function setPayment_method($payment_method) { $this->payment_method = $payment_method; }
		function getPayment_method() { return $this->payment_method; }
		function setOrderID($order_id) { $this->order_id = $order_id; }
		function getOrderID() { return $this->order_id; }
		function setR_Address($recipient_address) { $this->recipient_address = $recipient_address; }
		function getR_Address() { return $this->recipient_address; }
		function setP_Address($pick_address) { $this->pick_address = $pick_address; }
		function getP_Address() { return $this->pick_address; }
		function setEmi_Detail($emi_detail) { $this->emi_detail = $emi_detail; }
		function getEmi_Detail() { return $this->emi_detail; }
		function setFreebee_Detail($freebee_detail) { $this->freebee_detail = $freebee_detail; }
		function getFreebee_Detail() { return $this->freebee_detail; }
		function setP_description($products_description) { $this->products_description = $products_description; }
		function getP_description() { return $this->products_description; }
		function setComments($comments) { $this->comments = $comments; }
		function getComments() { return $this->comments; }


		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(action,
		 recipient_name,
		 recipient_email,
		 recipient_type,
		 recipient_mobile,
		 recipient_thana,
		 recipient_district,
		 price,
		 weight,
		 payment_method,
		 order_id,
		 recipient_address,
		 pick_address,
		 emi_detail,
		 freebee_detail,
		 products_description,
		 comments) VALUES(:action,
		 :recipient_name,
		 :recipient_email,
		 :recipient_type,
		 :recipient_mobile,
		 :recipient_thana,
		 :recipient_district,
		 :price,
		 :weight,
		 :payment_method,
		 :order_id,
		 :recipient_address,
		 :pick_address,
		 :emi_detail,
		 :freebee_detail,
		 :products_description,
		 :comments)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':action', $this->action);
			$stmt->bindParam(':recipient_name', $this->recipient_name);
			$stmt->bindParam(':recipient_email', $this->recipient_email);
			$stmt->bindParam(':recipient_type', $this->recipient_type);
			$stmt->bindParam(':recipient_mobile', $this->recipient_mobile);
			$stmt->bindParam(':recipient_thana', $this->recipient_thana);

			$stmt->bindParam(':recipient_district', $this->recipient_district);
			$stmt->bindParam(':price', $this->price);
			$stmt->bindParam(':weight', $this->weight);
			$stmt->bindParam(':payment_method', $this->payment_method);
			$stmt->bindParam(':order_id', $this->order_id);
			$stmt->bindParam(':recipient_address', $this->recipient_address);

			$stmt->bindParam(':pick_address', $this->pick_address);
			$stmt->bindParam(':emi_detail', $this->emi_detail);
			$stmt->bindParam(':freebee_detail', $this->freebee_detail);
			$stmt->bindParam(':products_description', $this->products_description);
			$stmt->bindParam(':comments', $this->comments);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

	}
 ?>