<?php 

$db = new mysqli("localhost", "root", "", "detik");
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}

?>