<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = cleanInput($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: index.php');
            exit();
        } else {
            $error = 'Nieprawidłowa nazwa użytkownika lub hasło';
        }
    } else {
        $error = 'Nieprawidłowa nazwa użytkownika lub hasło';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Path of Exile Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>⚔️ Path of Exile Forum</h1>
            </div>
        </header>

        <main>
            <div class="form-container">
                <h2>Logowanie</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Zaloguj się</button>
                </form>
                
                <div class="form-footer">
                    <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
                    <p><a href="index.php">← Powrót do forum</a></p>
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