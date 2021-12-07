<?php 

class Migration {
	function index() {
		http_response_code(404);
	}
	private function connection(){
		require_once 'db.php';
		return $db; 
	}
	private function query($sql, $operation){
		if ($sql && $operation) {
			$db = $this->connection();
			if ($db->query($sql)) {
				echo "Operation: $operation success";
			}else{
				echo "Operation: $operation failed. ".$db->error;
			}
			$db->close();
		}else{
			return false;
		}
	}
	function up($table, $info){
		if ($table && $info) {
			$this->query("CREATE TABLE IF NOT EXISTS $table ($info)", "Migration Up");
		}else{
			return false;
		}
	}
	function cleanup($table){
		if ($table) {
			$this->query("TRUNCATE TABLE $table", "Migration cleanup");
		}else{
			return false;
		}
	}
	function down($table){
		if ($table) {
			$this->query("DROP TABLE IF EXISTS $table", "Migration Down");
		}else{
			return false;
		}
	}
	function dummy($table, $fields, $values){
		if ($table) {
			$this->query("INSERT INTO $table ($fields) VALUES $values", "Migration Dummy");
		}else{
			return false;
		}
	}
}

?>