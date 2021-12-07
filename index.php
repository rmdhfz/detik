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
				('INV211201', 1, 1, md5(RAND()), 'Iphone 12', 'Rp. 12.000.000', 'virtual_account'),
				('INV211202', 2, 2, md5(RAND()), 'Laptop', 'Rp. 30.000.000', 'virtual_account'),
				('INV211203', 3, 3, md5(RAND()), 'Mobil', 'Rp.500.000.000', 'credit_card')
			"
		);
	}
}
// ------------------------------------------------------------------------
$main = new Main();
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
?>