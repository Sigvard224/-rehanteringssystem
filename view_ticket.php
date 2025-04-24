<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "Inget ärende-ID angivet.";
    exit();
}

$ticket_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Hämta ärendet från databasen och kontrollera att det tillhör användaren
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ? AND user_id = ?");
$stmt->execute([$ticket_id, $user_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ärendet hittades inte eller du har inte behörighet att visa det.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ärendedetaljer – TechAssist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="view-ticket">
        <h2>Ärendedetaljer</h2>
        <p><strong>Rubrik:</strong> <?php echo htmlspecialchars($ticket['subject']); ?></p>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($ticket['category']); ?></p>
        <p><strong>Beskrivning:</strong><br><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($ticket['status']); ?></p>
        <p><strong>Skapat:</strong> <?php echo $ticket['created_at']; ?></p>

        <p><a href="dashboard.php">← Tillbaka till dashboard</a></p>
        <p><a href="index.php">🏠 Start</a></p>
        <p><a href="dashboard.php">Mina ärenden</a></p>
        <p><a href="logout.php">Logga ut</a></p>

    </main>
</body>
</html>
