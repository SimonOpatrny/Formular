<?php
session_start();

// Pokud je uživatel už přihlášen, přesměruj ho na dashboard
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <main>
        <section>
            <div class="form">
                <div class="form-header">
                    <img src="images/apexlogo.png" alt="LogoApex">
                    <h2>Create an account</h2>
                </div>

                <!-- FORMULÁŘ REGISTRACE -->
                <form method="POST" action="register.php">
                    <div class="form-main">
                        <div class="form-main-inputs">
                            <label>Username</label>
                            <input type="text" name="username" placeholder="Username (alphanumeric only)" required>
                        </div>

                        <div class="form-main-inputs">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="form-main-inputs">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Enter your password (min 6 characters)" required>
                        </div>

                        <div class="form-main-inputs">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms">
                            I agree with <a href="#">terms of service and privacy policy</a>
                        </label>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="register">Create Account</button>
                    </div>
                </form>

                <div class="form-footer">
                    <a href="login.php">
                        <span>Already have an account?</span>
                        Sign in
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
