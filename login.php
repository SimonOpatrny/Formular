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
<title>Přihlášení</title>
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<form method="POST">
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="text" name="identifier" placeholder="Email nebo Username" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <button type="submit" name="login">Přihlásit se</button>
</form>
<a href="register.php">Registrovat se</a>
</body>
</html>
