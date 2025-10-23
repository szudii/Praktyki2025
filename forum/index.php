<?php
require_once 'config.php';

$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Path of Exile - Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>⚔️ Path of Exile Forum</h1>
                <nav>
                    <?php if (isLoggedIn()): ?>
                        <span class="welcome">Witaj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                        <?php if (isAdmin()): ?>
                            <span class="admin-badge">ADMIN</span>
                        <?php endif; ?>
                        <a href="new_post.php" class="btn btn-primary">Nowy wpis</a>
                        <a href="logout.php" class="btn btn-secondary">Wyloguj</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Zaloguj się</a>
                        <a href="register.php" class="btn btn-secondary">Rejestracja</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main>
            <div class="forum-header">
                <h2>Ostatnie dyskusje</h2>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="posts-list">
                    <?php while($post = $result->fetch_assoc()): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <h3>
                                    <a href="post.php?id=<?php echo $post['id']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <div class="post-meta">
                                    <span class="author">👤 <?php echo htmlspecialchars($post['username']); ?></span>
                                    <span class="date">📅 <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="post-preview">
                                <?php echo htmlspecialchars(substr($post['content'], 0, 200)); ?>
                                <?php if (strlen($post['content']) > 200) echo '...'; ?>
                            </div>
                            <div class="post-actions">
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-small">Czytaj dalej →</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Brak postów na forum. Bądź pierwszy i dodaj wpis!</p>
                </div>
            <?php endif; ?>
        </main>

        <footer>
            <p>Path of Exile Forum - Społeczność graczy © 2025</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>