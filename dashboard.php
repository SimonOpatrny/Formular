<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
require 'db.php';

// Načtení dat uživatele
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Aktualizace profilu
if(isset($_POST['update_profile'])){
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $avatar_path = $user['avatar'];

    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0){
        $allowed_types = ['image/jpeg', 'image/jpg'];
        if(in_array($_FILES['avatar']['type'], $allowed_types)){
            if(!is_dir('uploads')) mkdir('uploads', 0755, true);
            $avatar_name = uniqid() . '_' . basename($_FILES['avatar']['name']);
            $avatar_path = 'uploads/' . $avatar_name;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path);
        } else {
            $error = "Avatar musí být soubor .jpg";
        }
    }

    if(!isset($error)){
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, avatar=? WHERE id=?");
        $stmt->bind_param("ssssi", $new_username, $new_email, $new_password, $avatar_path, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['username'] = $new_username;
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style/dashboard.css">
</head>
<body>
<header>
    <h1>Vítej, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <?php if($user['avatar']): ?>
        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" class="top-avatar" alt="Avatar">
    <?php endif; ?>
    <a href="logout.php" class="logout-btn">Odhlásit se</a>
</header>

<div class="container">
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="#profile">Profil</a></li>
                <li><a href="#settings">Nastavení</a></li>
            </ul>
        </nav>
    </aside>

    <main>
        <section id="profile" class="card">
            <h2>Profil</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Avatar:</strong></p>
            <?php if($user['avatar']): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" width="120">
            <?php else: ?>
                <p>Žádný avatar</p>
            <?php endif; ?>
        </section>

        <section id="settings" class="card">
            <h2>Nastavení profilu</h2>
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Nové heslo:</label>
                <input type="password" name="password" placeholder="Nové heslo">

                <label>Avatar (.jpg):</label>
                <input type="file" name="avatar">

                <button type="submit" name="update_profile">Uložit změny</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>
