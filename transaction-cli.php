<?php 

$reference_id = $argv[1];
$status = $argv[2];
if ($reference_id && $status) {
	include 'db.php';
	$check = $db->query("SELECT id FROM transaction WHERE reference_id = '$reference_id'");
	if ($check->num_rows > 0) {
		$update = $db->query("UPDATE transaction SET status_transaction = '$status' WHERE reference_id = '$reference_id'");
		if ((bool) $update) {
			echo 'Sucessful update status transaction';
		}else{
			echo 'Failed update status transaction';
		}
		$db->close();
	}else{
		echo 'transaction not found';
	}
}else{
	echo 'Invalid flag';
}

 ?>