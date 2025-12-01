<?php
session_start();
require 'db.php';

if(isset($_POST['login'])){
    $identifier = $_POST['identifier']; 
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? OR username=? LIMIT 1");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if($user){
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Špatné heslo!";
        }
    } else {
        $error = "Uživatel neexistuje!";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
<main>
    <div class="form">
        <h2>Přihlášení</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label for="identifier">Email nebo Username</label>
            <input type="text" id="identifier" name="identifier" placeholder="Email nebo Username" required>

            <label for="password">Heslo</label>
            <input type="password" id="password" name="password" placeholder="Heslo" required>

            <button type="submit" name="login">Přihlásit se</button>
        </form>
        <p>Ještě nemáte účet? <a href="register.php">Registrovat se</a></p>
    </div>
</main>
</body>
</html>
