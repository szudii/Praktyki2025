<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = cleanInput($_POST['username']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    if (strlen($username) < 3) {
        $error = 'Nazwa użytkownika musi mieć minimum 3 znaki';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nieprawidłowy adres email';
    } elseif (strlen($password) < 6) {
        $error = 'Hasło musi mieć minimum 6 znaków';
    } elseif ($password !== $password_confirm) {
        $error = 'Hasła nie są identyczne';
    } else {
        $sql = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $error = 'Nazwa użytkownika lub email już istnieje';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
            
            if ($conn->query($sql)) {
                $success = 'Rejestracja zakończona sukcesem! Możesz się teraz zalogować.';
            } else {
                $error = 'Wystąpił błąd podczas rejestracji';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Path of Exile Forum</title>
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
                <h2>Rejestracja</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" required minlength="3">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Potwierdź hasło:</label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                </form>
                
                <div class="form-footer">
                    <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
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