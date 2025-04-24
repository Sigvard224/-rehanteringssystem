<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $user_id = $_SESSION['user_id'];
    $status = 'Öppen';

    if (empty($subject) || empty($description)) {
        $error = 'Både rubrik och beskrivning måste fyllas i.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO tickets (user_id, subject, category, description, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $subject, $category, $description, $status]);

        $success = 'Ditt ärende har skapats!';
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Skapa nytt ärende – TechAssist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="new-ticket">
        <h2>Skapa nytt ärende</h2>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color: green;"><?php echo $success; ?></p>
            <p><a href="dashboard.php">Tillbaka till dashboard</a></p>
        <?php else: ?>
            <form method="POST">
                <label for="subject">Rubrik:</label><br>
                <input type="text" name="subject" id="subject" required><br><br>

                <label for="category">Kategori:</label><br>
                <input type="text" name="category" id="category"><br><br>

                <label for="description">Beskrivning:</label><br>
                <textarea name="description" id="description" required></textarea><br><br>

                <button type="submit">Skapa ärende</button>
            </form>
        <?php endif; ?>

        <p><a href="index.php">🏠 Start</a></p>
        <p><a href="dashboard.php">Mina ärenden</a></p>
        <p><a href="logout.php">Logga ut</a></p>

    </main>
</body>
</html>
