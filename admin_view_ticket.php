<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Kontrollera att användaren är admin
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['is_admin'] != 1) {
    echo "Du har inte behörighet att visa denna sida.";
    exit();
}

// Hämta ärendet
if (!isset($_GET['id'])) {
    echo "Inget ärende-ID angivet.";
    exit();
}

$ticket_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ärendet hittades inte.";
    exit();
}

// Uppdatera status om formuläret skickats
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $update = $pdo->prepare("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?");
    $update->execute([$new_status, $ticket_id]);

    // Hämta uppdaterat ärende igen
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();
    $message = "Status uppdaterad!";
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin: Ärendedetaljer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="admin-view-ticket">
        <h2>Admin: Ärendedetaljer</h2>

        <?php if (!empty($message)): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <p><strong>Rubrik:</strong> <?php echo htmlspecialchars($ticket['subject']); ?></p>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($ticket['category']); ?></p>
        <p><strong>Beskrivning:</strong><br><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
        <p><strong>Skapat:</strong> <?php echo $ticket['created_at']; ?></p>
        <p><strong>Uppdaterat:</strong> <?php echo $ticket['updated_at']; ?></p>

        <form method="POST">
    <label for="status">Ändra status:</label>
    <select name="status" id="status">
        <option value="open" <?php if ($ticket['status'] == 'open') echo 'selected'; ?>>🟢 Öppen</option>
        <option value="in_progress" <?php if ($ticket['status'] == 'in_progress') echo 'selected'; ?>>🟡 Pågår</option>
        <option value="closed" <?php if ($ticket['status'] == 'closed') echo 'selected'; ?>>🔴 Avslutad</option>
    </select>
    <button type="submit">Uppdatera</button>
</form>

        <p><a href="index.php">Huvudsida</a></p>
        <?php if ($user['is_admin'] == 1): ?>
            <a href="admin_dashboard.php">Adminpanel</a>
        <?php endif; ?>
        <p><a href="logout.php">Logga ut</a></p>

    </main>
</body>
</html>
