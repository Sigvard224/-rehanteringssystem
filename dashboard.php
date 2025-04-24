<?php
session_start();
require_once 'db.php';

// Om inte inloggad, skicka tillbaka till login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Hämta användarens ID och visa deras ärenden
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ?");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – TechAssist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="dashboard">
        <h2>Välkommen, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        
        <h3>Dina ärenden:</h3>
        <?php if (count($tickets) > 0): ?>
            <table>
                <tr>
                    <th>Ärende-ID</th>
                    <th>Rubrik</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo $ticket['subject']; ?></td>
                        <td><?php echo $ticket['status']; ?></td>
                        <td><a href="view_ticket.php?id=<?php echo $ticket['id']; ?>">Visa</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Du har inga ärenden än.</p>
        <?php endif; ?>

        <p><a href="new_ticket.php">Skapa nytt ärende</a></p>

<?php
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<p><a href="index.php">🏠 Start</a></p>
        <p><a href="dashboard.php">Mina ärenden</a></p>
        <p><a href="logout.php">Logga ut</a></p>


    </main>
</body>
</html>
