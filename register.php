<?php
session_start();
require 'db.php';

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $error = "Hesla se neshodují!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if($stmt->execute()){
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Uživatel s tímto emailem nebo username již existuje!";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<title>Registrace</title>
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<form method="POST">
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <input type="password" name="confirm_password" placeholder="Potvrď heslo" required>
    <button type="submit" name="register">Registrovat</button>
</form>
<a href="login.php">Přihlásit se</a>
</body>
</html>
