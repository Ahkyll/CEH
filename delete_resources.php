<?php
session_start();
include 'connect.php';

if (isset($_GET['resource_id'])) {
    $resourceId = $_GET['resource_id'];

    // Fetch resource details
    $fetchResourceSql = "SELECT * FROM resources WHERE resource_id = ?";
    $fetchResourceStmt = $pdo->prepare($fetchResourceSql);
    $fetchResourceStmt->execute([$resourceId]);
    $resource = $fetchResourceStmt->fetch(PDO::FETCH_ASSOC);

    // Check if the logged-in user is the owner of the resource
    if ($resource && $resource['user_id'] == $_SESSION['user_id']) {
        // Delete the resource from the database
        $deleteResourceSql = "DELETE FROM resources WHERE resource_id = ?";
        $deleteResourceStmt = $pdo->prepare($deleteResourceSql);
        $deleteResourceStmt->execute([$resourceId]);

        // Redirect back to the resources page or specific location
        header("Location: admin_resources.php"); // You can modify this URL as needed
        exit();
    }
}
?>
