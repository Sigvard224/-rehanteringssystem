<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'db.php';

$felmeddelande = '';
$lyckades = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namn = trim($_POST['namn']);
    $epost = trim($_POST['epost']);
    $losenord = $_POST['losenord'];
    $bekrafta = $_POST['bekrafta'];

    if ($losenord !== $bekrafta) {
        $felmeddelande = "Lösenorden matchar inte.";
    } else {
        // Kontrollera om e-post redan finns
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$epost]);

        if ($stmt->rowCount() > 0) {
            $felmeddelande = "E-posten finns redan.";
        } else {
            $hashat = password_hash($losenord, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$namn, $epost, $hashat]);
            $lyckades = "Registreringen lyckades! Du kan nu logga in.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Registrering</title></head>
<body>
    <h2>Skapa konto</h2>

    <?php if ($felmeddelande): ?>
        <p style="color:red;"><?php echo $felmeddelande; ?></p>
    <?php endif; ?>
    <?php if ($lyckades): ?>
        <p style="color:green;"><?php echo $lyckades; ?></p>
    <?php endif; ?>

    <form method="POST">
        Namn: <input type="text" name="namn" required><br><br>
        E-post: <input type="email" name="epost" required><br><br>
        Lösenord: <input type="password" name="losenord" required><br><br>
        Bekräfta lösenord: <input type="password" name="bekrafta" required><br><br>
        <button type="submit">Registrera</button>
    </form>
    <p><a href="index.php">Huvudsida</a></p>

</body>
</html>
