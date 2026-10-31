<?php
require_once 'core/models.php';
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body class="auth-page">
  <div class="box">
    <div class="welcome">
      <h1>Welcome!</h1>
      <p>Please register if you do not have an account here.</p>
    </div>

    <?php if (isset($_SESSION['message'])) { ?>
      <div class="message"><?= htmlspecialchars($_SESSION['message']) ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php } ?>

    <form action="core/handleForms.php" method="POST">
      <div class="field">
        <label for="first_name">First name</label>
        <input type="text" name="first_name" id="first_name" required>
      </div>
      <div class="field">
        <label for="last_name">Last name</label>
        <input type="text" name="last_name" id="last_name" required>
      </div>
      <div class="field">
        <label for="address">Address</label>
        <input type="text" name="address" id="address">
      </div>
      <div class="field">
        <label for="age">Age</label>
        <input type="number" name="age" id="age" min="1">
      </div>
      <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
      </div>

      <input type="submit" name="registerUserBtn" value="Register">
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>