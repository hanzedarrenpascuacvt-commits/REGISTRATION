<?php
require_once 'core/models.php';
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body class="auth-page">
  <div class="box">
    <div class="welcome">
      <h1>LOG IN</h1>
    </div>

    <?php if (isset($_SESSION['success'])) { ?>
      <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['message'])) { ?>
      <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <form action="core/handleForms.php" method="POST">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>
      <input type="submit" name="loginUserBtn" value="Login">
    </form>

    <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>