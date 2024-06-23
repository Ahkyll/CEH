<?php
	include('connection.php');
	$id = $_GET['state_id'];

	$code = $_POST['code'];
	$state = $_POST['state'];
	$description = $_POST['description'];

	$update = $connection->query("UPDATE state SET state_code = '$code', state_name = '$state', description = '$description' WHERE state_id='$id'");
	if($update){
		header('location:state.php');
	}
?>