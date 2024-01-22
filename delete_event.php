<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];

    error_log("Received event ID: $eventId");

    try {
        // Check if the event belongs to the logged-in user
        $checkOwnershipStmt = $pdo->prepare("SELECT user_id FROM events WHERE event_id = :event_id");
        $checkOwnershipStmt->bindParam(':event_id', $eventId);
        $checkOwnershipStmt->execute();
        
        $eventOwner = $checkOwnershipStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($eventOwner && $eventOwner['user_id'] == $_SESSION['user_id']) {
            // Delete the event
            $deleteStmt = $pdo->prepare("DELETE FROM events WHERE event_id = :event_id");
            $deleteStmt->bindParam(':event_id', $eventId);
        
            if ($deleteStmt->execute()) {
                // Deletion successful
                echo json_encode(['success' => true]);
            } else {
                // Deletion failed
                echo json_encode(['success' => false, 'error' => 'Failed to delete event']);
            }
        } else {
            // Event doesn't belong to the logged-in user
            echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        }
    } catch (PDOException $e) {
        // Handle the exception (log, display an error, etc.)
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Internal server error']);
    }
}
?>
