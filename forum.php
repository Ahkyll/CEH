<?php
// Dummy thread data
$threadPosts = [
    "This is the first post in the thread.",
    "Here's another post.",
    "And one more post for good measure.",
];

// Handle new posts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_content'])) {
    $newPost = trim($_POST['post_content']);

    if (!empty($newPost)) {
        // Add the new post to the thread
        $threadPosts[] = $newPost;
        
        // You may want to save the updated threadPosts array to a database here
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .thread {
            margin-bottom: 20px;
        }

        .post {
            background-color: #e6e6e6;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .post-form {
            margin-top: 20px;
        }

        .post-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .post-form button {
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .post-form button:hover {
            background-color: #45a049;
        }
    </style>
    <title>Thread Page</title>
</head>
<body>
        

<div class="container">
    <div class="thread">
        <?php
        // Display each post in the thread
        foreach ($threadPosts as $post) {
            echo '<div class="post">' . htmlspecialchars($post) . '</div>';
        }
        ?>
    </div>
    <div class="post-form">
        <form action="" method="post">
            <textarea name="post_content" placeholder="Type your message..."></textarea>
            <button type="submit">Post</button>
        </form>
    </div>
</div>

</body>
</html>
