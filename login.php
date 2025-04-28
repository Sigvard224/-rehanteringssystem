<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
         $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin']; // sätt även detta direkt

        if ($user['is_admin'] == 1) {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit();
    } else {
        $error = "Fel e-post eller lösenord.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Logga in</h2>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="email">E-post:</label><br>
            <input type="email" name="email" required><br><br>

            <label for="password">Lösenord:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Logga in</button>
        </form>
    </div>
</body>
</html>
