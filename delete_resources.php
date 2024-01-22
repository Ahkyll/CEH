<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resource_id'])) {
    $resourceId = $_POST['resource_id'];

    try {
        // Check if the resource belongs to the logged-in user
        $checkOwnershipStmt = $pdo->prepare("SELECT user_id FROM resources WHERE resource_id = :resource_id");
        $checkOwnershipStmt->bindParam(':resource_id', $resourceId);
        $checkOwnershipStmt->execute();
        
        $resourceOwner = $checkOwnershipStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resourceOwner && $resourceOwner['user_id'] == $_SESSION['user_id']) {
            // Delete the resource
            $deleteStmt = $pdo->prepare("DELETE FROM resources WHERE resource_id = :resource_id");
            $deleteStmt->bindParam(':resource_id', $resourceId);
        
            if ($deleteStmt->execute()) {
                // Deletion successful
                echo json_encode(['success' => true]);
            } else {
                // Deletion failed
                echo json_encode(['success' => false, 'error' => 'Failed to delete resource']);
            }
        } else {
            // Resource doesn't belong to the logged-in user
            echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        }
    } catch (PDOException $e) {
        // Handle the exception (log, display an error, etc.)
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Internal server error']);
    }
}
?>
