<?php
require_once 'core/models.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$cat = getCategoryById($pdo, $_GET['id'] ?? 0);
if (!$cat) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Category</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body class="center-page">
  <div class="box">
    <h2>Edit Category</h2>
    <form action="core/handleForms.php" method="POST">
      <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
      <div class="field"><label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required></div>
      <div class="field"><label>Description</label>
        <input type="text" name="description" value="<?= htmlspecialchars($cat['description'] ?? '') ?>"></div>
      <input type="submit" name="updateCategoryBtn" value="Update Category">
    </form>
    <a class="back" href="index.php">&larr; Back</a>
  </div>
</body>
</html>