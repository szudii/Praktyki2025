<?php
require_once 'config.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()) {
    $comment = cleanInput($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    
    if (strlen($comment) >= 3) {
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ($post_id, $user_id, '$comment')";
        $conn->query($sql);
        header("Location: post.php?id=$post_id");
        exit();
    } else {
        $error = 'Komentarz musi mieć minimum 3 znaki';
    }
}

if (isset($_GET['delete']) && isLoggedIn()) {
    $sql = "SELECT user_id FROM posts WHERE id = $post_id";
    $result = $conn->query($sql);
    $post = $result->fetch_assoc();
    
    if (isAdmin() || $post['user_id'] == $_SESSION['user_id']) {
        $sql = "DELETE FROM posts WHERE id = $post_id";
        $conn->query($sql);
        header('Location: index.php');
        exit();
    }
}

$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = $post_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit();
}

$post = $result->fetch_assoc();

$sql = "SELECT comments.*, users.username FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE comments.post_id = $post_id 
        ORDER BY comments.created_at ASC";
$comments = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Path of Exile Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>⚔️ Path of Exile Forum</h1>
                <nav>
                    <a href="index.php" class="btn btn-secondary">← Powrót do forum</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="logout.php" class="btn btn-secondary">Wyloguj</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main>
            <div class="post-detail">
                <div class="post-header">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <div class="post-meta">
                        <span class="author">👤 <?php echo htmlspecialchars($post['username']); ?></span>
                        <span class="date">📅 <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></span>
                    </div>
                </div>
                
                <div class="post-content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
                
                <?php if (isLoggedIn() && (isAdmin() || $post['user_id'] == $_SESSION['user_id'])): ?>
                    <div class="post-actions">
                        <a href="post.php?id=<?php echo $post_id; ?>&delete=1" 
                           onclick="return confirm('Czy na pewno chcesz usunąć ten wpis?')" 
                           class="btn btn-danger">🗑️ Usuń wpis</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="comments-section">
                <h3>Komentarze (<?php echo $comments->num_rows; ?>)</h3>
                
                <?php if (isLoggedIn()): ?>
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="comment-form">
                        <textarea name="comment" placeholder="Dodaj komentarz..." required minlength="3"></textarea>
                        <button type="submit" class="btn btn-primary">Dodaj komentarz</button>
                    </form>
                <?php else: ?>
                    <div class="login-prompt">
                        <p><a href="login.php">Zaloguj się</a>, aby dodać komentarz</p>
                    </div>
                <?php endif; ?>
                
                <div class="comments-list">
                    <?php if ($comments->num_rows > 0): ?>
                        <?php while($comment = $comments->fetch_assoc()): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="author">👤 <?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="date">📅 <?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></span>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-comments">Brak komentarzy. Bądź pierwszy!</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer>
            <p>Path of Exile Forum - Społeczność graczy © 2025</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>