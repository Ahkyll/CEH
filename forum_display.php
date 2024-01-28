<?php
session_start();
include 'connect.php';

// Retrieve existing forum posts with user information
$sql = "SELECT tb_forum.*, users.username, users.profile_picture
        FROM tb_forum
        JOIN users ON tb_forum.user_id = users.user_id
        ORDER BY tb_forum.post_time DESC"; 

$stmt = $pdo->query($sql);
$forumPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve comments for each post and store them in the respective post entry
foreach ($forumPosts as &$post) {
    $commentsSql = "SELECT tb_comments.*, users.username, users.profile_picture
                    FROM tb_comments
                    JOIN users ON tb_comments.user_id = users.user_id
                    WHERE tb_comments.post_id = ?
                    ORDER BY tb_comments.comment_time ASC";

    $commentsStmt = $pdo->prepare($commentsSql);
    $commentsStmt->execute([$post['post_id']]);
    $post['comments'] = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
}
unset($post); // unset to avoid accidental usage

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forum</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: #ecf0f1;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #34495e;
        }

        .forum-post {
            background-color: #ecf0f1;
            margin: 20px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post-content {
            margin-bottom: 15px;
        }

        .post-content h2 {
            color: #333;
        }

        .post-details {
            display: flex;
            align-items: center;
            color: #555;
        }

        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comments-section {
            margin-top: 15px;
        }

        .comment {
            background-color: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .comment-details {
            display: flex;
            align-items: center;
            color: #555;
            margin-top: 10px;
        }

        .container {
            margin: 20px;
        }

        .create-post-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .create-post-form h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .create-post-form label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        .create-post-form input,
        .create-post-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .create-post-form input[type="submit"] {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .create-post-form input[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>

    <header>
        <!-- Your header content goes here -->
    </header>

    
    <div class="container">
    <button id="togglePostFormBtn">Create a New Post</button>
    <div id="createPostForm" style="display: none;" class="create-post-form">
        <h2>Create a New Post</h2>
        <form action="add_post.php" method="post">
            <label for="post-title">Post Title:</label>
            <input type="text" id="post-title" name="post-title" required>

            <label for="post-content">Post Content:</label>
            <textarea id="post-content" name="post-content" required></textarea>

            <input type="submit" name="submit-post" value="Submit Post">
        </form>
    </div>
</div>


    <?php foreach ($forumPosts as $post) : ?>
        <div class="forum-post">
            <div class="post-content">
                <p><?= htmlspecialchars($post['post_content']) ?></p>
            </div>
            <div class="post-details">
    <img src="<?= htmlspecialchars($post['profile_picture']) ?>" alt="User Profile" class="profile-image">
    Posted by: <?= htmlspecialchars($post['username']) ?> | Date: <?= date('F j, Y g:i a', strtotime($post['post_time'])) ?>
</div>


            <!-- Display comments toggle button -->
<?php if ($post['comments']) : ?>
    <button class="toggle-comments-btn" onclick="toggleComments(this)">Toggle Comments</button>

    <!-- Display comments -->
    <div class="comments-section" style="display: none;">
        <p><strong>Comments:</strong></p>
        <?php foreach ($post['comments'] as $comment) : ?>
            <div class="comment">
                <p><?= htmlspecialchars($comment['comment_content']) ?></p>
                <div class="comment-details">
                                <img src="<?= htmlspecialchars($comment['profile_picture']) ?>" alt="User Profile" class="profile-image">
                                Comment by: <?= htmlspecialchars($comment['username']) ?> | Date: <?= date('F j, Y g:i a', strtotime($comment['comment_time'])) ?>
                            </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

            <!-- Comment Form -->
            <div class="comment-form">
                <h3>Add a Comment</h3>
                <form action="add_comment.php" method="post">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['post_id']) ?>">
                    <label for="comment-content">Your Comment:</label>
                    <textarea id="comment-content" name="comment-content" required></textarea>
                    <input type="submit" name="submit-comment" value="Add Comment">
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <script>
    function toggleComments(btn) {
        const commentsSection = btn.nextElementSibling;
        commentsSection.style.display = (commentsSection.style.display === 'none') ? 'block' : 'none';

        // Prevent the default behavior (e.g., following a link or submitting a form)
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false; // For older browsers
        }
    }
    document.getElementById("togglePostFormBtn").addEventListener("click", function () {
        const postForm = document.getElementById("createPostForm");
        postForm.style.display = (postForm.style.display === 'none') ? 'block' : 'none';
    });
</script>


   

    <!-- Your footer content goes here -->

</body>
</html>


