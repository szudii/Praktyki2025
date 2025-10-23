<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = cleanInput($_POST['title']);
    $content = cleanInput($_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    if (strlen($title) < 5) {
        $error = 'Tytuł musi mieć minimum 5 znaków';
    } elseif (strlen($content) < 10) {
        $error = 'Treść musi mieć minimum 10 znaków';
    } else {
        $sql = "INSERT INTO posts (user_id, title, content) VALUES ($user_id, '$title', '$content')";
        
        if ($conn->query($sql)) {
            $post_id = $conn->insert_id;
            header("Location: post.php?id=$post_id");
            exit();
        } else {
            $error = 'Wystąpił błąd podczas dodawania wpisu';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy wpis - Path of Exile Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>⚔️ Path of Exile Forum</h1>
                <nav>
                    <a href="index.php" class="btn btn-secondary">← Powrót do forum</a>
                    <a href="logout.php" class="btn btn-secondary">Wyloguj</a>
                </nav>
            </div>
        </header>

        <main>
            <div class="form-container">
                <h2>Dodaj nowy wpis</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="title">Tytuł:</label>
                        <input type="text" id="title" name="title" required minlength="5" maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Treść:</label>
                        <textarea id="content" name="content" rows="10" required minlength="10"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Dodaj wpis</button>
                </form>
            </div>
        </main>

        <footer>
            <p>Path of Exile Forum - Społeczność graczy © 2025</p>
        </footer>
    </div>
</body>
</html>

<?php $conn->close(); ?>