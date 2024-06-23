<?php
	include('connect.php');

	$eventName = $_POST['eventName'];
	$eventDate = $_POST['eventDate'];
	$eventDetails = $_POST['eventDetails'];
    $eventDetails = $_POST['eventDetails'];

	$insert = $connection->query("INSERT INTO event(event_name, event_date, event_details, event_image) VALUES('$name', '$state', '$description')");
	$lastid = $connection->last_id;
	
	if($insert == TRUE) {
		header('location:admin_event.php');
	}
?>