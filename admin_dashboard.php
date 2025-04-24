<?php
session_start();
require_once 'db.php';

// Kontrollera att användaren är inloggad och är admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Hämta info om användaren
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['is_admin'] != 1) {
    echo "Du har inte behörighet att se denna sida.";
    exit();
}

// Hämta alla ärenden
$stmt = $pdo->query("
    SELECT tickets.id, tickets.subject, tickets.status, tickets.created_at, users.email 
    FROM tickets
    JOIN users ON tickets.user_id = users.id
    ORDER BY tickets.created_at DESC
");
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Adminpanel – TechAssist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="admin-dashboard">
        <h2>Adminpanel – Alla ärenden</h2>

        <?php if (count($tickets) === 0): ?>
            <p>Inga ärenden har skapats ännu.</p>
        <?php else: ?>
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rubrik</th>
                        <th>Användare</th>
                        <th>Status</th>
                        <th>Skapat</th>
                        <th>Visa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket['id']; ?></td>
                            <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['email']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                            <td><?php echo $ticket['created_at']; ?></td>
                            <td><a href="admin_view_ticket.php?id=<?php echo $ticket['id']; ?>">Öppna</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <p><a href="index.php">Huvudsida</a></p>
        <p><a href="logout.php">Logga ut</a></p>

    </main>
</body>
</html>
                        