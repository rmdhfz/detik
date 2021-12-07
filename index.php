<?php 


Class Main {

	public function index(){
		http_response_code(404);
	}
	private function migration() {
		require_once 'Migration.php';
		return new Migration();
	}
	private function MigrationService($table, $info){
		$migration = $this->migration();
		$migration->up($table, $info);
	}
	private function CleanUpService($table){
		$migration = $this->migration();
		$migration->cleanup($table);
	}
	private function MigrationDownService($table) {
		$migration = $this->migration();
		$migration->down($table);
	}
	private function MigrationDummyService($table, $fields, $values) {
		$migration = $this->migration();
		$migration->dummy($table, $fields, $values);
	}
	// ------------------------------------------------------------------------
	public function MigrationCustomer(){
		$this->MigrationService('customer', 
			"id INT(3) AUTO_INCREMENT PRIMARY KEY,
			customer_name VARCHAR(25) NOT NULL,
			customer_email VARCHAR(25) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
	}
	public function MigrationMerchant(){
		$this->MigrationService('merchant', 
			"id INT(3) AUTO_INCREMENT PRIMARY KEY,
			merchant_name VARCHAR(25) NOT NULL,
			merchant_email VARCHAR(25) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
		);
	}
	public function MigrationTransaction(){
		$this->MigrationService('transaction', (
			"id INT(3) AUTO_INCREMENT PRIMARY KEY,
			invoice_id VARCHAR(25) NOT NULL,
			customer_id INT(3) NOT NULL,
			merchant_id INT(3) NOT NULL,
			reference_id VARCHAR(15) NOT NULL,
			item_name VARCHAR(35) NOT NULL,
			amount VARCHAR(25) NOT NULL,
			payment_type VARCHAR(25) NOT NULL,
			number_va VARCHAR(16) DEFAULT NULL,
			status_transaction VARCHAR(7) DEFAULT 'pending',
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
		));	
	}
	// ------------------------------------------------------------------------
	public function CleanUpCustomer(){
		$this->CleanUpService('customer');
	}
	public function CleanUpMerchant(){
		$this->CleanUpService('merchant');
	}
	public function CleanUpTransaction(){
		$this->CleanUpService('transaction');
	}
	// ------------------------------------------------------------------------
	public function MigrationDownCustomer(){
		$this->MigrationDownService('customer');
	}
	public function MigrationDownMerchant(){
		$this->MigrationDownService('merchant');
	}
	public function MigrationDownTransaction(){
		$this->MigrationDownService('transaction');
	}
	// ------------------------------------------------------------------------
	public function InsertDummyCustomer(){
		$this->MigrationDummyService('customer', 'id, customer_name, customer_email',
			"
				(1, 'Hafiz Ramadhan', 'hfzrmd@gmail.com'),
				(2, 'Budi Anduk', 'budi@anduk.co.id'),
				(3, 'Rafi', 'rafi@google.com')
			"
		);
	}
	public function InsertDummyMerchant(){
		$this->MigrationDummyService('merchant', 'id, merchant_name, merchant_email',
			"
				(1, 'Merchant Hafiz', 'merchant@hafiz.com'),
				(2, 'Merchant Budi', 'merchant@budi.com'),
				(3, 'Merchant Rafi', 'merchant@rafi.com')
			"
		);
	}
	public function InsertDummyTransaction(){
		$this->MigrationDummyService('transaction', 'invoice_id, customer_id, merchant_id, reference_id, item_name, amount, payment_type',
			"
				('INV21121', 1, 1, md5(RAND()), 'Iphone 12', 'Rp. 12.000.000', 'virtual_account'),
				('INV21122', 2, 2, md5(RAND()), 'Laptop', 'Rp. 30.000.000', 'virtual_account'),
				('INV21123', 3, 3, md5(RAND()), 'Mobil', 'Rp.500.000.000', 'credit_card')
			"
		);
	}
}

class API {
	public function index(){
		http_response_code(404);
	}
	private function EscapeData($data){
		if ($data) {
			return stripcslashes(htmlspecialchars(htmlentities($data)));
		}else{
			return false;
		}
	}
	private function query($sql, $type){
		if ($sql) {
			include 'db.php';
			if ($result = $db->query($sql)) {
				if ($type === "fetch_row") {
					return $result->fetch_row;
				}else if ($type === "num_rows") {
					return $result->num_rows;
				}else if ($type === "fetch_array") {
					return $result->fetch_array(MYSQLI_ASSOC);
				}else if ($type === "column") {
					return $result->fetch_column;
				}else if ($type === "result"){
					return $result->fetch_assoc;
				}else{
					return true;
				}
			}else{
				return $db->error;
			}
			$db->close();
		}else{
			return false;
		}
	}
	private function Response($status_code, $status, $msg, $data){
		http_response_code((int) $status_code);
		header('Content-Type: application/json');
		return [
			'status'	=>	$this->EscapeData($status),
			'message'	=>	$this->EscapeData($msg),
			'data'		=>	$data,
		];
	}
	public function CreateTransaction(){
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "POST") {
			$customer_id = $this->EscapeData(intval(isset($_POST['customer_id'])));
			$merchant_id = $this->EscapeData(intval(isset($_POST['merchant_id'])));
			if ($customer_id && $merchant_id) {
				// ------------------------------------------------------------------------
				static $urutan = 1;
				$reference_id = md5(rand());
				$invoice_id = "INV".date('y').date('m').$urutan; $urutan++;
				$item = $this->EscapeData(isset($_POST['item']));
				$amount = $this->EscapeData(isset($_POST['amount']));
				$payment_type = $this->EscapeData(strtolower(str_replace(" ", "_", isset($_POST['payment_type']))));
				// ------------------------------------------------------------------------
				if ($payment_type === "virtual_account" || $payment_type === "credit_card") {
					$number_va = null;
					if ($payment_type === "virtual_account") {
						$number_va = rand();
					}
					// ------------------------------------------------------------------------
					# check customer
					$check = $this->query("SELECT id FROM customer WHERE id = '$customer_id'", 'num_rows');
					if ($check > 0) {
						$insert = $this->query("INSERT INTO transaction VALUES ($invoice_id, $customer_id, $merchant_id, $item, $amount, $payment_type, $number_va)", true);
						if ($insert) {
							echo json_encode($this->Response(200, true, 'Sucessful save transaction', [
								'reference_id'	=>	$reference_id,
								'number_va'		=>	$number_va,
							]));
						}else{
							echo json_encode($this->Response(500, false, 'Failed save transaction', [
								'reference_id'	=>	$reference_id,
								'number_va'		=>	$number_va,
							]));
						}
					}else{
						http_response_code(404);
					}
				}else{
					echo 'unknown payment_type'; 
					http_response_code(400);
				}
			}else{
				http_response_code(400);
			}
		}else{
			http_response_code(405);
		}
	}
}
// ------------------------------------------------------------------------
// $main = new Main();
// $main->MigrationCustomer();
// $main->MigrationMerchant();
// $main->MigrationTransaction();

// $main->CleanUpCustomer();
// $main->CleanUpMerchant();
// $main->CleanUpTransaction();

// $main->MigrationDownCustomer();
// $main->MigrationDownMerchant();
// $main->MigrationDownTransaction();

// $main->InsertDummyCustomer();
// $main->InsertDummyMerchant();
// $main->InsertDummyTransaction();

include 'route.php';

Route::add('/api/create-transaction', function(){
	$api = new API();
	$api->CreateTransaction();
});
Route::submit();

?>